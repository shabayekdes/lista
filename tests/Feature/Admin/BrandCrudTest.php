<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

use App\Models\Brand;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_list_brands()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/brands')
                ->assertStatus(200);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_brand_name_is_required()
    {
        $this->withExceptionHandling();

        $data = $this->getData();

        Arr::forget($data, 'name');

        $response = $this->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('/api/admin/brands', $data);


        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(422, $result['code']);
        $this->assertEquals("The name field is required.", $result['message']);

        $this->assertTrue(Arr::has($result, 'errors.errorDetails.name'));

    }
     /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_brand()
    {
        $this->withoutExceptionHandling();

        $brand = factory(Brand::class)->create();

        $data = [
            'name' => $this->faker->sentence()
        ];

        $response = $this->put("/api/admin/brands/{$brand->id}", $data);

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
    public function test_can_delete_brand()
    {
        $this->withoutExceptionHandling();

        $brand = factory(Brand::class)->create();


        $response = $this->delete("/api/admin/brands/{$brand->id}");

        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(200, $result['code']);
        $this->assertEquals(0, Brand::count());
    }
        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_show_brand()
    {
        $brand = factory(Brand::class)->create();

        $response = $this->get("/api/admin/brands/{$brand->id}");

        $response->assertStatus(200);

        $result = $response->json();

        $brand = Brand::first();

        $this->assertEquals(1, Brand::count());
        $this->assertEquals($brand->name, $result['data']['name']);
    }

    private function getData()
    {
        return [
            'name' => $this->faker->sentence()
        ];
    }
}
