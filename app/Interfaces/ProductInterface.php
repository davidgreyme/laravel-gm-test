<?php


namespace App\Interfaces;

/**
 * Interface ProductInterface
 * @package App\Interfaces
 */
interface ProductInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $userId
     * @return mixed
     */
    public function getByUserId($userId);

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data);
}
