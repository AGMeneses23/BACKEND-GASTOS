<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_index_api_categorias()
    {
        //Crear un usuario
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crear categoría asociadas al usuario
        Categoria::factory()->count(3)->create(['user_id' => $user->id]);

        //Hacer la solicitud
        $response = $this->getJson('/api/categorias/lista');

        //Verificar la respuesta
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'nombre', 'user_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_create_api_categoria()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Datos para la nueva categoría
        $payload = ['nombre' => 'Nueva Categoría'];

        //Hacer la solicitud
        $response = $this->postJson('/api/categorias/guardar', $payload);

        //Verificar la respuesta
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Categoría creada exitosamente',
            ]);

        //Verificar que la categoría fue creada en la base de datos
        $this->assertDatabaseHas('categorias', [
            'nombre' => 'Nueva Categoría',
            'user_id' => $user->id,
        ]);
    }

    public function test_edit_api_categorias()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crea categoría
        $categoria = Categoria::factory()->create(['user_id' => $user->id]);

        // Datos para actualización
        $payload = ['nombre' => 'Categoría Actualizada'];

        //Hacer solicitud
        $response = $this->putJson("/api/categorias/editar/{$categoria->id}", $payload);

        //Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Categoría actualizada correctamente',
            ]);

        //Verifica que la categoría fue creada en la base de datos
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Categoría Actualizada',
        ]);
    }

    public function test_eliminar_api_categoria()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crear una categoría
        $categoria = Categoria::factory()->create(['user_id' => $user->id]);

        //Hacer la solicitud
        $response = $this->deleteJson("/api/categorias/eliminar/{$categoria->id}");

        //Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Categoría eliminada exitosamente',
            ]);

        //Verificar que la categoría fue eliminada de la base de datos
        $this->assertDatabaseMissing('categorias', [
            'id' => $categoria->id,
        ]);
    }
}
