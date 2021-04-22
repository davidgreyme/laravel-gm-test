<?php

namespace Tests\Feature\Controllers;

use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions, UserHelper;

    /**
     * @test for User Create.
     */
    public function test_user_register_success()
    {
        $response = $this->createUserAndAuthenticate();
        $response->assertStatus(200);
        $this->assertEquals('success', json_decode($response->getContent())->type);
        $this->assertEquals(1, json_decode($response->getContent())->success);
    }

    /**
     * @test Get users with largest product creation.
     */
    public function test_get_users_with_largest_product_creation_success()
    {
        $userCreateResponse = $this->createUserAndAuthenticate();
        $token = $userCreateResponse['access_token'];
        $response = $this->withToken($token)->json('get', "api/users");

        $response->assertStatus(200);
        $this->assertEquals('success', json_decode($response->getContent())->type);
        $this->assertEquals(1, json_decode($response->getContent())->success);
        $this->assertIsArray($response->json()['users']);
    }

    /**
     * @test fail for user with taken username.
     */
    public function test_user_register_fail()
    {
        $this->createUserAndAuthenticate(false);
        $response = $this->createUserAndAuthenticate(false);
        $response->assertStatus(422);
        $this->assertEquals('error', json_decode($response->getContent())->type);
        $this->assertEquals(0, json_decode($response->getContent())->success);
    }

    /**
     * @test for create user success.
     */
    public function test_user_update_success()
    {
        $userCreateResponse = $this->createUserAndAuthenticate();
        $user = $userCreateResponse['user'];
        $token = $userCreateResponse['access_token'];
        $username = 'newUsername';
        $response = $this->withToken($token)->json('put', "api/user/".$user['id'], ['username' => $username]);
        $userRepo = App::make(UserInterface::class);
        $user = $userRepo->getById($user['id']);
        $response->assertStatus(200);
        $this->assertEquals(Str::lower($username), $user->username);
        $this->assertEquals('success', json_decode($response->getContent())->type);
        $this->assertEquals(1, json_decode($response->getContent())->success);
    }

    /**
     * @test for create user fail.
     */
    public function test_user_update_fail()
    {
        $userCreateResponse = $this->createUserAndAuthenticate();
        $authUserCreateResponse = $this->createUserAndAuthenticate();
        $user = $userCreateResponse['user'];
        $token = $authUserCreateResponse['access_token'];

        $response = $this->withToken($token)->json('put', "api/user/".$user['id'], ['username' => 'newUsername']);
        $response->assertStatus(403);
        $this->assertEquals('error', json_decode($response->getContent())->type);
        $this->assertEquals(0, json_decode($response->getContent())->success);
    }


}
