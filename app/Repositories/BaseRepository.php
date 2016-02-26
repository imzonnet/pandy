<?php namespace App\Repositories;

interface BaseRepository
{
    /**
     * Get a listing of the resource.
     *
     * @param array $columns
     * @param string $order
     * @return Response
     */
    public function all($columns = array('*'), $order = 'asc');

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Response
     */
    public function create(array $attributes = array());

    /**
     * Update the specified resource in storage.
     *
     * @param  object $model
     * @param  array $attributes
     * @return Response
     */
    public function update($model, array $attributes = array());

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function delete($id);

    /**
     * Eager loading relations
     *
     * @param $relations
     * @return $this
     */
    public function with($relations);

    /**
     * Find a element
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * Find By Condition
     * @param $attribute
     * @param $value
     * @param array $columns
     * @param string $condition
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'), $condition = "=");

    /**
     * Get paginate
     * @param int $perPage
     * @param array $columns
     * @param string $order
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'), $order = "asc");

    /**
     * Get max value
     * @param string $field
     * @return mixed
     */
    public function max($field = 'id');

    public function whereIn($field, $data = array());
    public function whereRaw($field);
}