<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends Controller
{
    /**
     * @var UserInterface
     */
    private $userRepo;

    /**
     * UserController constructor.
     * @param UserInterface $userRepo
     */
    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(PaginateRequest $request)
    {
        try {
            $orderData = $request->inputs();
            $users = $this->userRepo->getWithLargestProductCreation($orderData);

            return response()->json([
                'success' => 1,
                'type'    => 'success',
                'users'   => UserResource::collection($users),
            ])->setStatusCode(200);

        } catch (\Exception $exception) {

            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * @param User $user
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update(User $user, UserUpdateRequest $request)
    {
        try {
            $access = Gate::inspect('update', $user);

            if (!$access->allowed()) {
                return response()->json([
                    'success' => 0,
                    'type'    => 'error',
                    'message' => $access->message(),
                ])->setStatusCode(403);
            }
            $data = $request->all();
            $this->userRepo->update($user->id, $data);

            return response()->json([
                'success' => 1,
                'type'    => 'success',
            ])->setStatusCode(200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
