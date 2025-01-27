<?php

namespace Database\Factories;

use App\Models\Gasto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gasto>
 */
class GastoFactory extends Factory
{

    protected $model = Gasto::class;

    public function definition()
    {
        return [
            'id_users' => \App\Models\User::factory(), //Crea al usuario para el respectivo gasto
            'id_categoria' => \App\Models\Categoria::factory(), //Crear una categoria para el gasto
            'fecha' => $this->faker->date(),
            'monto' => $this->faker->randomFloat(2, 10, 1000),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
