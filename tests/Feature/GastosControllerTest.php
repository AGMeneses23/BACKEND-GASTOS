<?php

namespace Tests\Feature;

use App\Models\Gasto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GastosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_api_gastos()
    {
        //Crear un usuario
        /** @var \App\Models\User */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crear gastos asociados al usuario
        Gasto::factory()->count(3)->create(['id_users' => $user->id]);

        //Hacer la solicitud
        $response = $this->getJson('/api/gastos/lista');

        //Verificar la respuesta
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'id_users', 'id_categoria', 'fecha', 'monto', 'descripcion', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_show_api_gasto()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crea un gasto asociado al usuario
        $gasto = Gasto::factory()->create(['id_users' => $user->id]);

        //Hacer la solicitud
        $response = $this->getJson("/api/gastos/{$gasto->id}");

        //Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Gasto encontrado',
                'data' => [
                    'id' => $gasto->id,
                    'id_users' => $user->id,
                ]
            ]);
    }

    public function test_create_api_gasto()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear una categoría de prueba
        $categoria = \App\Models\Categoria::factory()->create(); // O usa create(['id' => 1]) si necesitas un id específico.

        //Datos para el nuevo gasto
        $payload = [
            'id_categoria' => $categoria->id,
            'fecha' => '2024-11-27',
            'monto' => 1500.50,
            'descripcion' => 'Gasto de prueba'
        ];

        //Hacer la solicitud
        $response = $this->postJson('/api/gastos/guardar', $payload);

        //Verificar la respuesta
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Gasto creado exitosamente',
            ]);

        //Verificar que el gasto fue creado en la base de datos
        $this->assertDatabaseHas('gasto', [
            'id_users' => $user->id,
            'descripcion' => 'Gasto de prueba',
        ]);
    }

    public function test_edit_api_gasto()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crea una categoría de prueba
        $categoria = \App\Models\Categoria::factory()->create();

        //Crear un gasto asociado al usuario y a la categoría creada
        $gasto = Gasto::factory()->create([
            'id_users' => $user->id,
            'id_categoria' => $categoria->id
        ]);

        //Datos para la actualización
        $payload = [
            'id_categoria' => $categoria->id, //Usar la categoria existente
            'fecha' => '2024-11-28',
            'monto' => 2000.75,
            'descripcion' => 'Gasto actualizado'
        ];

        //Hacer la solicitud
        $response = $this->putJson("/api/gastos/editar/{$gasto->id}", $payload);

        //Verificar la respuesta
        $response->assertJson([
            'message' => 'Gasto actualizado exitosamente',
        ]);

        //Verificar que el gasto fue actualizado en la base de datos
        $this->assertDatabaseHas('gasto', [
            'id' => $gasto->id,
            'descripcion' => 'Gasto actualizado',
        ]);
    }

    public function test_eliminar_api_gasto()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear una categoría de prueba
        $categoria = \App\Models\Categoria::factory()->create();

        // Crear un gasto asociado al usuario y a la categoría creada
        $gasto = Gasto::factory()->create([
            'id_users' => $user->id,
            'id_categoria' => $categoria->id
        ]);

        //Hacer la solicitud de eliminación
        $response = $this->deleteJson("/api/gastos/eliminar/{$gasto->id}");

        //Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Gasto eliminado exitosamente',
            ]);

        //Verificar que el gasto fue eliminado de la base de datos
        $this->assertDatabaseMissing('gasto', [
            'id' => $gasto->id,
        ]);
    }
}
