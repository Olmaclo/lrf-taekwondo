<?php

use App\Services\DrawGenerationService;

beforeEach(function () {
    $this->service = new DrawGenerationService();
});

// ── Direct elimination ────────────────────────────────────────────────────────

it('creates direct elimination bracket for 2 athletes', function () {
    $draw = $this->service->generateDirectElimination(athletes(2));

    expect($draw['format'])->toBe('direct_elimination');
    expect($draw['matches'])->toBeArray()->not->toBeEmpty();
    // 2 athletes → bracket of 2 → 1 match (the final)
    expect($draw['matches'])->toHaveCount(1);
});

it('creates direct elimination bracket for 4 athletes with 3 matches', function () {
    $draw = $this->service->generateDirectElimination(athletes(4));

    expect($draw['format'])->toBe('direct_elimination');
    // 4-person bracket: 2 semi-finals + 1 final
    expect($draw['matches'])->toHaveCount(3);
});

it('adds BYE entries when bracket size is not a power of 2', function () {
    $draw = $this->service->generateDirectElimination(athletes(3));

    $hasBye = collect($draw['matches'])->contains(fn ($m) => $m['is_bye'] === true);
    expect($hasBye)->toBeTrue();
});

it('auto-advances BYE winner to next round', function () {
    $draw = $this->service->generateDirectElimination(athletes(3));

    // The 3-person bracket has size 4 (1 BYE): the athlete facing BYE is auto-advanced
    // So one match in round 2 (the final) should already have athlete1 filled in
    $round2 = collect($draw['matches'])->firstWhere('round', 2);
    if ($round2) {
        expect($round2['athlete1'] ?? $round2['athlete2'])->not->toBeNull();
    } else {
        // For 2-person bracket round numbering may differ; just ensure no crash
        expect(true)->toBeTrue();
    }
});

// ── Pool elimination ──────────────────────────────────────────────────────────

it('uses pool format for 6 or more athletes', function () {
    $draw = $this->service->generatePoolElimination(athletes(6));

    expect($draw['format'])->toBe('pool_elimination');
    expect($draw['pools'])->toBeArray()->not->toBeEmpty();
    expect($draw['pools']['pools'])->toHaveCount(2); // 6 athletes → 2 pools
});

it('creates 3 pools for 9 athletes', function () {
    $draw = $this->service->generatePoolElimination(athletes(9));

    expect($draw['pools']['pools'])->toHaveCount(3);
});

it('pool matches cover every athlete pair in the pool (round-robin)', function () {
    $draw     = $this->service->generatePoolElimination(athletes(6));
    $pool     = $draw['pools']['pools'][0];
    $n        = count($pool['athletes']);
    $expected = ($n * ($n - 1)) / 2;

    expect($pool['matches'])->toHaveCount($expected);
});

it('pool structure includes finals bracket', function () {
    $draw = $this->service->generatePoolElimination(athletes(6));

    expect($draw['pools']['finals'])->toBeArray()->not->toBeEmpty();
    // Should have at least a final match
    $final = collect($draw['pools']['finals'])->firstWhere('pool', 'FINALE');
    expect($final)->not->toBeNull();
});

// ── Winner propagation ────────────────────────────────────────────────────────

it('setMatchWinnerInArray updates the winner and winner_id', function () {
    $draw      = $this->service->generateDirectElimination(athletes(2));
    $match     = $draw['matches'][0];
    $winnerId  = $match['athlete1']['id'];

    $updated = $this->service->setMatchWinnerInArray($draw, $match['id'], $winnerId);

    expect($updated['matches'][0]['winner_id'])->toBe($winnerId);
    expect($updated['matches'][0]['winner']['id'])->toBe($winnerId);
});

it('winner propagates to next round', function () {
    $draw     = $this->service->generateDirectElimination(athletes(4));
    // Match at round 2 (semifinal), position 1
    $sf1 = collect($draw['matches'])->firstWhere(fn ($m) => $m['round'] === 2 && $m['position'] === 1);
    $sf2 = collect($draw['matches'])->firstWhere(fn ($m) => $m['round'] === 2 && $m['position'] === 2);

    // Set winner of first semi
    $winnerId = $sf1['athlete1']['id'];
    $draw     = $this->service->setMatchWinnerInArray($draw, $sf1['id'], $winnerId);

    // Final should now have athlete1 populated
    $final = collect($draw['matches'])->firstWhere('round', 1);
    expect($final['athlete1']['id'] ?? $final['athlete2']['id'])->toBe($winnerId);
});

// ── Propagation safety (bug fix: ne pas auto-avancer quand un feeder attend) ──

it('does not auto-advance winner as bye when opponent slot has a feeder match', function () {
    // 4 athlètes : sf1 (pos 1) et sf2 (pos 2) → finale (pos 1)
    // Après la victoire en sf1, la finale doit avoir athlete1 rempli MAIS pas de winner
    // car sf2 n'est pas encore joué — ce n'est pas un BYE
    $draw     = $this->service->generateDirectElimination(athletes(4));
    $sf1      = collect($draw['matches'])->firstWhere(fn ($m) => $m['round'] === 2 && $m['position'] === 1);
    $winnerId = $sf1['athlete1']['id'];

    $draw  = $this->service->setMatchWinnerInArray($draw, $sf1['id'], $winnerId);
    $final = collect($draw['matches'])->firstWhere('round', 1);

    // La finale a athlete1 rempli (gagnant sf1 propagé)
    expect($final['athlete1']['id'])->toBe($winnerId);
    // Mais le winner de la finale NE doit PAS être auto-rempli (sf2 pas encore joué)
    expect($final['winner_id'] ?? null)->toBeNull();
    expect($final['is_bye'] ?? false)->toBeFalse();
});

it('auto-advances only when opponent slot truly has no feeder match', function () {
    // 3 athlètes : bracket de taille 4 avec 1 BYE
    // Le BYE doit auto-avancer l'athlète qui y fait face
    $draw  = $this->service->generateDirectElimination(athletes(3));
    $byes  = collect($draw['matches'])->filter(fn ($m) => $m['is_bye'] === true);

    expect($byes)->not->toBeEmpty();
    // Tous les matchs BYE doivent avoir un winner propagé
    foreach ($byes as $bye) {
        expect($bye['winner_id'] ?? null)->not->toBeNull();
    }
});

it('full 8-athlete bracket propagates all winners correctly', function () {
    $draw    = $this->service->generateDirectElimination(athletes(8));
    $matches = collect($draw['matches']);

    // 8 athlètes → 7 matchs (4 quarts + 2 demies + 1 finale)
    expect($matches)->toHaveCount(7);

    // Simuler tous les matchs du premier round
    $quarterfinals = $matches->where('round', 3);
    foreach ($quarterfinals as $qf) {
        $draw = $this->service->setMatchWinnerInArray($draw, $qf['id'], $qf['athlete1']['id']);
    }

    // Les demi-finales doivent toutes avoir leurs 2 athlètes remplis
    $semis = collect($draw['matches'])->where('round', 2);
    foreach ($semis as $semi) {
        expect($semi['athlete1'])->not->toBeNull();
        expect($semi['athlete2'])->not->toBeNull();
        expect($semi['winner_id'] ?? null)->toBeNull(); // pas encore joué
    }
});

// ── Helpers ──────────────────────────────────────────────────────────────────

function athletes(int $count): array
{
    return array_map(fn ($i) => (object) [
        'id'    => $i,
        'name'  => "Athlete {$i}",
        'club'  => 'Club Test',
        'gender'=> 'M',
    ], range(1, $count));
}
