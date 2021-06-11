<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_list_products()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/admin/products')
                ->assertStatus(200);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_product_name_is_required()
    {
        $this->withExceptionHandling();

        $data = $this->getData();

        Arr::forget($data, 'name');

        $response = $this->postJson('/api/admin/products', $data);


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
    public function test_can_store_product_with_unique_slug()
    {
        $this->withoutExceptionHandling();

        $data = $this->getData();

        $product = factory(Product::class)->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name'])
        ]);

        $response = $this->post('/api/admin/products', $data);

        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(201, $result['code']);
        $this->assertEquals($product->slug . '-2' , $result['data']['slug']);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_product_with_category_is_required()
    {

        $data = $this->getData();

        Arr::forget($data, 'category_id');

        $response = $this->postJson('/api/admin/products', $data);

        $result = $response->json();


        $this->assertEquals(422, $result['code']);
        $this->assertEquals("The category id field is required.", $result['message']);

        $this->assertTrue(Arr::has($result, 'errors.errorDetails.category_id'));
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_product_with_category_exists()
    {

        $data = $this->getData();
        $data['category_id'] = 2;

        $response = $this->postJson('/api/admin/products', $data);

        $result = $response->json();

        $this->assertEquals(422, $result['code']);
        $this->assertEquals("The selected category id is invalid.", $result['message']);


        $this->assertTrue(Arr::has($result, 'errors.errorDetails.category_id'));
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_product_with_brand_is_required()
    {

        $data = $this->getData();

        Arr::forget($data, 'brand_id');

        $response = $this->postJson('/api/admin/products', $data);

        $result = $response->json();

        $this->assertEquals(422, $result['code']);
        $this->assertEquals("The brand id field is required.", $result['message']);

        $this->assertTrue(Arr::has($result, 'errors.errorDetails.brand_id'));
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_product_with_brand_exists()
    {

        $data = $this->getData();
        $data['brand_id'] = 2;

        $response = $this->postJson('/api/admin/products', $data);

        $result = $response->json();

        $this->assertEquals(422, $result['code']);
        $this->assertEquals("The selected brand id is invalid.", $result['message']);


        $this->assertTrue(Arr::has($result, 'errors.errorDetails.brand_id'));
    }
        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_store_product_with_price_is_required()
    {

        $data = $this->getData();

        Arr::forget($data, 'price');

        $response = $this->postJson('/api/admin/products', $data);

        $result = $response->json();

        $this->assertEquals(422, $result['code']);
        $this->assertEquals("The price field is required.", $result['message']);

        $this->assertTrue(Arr::has($result, 'errors.errorDetails.price'));
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_product()
    {
        // $this->withoutExceptionHandling();

        $product = factory(Product::class)->create();

        $data = [
            'name' => $this->faker->sentence()
        ];

        $response = $this->put("/api/admin/products/{$product->id}", $data);

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
    public function test_can_delete_product()
    {
        // $this->withoutExceptionHandling();

        $product = factory(Product::class)->create();

        $response = $this->delete("/api/admin/products/{$product->id}");

        $response->assertStatus(200);
        $result = $response->json();

        $this->assertEquals(200, $result['code']);
        $this->assertEquals(0, Product::count());
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_delete_product_failied()
    {
        $response = $this->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->delete("/api/admin/products/1");

        $response->assertStatus(200);

        $result = $response->json();

        $this->assertEquals(404, $result['code']);
        $this->assertEquals(0, Product::count());
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_show_product()
    {
        $product = factory(Product::class)->create();

        $response = $this->get("/api/admin/products/{$product->id}");

        $response->assertStatus(200);

        $result = $response->json();

        $product = Product::first();

        $this->assertEquals(1, Product::count());
        $this->assertEquals($product->name, $result['data']['name']);
    }


    private function getData()
    {
        return [
            'name' => $this->faker->sentence(),
            'slug' => 'test',
            'category_id' => factory(Category::class)->create()->id,
            'brand_id' => factory(Brand::class)->create()->id,
            'price' => $this->faker->randomFloat(2)

        ];
    }
}
