<?php namespace App\Repositories;


interface UserRepository extends BaseRepository
{
    /**
     * Return all records/entities for users
     *
     * @param bool $status
     * @return array
     */
    public function listUsers($status = true);

    /**
     * @param $email
     * @return mixed
     */
    public function getUserIdByEmail($email);

}