<?php

use App\Services\WeightCategoryService;

beforeEach(function () {
    $this->svc = new WeightCategoryService();
});

// ── getWeightCategories ───────────────────────────────────────────────────────

it('returns weight category names for senior male', function () {
    $cats = $this->svc->getWeightCategories('Senior', 'M');
    expect($cats)->toBeArray()->not->toBeEmpty();
    // Known Senior M categories
    expect($cats)->toContain('-68kg');
    expect($cats)->toContain('+87kg');
});

it('returns weight category names for senior female', function () {
    $cats = $this->svc->getWeightCategories('Senior', 'F');
    expect($cats)->toBeArray()->not->toBeEmpty();
    expect($cats)->toContain('-57kg');
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

it('finds correct weight category -68kg for 65kg senior male', function () {
    $cat = $this->svc->getWeightCategoryFromWeight(65.0, 'Senior', 'M');
    expect($cat)->toBe('-68kg');
});

it('finds correct weight category -57kg for 56kg senior female', function () {
    $cat = $this->svc->getWeightCategoryFromWeight(56.0, 'Senior', 'F');
    expect($cat)->toBe('-57kg');
});

it('returns heaviest open category for overweight athlete', function () {
    $cat = $this->svc->getWeightCategoryFromWeight(150.0, 'Senior', 'M');
    expect($cat)->toBe('+87kg');
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
    // Key format: "AgeCategory|Gender|WeightCat"
    expect($list[0]['key'])->toMatch('/^[A-Za-z]+\|[MF]\|.+kg$/');
});
