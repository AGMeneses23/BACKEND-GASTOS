<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'user_id' => User::factory(),
        ];
    }
}
