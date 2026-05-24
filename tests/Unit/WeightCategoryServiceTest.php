<?php

use App\Services\WeightCategoryService;

beforeEach(function () {
    $this->svc = new WeightCategoryService();
});

// ── getWeightCategories ───────────────────────────────────────────────────────

it('returns weight category names for senior male', function () {
    $cats = $this->svc->getWeightCategories('Senior', 'M');
    expect($cats)->toBeArray()->not->toBeEmpty();
    expect($cats)->toContain('-54kg');
    expect($cats)->toContain('-68kg');
    expect($cats)->toContain('+87kg');
    expect($cats)->toHaveCount(8);
});

it('returns weight category names for senior female', function () {
    $cats = $this->svc->getWeightCategories('Senior', 'F');
    expect($cats)->toBeArray()->not->toBeEmpty();
    expect($cats)->toContain('-57kg');
    expect($cats)->toContain('+73kg');
    expect($cats)->toHaveCount(8);
});

it('returns weight category names for junior male', function () {
    $cats = $this->svc->getWeightCategories('Junior', 'M');
    expect($cats)->toContain('-45kg');
    expect($cats)->toContain('+78kg');
    expect($cats)->toHaveCount(10);
});

it('returns weight category names for junior female', function () {
    $cats = $this->svc->getWeightCategories('Junior', 'F');
    expect($cats)->toContain('-42kg');
    expect($cats)->toContain('-68kg');
    expect($cats)->toContain('+68kg');
    expect($cats)->toHaveCount(10);
});

it('returns weight category names for cadet male', function () {
    $cats = $this->svc->getWeightCategories('Cadet', 'M');
    expect($cats)->toContain('-33kg');
    expect($cats)->toContain('+65kg');
    expect($cats)->toHaveCount(10);
});

it('returns weight category names for cadet female', function () {
    $cats = $this->svc->getWeightCategories('Cadet', 'F');
    expect($cats)->toContain('-29kg');
    expect($cats)->toContain('+59kg');
    expect($cats)->toHaveCount(10);
});

it('returns full nested structure when called with no arguments', function () {
    $all = $this->svc->getWeightCategories();
    expect($all)->toBeArray()->not->toBeEmpty();
    expect($all)->toHaveKey('Senior');
    expect($all)->toHaveKey('Junior');
    expect($all)->toHaveKey('Cadet');
    expect($all)->toHaveKey('Benjamin');
    expect($all)->toHaveKey('Minime');
});

// ── getAgeCategoryFromAge ─────────────────────────────────────────────────────

it('maps age 22 to Senior', function () {
    expect($this->svc->getAgeCategoryFromAge(22))->toBe('Senior');
});

it('maps age 16 to Junior', function () {
    expect($this->svc->getAgeCategoryFromAge(16))->toBe('Junior');
});

it('maps age 13 to Cadet', function () {
    expect($this->svc->getAgeCategoryFromAge(13))->toBe('Cadet');
});

it('maps age 11 to Minime', function () {
    expect($this->svc->getAgeCategoryFromAge(11))->toBe('Minime');
});

it('maps age 9 to Benjamin', function () {
    expect($this->svc->getAgeCategoryFromAge(9))->toBe('Benjamin');
});

it('returns null for athlete too young (age < 8)', function () {
    expect($this->svc->getAgeCategoryFromAge(5))->toBeNull();
});

// ── getWeightCategoryFromWeight ───────────────────────────────────────────────

it('assigns -54kg to a 50kg senior male', function () {
    expect($this->svc->getWeightCategoryFromWeight(50.0, 'Senior', 'M'))->toBe('-54kg');
});

it('assigns -54kg to a very light senior male (10kg)', function () {
    expect($this->svc->getWeightCategoryFromWeight(10.0, 'Senior', 'M'))->toBe('-54kg');
});

it('finds correct weight category -68kg for 65kg senior male', function () {
    expect($this->svc->getWeightCategoryFromWeight(65.0, 'Senior', 'M'))->toBe('-68kg');
});

it('finds correct weight category -57kg for 56kg senior female', function () {
    expect($this->svc->getWeightCategoryFromWeight(56.0, 'Senior', 'F'))->toBe('-57kg');
});

it('assigns -46kg to a very light senior female', function () {
    expect($this->svc->getWeightCategoryFromWeight(10.0, 'Senior', 'F'))->toBe('-46kg');
});

it('assigns -45kg to a very light junior male', function () {
    expect($this->svc->getWeightCategoryFromWeight(30.0, 'Junior', 'M'))->toBe('-45kg');
});

it('assigns -42kg to a very light junior female', function () {
    expect($this->svc->getWeightCategoryFromWeight(30.0, 'Junior', 'F'))->toBe('-42kg');
});

it('assigns -33kg to a very light cadet male', function () {
    expect($this->svc->getWeightCategoryFromWeight(20.0, 'Cadet', 'M'))->toBe('-33kg');
});

it('returns +87kg for overweight senior male', function () {
    expect($this->svc->getWeightCategoryFromWeight(150.0, 'Senior', 'M'))->toBe('+87kg');
});

it('returns +78kg for overweight junior male', function () {
    expect($this->svc->getWeightCategoryFromWeight(100.0, 'Junior', 'M'))->toBe('+78kg');
});

// ── buildCategoryString ───────────────────────────────────────────────────────

it('builds category string correctly', function () {
    $str = $this->svc->buildCategoryString('Senior', 'M', '-68kg');
    expect($str)->toBe('Senior M -68kg');
});

// ── getFlatCategoriesForType ──────────────────────────────────────────────────

it('returns flat category list with key and label', function () {
    $list = $this->svc->getFlatCategoriesForType('kyorugi');
    expect($list)->toBeArray()->not->toBeEmpty();
    expect($list[0])->toHaveKey('key');
    expect($list[0])->toHaveKey('label');
    expect($list[0]['key'])->toMatch('/^[A-Za-z]+\|[MF]\|.+kg$/');
});
