<?php
namespace Database\Factories;

use App\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'value' => $this->faker->sentence(6),
          'status' => $this->faker->randomElement(['open', 'closed'])
        ];
    }
}
