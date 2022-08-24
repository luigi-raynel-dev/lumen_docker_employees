<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Occupation;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;
    public function definition()
    {
    	return [
            'firstname' => $this->faker->firstname,
            'lastname' => $this->faker->lastname,
            'email' => $this->faker->unique()->safeEmail,
            'birthdate' => $this->faker->date(),
            'occupation_id' => $this->faker->randomElement(Occupation::all())['id']
        ];
    }
}
