<?php

namespace Database\Factories;

use App\Models\Occupation;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class OccupationFactory extends Factory
{
    protected $model = Occupation::class;

    public function definition()
    {
    	return [
            'name' => $this->faker->word,
            'department_id' => $this->faker->randomElement(Department::all())['id']
        ];
    }
}
