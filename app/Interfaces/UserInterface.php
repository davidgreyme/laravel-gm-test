<?php


namespace App\Interfaces;

/**
 * Interface UserInterface
 * @package App\Interfaces
 */
interface UserInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data);

    /**
     * @param $orderData
     * @return mixed
     */
    public function getWithLargestProductCreation($orderData);
}
