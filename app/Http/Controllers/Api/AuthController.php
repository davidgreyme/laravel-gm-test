<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{

    /**
     * @var UserInterface
     */
    private $userRepo;

    /**
     * AuthController constructor.
     * @param UserInterface $userRepo
     */
    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function register(UserCreateRequest $request)
    {
        try {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $user = $this->userRepo->store($data);
            $accessToken = $user->createToken('authToken')->accessToken;

            return response()->json([
                'success'      => 1,
                'type'         => 'success',
                'user'         => new UserResource($user),
                'access_token' => $accessToken
            ])->setStatusCode(200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $data = $request->all();
        if (!auth()->attempt($data)) {
            return response()->json([
                'message' => 'Invalid Credentials',
                'type'    => 'error',
                'success' => '0'
            ])->setStatusCode(422);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'success'      => 1,
            'type'         => 'success',
            'user'         => new UserResource(auth()->user()),
            'access_token' => $accessToken,
        ])->setStatusCode(200);
    }

    /**
     * Logout Api User
     * @return JsonResponse
     */
    public function logout()
    {

        try {
            auth()->user()->token()->revoke();

            return response()->json([
                'success' => 1,
                'type'    => 'success',
            ])->setStatusCode(200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
