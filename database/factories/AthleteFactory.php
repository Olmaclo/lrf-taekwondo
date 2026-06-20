<?php

namespace Database\Factories;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AthleteFactory extends Factory
{
    protected $model = Athlete::class;

    public function definition(): array
    {
        $gender        = $this->faker->randomElement(['M', 'F']);
        $ageCategories = ['Minime', 'Cadet', 'Junior', 'Senior'];
        $weightCats    = ['-46kg', '-49kg', '-53kg', '-57kg', '-62kg', '-68kg', '-74kg', '-80kg', '+80kg'];
        $birthYear     = $this->faker->numberBetween(1995, 2012);

        return [
            'first_name'          => $this->faker->firstName($gender === 'M' ? 'male' : 'female'),
            'last_name'           => $this->faker->lastName(),
            'birth_date'          => $this->faker->dateTimeBetween("{$birthYear}-01-01", "{$birthYear}-12-31")->format('Y-m-d'),
            'gender'              => $gender,
            'weight'              => $this->faker->randomFloat(1, 40, 100),
            'age_category'        => $this->faker->randomElement($ageCategories),
            'weight_category'     => $this->faker->randomElement($weightCats),
            'club'                => $this->faker->company() . ' TK',
            'nationality'         => 'Sénégalais(e)',
            'license_number'      => 'SEN-' . $this->faker->numerify('####'),
            'registration_status' => 'pending',
            'payment_status'      => 'unpaid',
            'event_id'            => Event::factory(),
            'coach_id'            => null,
        ];
    }

    public function validated(): self
    {
        return $this->state([
            'registration_status' => 'validated',
            'payment_status'      => 'validated',
            'payment_amount'      => 5000,
            'receipt_number'      => Athlete::generateReceiptNumber(),
        ]);
    }

    public function pending(): self
    {
        return $this->state(['registration_status' => 'pending', 'payment_status' => 'unpaid']);
    }
}
