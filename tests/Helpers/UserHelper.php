<?php


namespace Tests\Helpers;


use App\Interfaces\UserInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;

trait UserHelper
{
    use WithFaker;

    /**
     * Create user.
     *
     * @return mixed
     */
    public function createUser()
    {
        $userRepo = App::make(UserInterface::class);
        $userData = [
            'username' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('admin')
        ];
        return $userRepo->store($userData);
    }

    /**
     * Create and authenticate user/
     *
     * @param bool $fake
     * @return mixed
     */
    public function createUserAndAuthenticate($fake = true)
    {
        return $this->post('/api/auth/register', [
            'username' => $fake ? $this->faker->name : 'admin',
            'email' => $this->faker->email,
            'password' => bcrypt('admin')
        ]);
    }
}
