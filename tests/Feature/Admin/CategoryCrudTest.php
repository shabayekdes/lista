<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the test
     */
    public function setUp(): void
    {
        parent::setUp();
        // set your headers here
        // $this->withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Accept' => 'application/json'
        // ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_list_categories()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/categories')
                ->assertStatus(200);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_category_name_is_required()
    {
        // $this->withoutExceptionHandling();

        $data = [
            'name' => $this->faker->sentence()
        ];

        $response = $this->post('/api/admin/categories', $data);

        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(201, $result['code']);
        $this->assertEquals(1, Category::count());
        $this->assertEquals($data['name'], $result['data']['name']);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_category()
    {
        // $this->withoutExceptionHandling();

        $category = factory(Category::class)->create();

        $data = [
            'name' => $this->faker->sentence()
        ];

        $response = $this->put("/api/admin/categories/{$category->id}", $data);

        $response->assertStatus(200);

        $result = $response->json();


        $this->assertEquals(201, $result['code']);
        $this->assertEquals($data['name'], $result['data']['name']);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_delete_category()
    {
        // $this->withoutExceptionHandling();

        $category = factory(Category::class)->create();


        $response = $this->delete("/api/admin/categories/{$category->id}");

        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(200, $result['code']);
        $this->assertEquals(0, Category::count());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_delete_category_failied()
    {
        $response = $this->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->delete("/api/admin/categories/1");

        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(404, $result['code']);
        $this->assertEquals(0, Category::count());
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_show_category()
    {
        $category = factory(Category::class)->create();

        $response = $this->get("/api/admin/categories/{$category->id}");

        $response->assertStatus(200);

        $result = $response->json();

        $category = Category::first();

        $this->assertEquals(1, Category::count());
        $this->assertEquals($category->name, $result['data']['name']);
    }
}
