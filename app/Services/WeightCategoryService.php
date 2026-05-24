<?php

namespace App\Services;

class WeightCategoryService
{
    private array $ageCategories = [
        'Benjamin' => ['min' => 8,  'max' => 9],
        'Minime'   => ['min' => 10, 'max' => 11],
        'Cadet'    => ['min' => 12, 'max' => 14],
        'Junior'   => ['min' => 15, 'max' => 17],
        'Senior'   => ['min' => 18, 'max' => 99],
    ];

    // Gender keys are 'M' and 'F' throughout.
    // Lower bound of the first category in each group is 0 so that any weight
    // below the nominal floor is still correctly assigned to the lightest class.
    private array $weightCategories = [
        'Benjamin' => [
            'M' => ['-21kg'=>[0,21],'-24kg'=>[21,24],'-27kg'=>[24,27],'-30kg'=>[27,30],'-33kg'=>[30,33],'-37kg'=>[33,37],'-41kg'=>[37,41],'-45kg'=>[41,45],'-49kg'=>[45,49],'+49kg'=>[49,999]],
            'F' => ['-17kg'=>[0,17],'-20kg'=>[17,20],'-23kg'=>[20,23],'-26kg'=>[23,26],'-29kg'=>[26,29],'-33kg'=>[29,33],'-37kg'=>[33,37],'-41kg'=>[37,41],'-44kg'=>[41,44],'+44kg'=>[44,999]],
        ],
        'Minime' => [
            'M' => ['-27kg'=>[0,27],'-30kg'=>[27,30],'-33kg'=>[30,33],'-37kg'=>[33,37],'-41kg'=>[37,41],'-45kg'=>[41,45],'-49kg'=>[45,49],'-53kg'=>[49,53],'-57kg'=>[53,57],'+57kg'=>[57,999]],
            'F' => ['-23kg'=>[0,23],'-26kg'=>[23,26],'-29kg'=>[26,29],'-33kg'=>[29,33],'-37kg'=>[33,37],'-41kg'=>[37,41],'-44kg'=>[41,44],'-47kg'=>[44,47],'-51kg'=>[47,51],'+51kg'=>[51,999]],
        ],
        'Cadet' => [
            'M' => ['-33kg'=>[0,33],'-37kg'=>[33,37],'-41kg'=>[37,41],'-45kg'=>[41,45],'-49kg'=>[45,49],'-53kg'=>[49,53],'-57kg'=>[53,57],'-61kg'=>[57,61],'-65kg'=>[61,65],'+65kg'=>[65,999]],
            'F' => ['-29kg'=>[0,29],'-33kg'=>[29,33],'-37kg'=>[33,37],'-41kg'=>[37,41],'-44kg'=>[41,44],'-47kg'=>[44,47],'-51kg'=>[47,51],'-55kg'=>[51,55],'-59kg'=>[55,59],'+59kg'=>[59,999]],
        ],
        'Junior' => [
            'M' => ['-45kg'=>[0,45],'-48kg'=>[45,48],'-51kg'=>[48,51],'-55kg'=>[51,55],'-59kg'=>[55,59],'-63kg'=>[59,63],'-68kg'=>[63,68],'-73kg'=>[68,73],'-78kg'=>[73,78],'+78kg'=>[78,999]],
            'F' => ['-42kg'=>[0,42],'-44kg'=>[42,44],'-46kg'=>[44,46],'-49kg'=>[46,49],'-52kg'=>[49,52],'-55kg'=>[52,55],'-59kg'=>[55,59],'-63kg'=>[59,63],'-68kg'=>[63,68],'+68kg'=>[68,999]],
        ],
        'Senior' => [
            'M' => ['-54kg'=>[0,54],'-58kg'=>[54,58],'-63kg'=>[58,63],'-68kg'=>[63,68],'-74kg'=>[68,74],'-80kg'=>[74,80],'-87kg'=>[80,87],'+87kg'=>[87,999]],
            'F' => ['-46kg'=>[0,46],'-49kg'=>[46,49],'-53kg'=>[49,53],'-57kg'=>[53,57],'-62kg'=>[57,62],'-67kg'=>[62,67],'-73kg'=>[67,73],'+73kg'=>[73,999]],
        ],
    ];

    public function getAgeCategories(): array
    {
        return $this->ageCategories;
    }

    public function getAgeCategoryNames(): array
    {
        return array_keys($this->ageCategories);
    }

    /**
     * Return weight category names for a given age category and gender.
     * If called with no args, returns the full nested structure.
     */
    public function getWeightCategories(?string $ageCategory = null, ?string $gender = null): array
    {
        if ($ageCategory === null) {
            return $this->weightCategories;
        }
        if ($gender === null) {
            return $this->weightCategories[$ageCategory] ?? [];
        }
        return array_keys($this->weightCategories[$ageCategory][$gender] ?? []);
    }

    public function getAllWeightCategories(): array
    {
        return $this->weightCategories;
    }

    public function getAgeCategoryFromAge(int $age): ?string
    {
        foreach ($this->ageCategories as $category => $range) {
            if ($age >= $range['min'] && $age <= $range['max']) {
                return $category;
            }
        }
        return null; // Below minimum age — not eligible
    }

    public function getWeightCategoryFromWeight(float $weight, string $ageCategory, string $gender): ?string
    {
        $categories = $this->weightCategories[$ageCategory][$gender] ?? [];
        foreach ($categories as $catName => $range) {
            if ($weight >= $range[0] && $weight < $range[1]) {
                return $catName;
            }
        }
        // Heaviest open category catches anything above
        $last = array_key_last($categories);
        return $last ?: null;
    }

    /** Build a display string like "Senior M -54kg" */
    public function buildCategoryString(string $ageCategory, string $gender, string $weightCategory): string
    {
        return "{$ageCategory} {$gender} {$weightCategory}";
    }

    /** Return all flat category entries for an event type */
    public function getFlatCategoriesForType(string $eventType = 'kyorugi'): array
    {
        $flat = [];
        foreach ($this->weightCategories as $age => $genders) {
            foreach ($genders as $gender => $weights) {
                foreach (array_keys($weights) as $weight) {
                    $flat[] = [
                        'key'   => "{$age}|{$gender}|{$weight}",
                        'label' => $this->buildCategoryString($age, $gender, $weight),
                    ];
                }
            }
        }
        return $flat;
    }
}
