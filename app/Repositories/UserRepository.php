<?php


namespace App\Repositories;


use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository implements UserInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param $orderData
     * @return mixed
     */
    public function getWithLargestProductCreation($orderData)
    {
        return $this->model
            ->select('users.*')
            ->selectRaw('COUNT(products.id) as products_count')
            ->leftJoin('products', 'products.user_id', 'users.id')
            ->groupBy('users.id')
            ->orderBy('products_count', $orderData['order'])
            ->skip($orderData['skip'])
            ->take($orderData['take'])
            ->get();
    }
}
