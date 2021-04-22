<?php

namespace Tests\Feature\Controllers;

use App\Interfaces\ProductInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions, UserHelper, WithFaker;

    /**
     * @test create product success.
     */
    public function test_product_create_success()
    {
        $userCreateResponse = $this->createUserAndAuthenticate();
        $user = $userCreateResponse['user'];
        $token = $userCreateResponse['access_token'];

        $response = $this->withToken($token)->json('post', '/api/products', [
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500),
            'user_id' => $user['id']
        ]);
        $response->assertStatus(200);
        $this->assertEquals('success', json_decode($response->getContent())->type);
        $this->assertEquals(1, json_decode($response->getContent())->success);
    }

    /**
     * @test create product fail.
     */
    public function test_product_create_fail()
    {
        $userCreateResponse = $this->createUserAndAuthenticate();
        $user = $userCreateResponse['user'];
        $token = $userCreateResponse['access_token'];

        $response = $this->withToken($token)->json('post', '/api/products', [
            'price'   => $this->faker->numberBetween(150, 500),
            'user_id' => $user['id']
        ]);
        $response->assertStatus(422);
        $this->assertEquals('error', json_decode($response->getContent())->type);
        $this->assertEquals(0, json_decode($response->getContent())->success);
    }

    /**
     * @test update product success.
     */
    public function test_product_update_success()
    {
        $userCreateResponse = $this->createUserAndAuthenticate();
        $user = $userCreateResponse['user'];
        $token = $userCreateResponse['access_token'];
        $productRepo = App::make(ProductInterface::class);
        $productData = [
            'user_id' => $user['id'],
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ];
        $product = $productRepo->store($productData);
        $response = $this->withToken($token)->json('put', '/api/products/'.$product->id, [
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ]);
        $response->assertStatus(200);
        $this->assertEquals('success', json_decode($response->getContent())->type);
        $this->assertEquals(1, json_decode($response->getContent())->success);
    }

    /**
     * @test update product fail.
     */
    public function test_product_update_fail()
    {
        $authUserCreateResponse = $this->createUserAndAuthenticate();
        $token = $authUserCreateResponse['access_token'];
        $user = $this->createUser();
        $productRepo = App::make(ProductInterface::class);
        $productData = [
            'user_id' => $user->id,
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ];
        $product = $productRepo->store($productData);

        $response = $this->withToken($token)->json('put', '/api/products/'.$product->id, [
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ]);
        $response->assertStatus(403);
        $this->assertEquals('error', json_decode($response->getContent())->type);
        $this->assertEquals(0, json_decode($response->getContent())->success);
    }

    /**
     * @test update product fail.
     */
    public function test_product_delete_fail()
    {
        $authUserCreateResponse = $this->createUserAndAuthenticate();
        $token = $authUserCreateResponse['access_token'];
        $user = $this->createUser();
        $productRepo = App::make(ProductInterface::class);
        $productData = [
            'user_id' => $user->id,
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ];
        $product = $productRepo->store($productData);

        $response = $this->withToken($token)->json('delete', '/api/products/'.$product->id, [
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ]);
        $response->assertStatus(403);
        $this->assertEquals('error', json_decode($response->getContent())->type);
        $this->assertEquals(0, json_decode($response->getContent())->success);
    }

    /**
     * @test update product fail.
     */
    public function test_product_delete_success()
    {
        $authUserCreateResponse = $this->createUserAndAuthenticate();
        $token = $authUserCreateResponse['access_token'];
        $user = $this->createUser();
        $productRepo = App::make(ProductInterface::class);
        $productData = [
            'user_id' => $user->id,
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ];
        $product = $productRepo->store($productData);

        $response = $this->withToken($token)->json('delete', '/api/products/'.$product->id, [
            'title'   => $this->faker->word,
            'price'   => $this->faker->numberBetween(150, 500)
        ]);
        $response->assertStatus(403);
        $this->assertEquals('error', json_decode($response->getContent())->type);
        $this->assertEquals(0, json_decode($response->getContent())->success);
    }
}
