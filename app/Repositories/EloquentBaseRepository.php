<?php namespace App\Repositories;

class EloquentBaseRepository implements BaseRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Get all record of a table
     *
     * @param array $columns
     * @param string $order
     * @return mixed
     */
    public function all($columns = array('*'), $order = 'asc')
    {
        return $this->model->orderBy('id', $order)->get($columns);
    }

    /**
     * Create new record
     *
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes = array())
    {
        return $this->model->create($attributes);
    }

    /**
     * Update a record
     *
     * @param array $attributes
     * @return mixed
     * @throws \Exception
     */
    public function update($model, array $attributes = array())
    {
        return $model->update($attributes);
    }

    /**
     * Delete current item
     *
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id){
        return $this->find($id)->delete();
    }

    public function with($relations)
    {
        if (is_string($relations))
        {
            $relations = func_get_args();
        }

        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * Find a record with ID primary key
     *
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws \Exception
     */
    public function find($id, $columns = array('*'))
    {
        $element = $this->model->find($id, $columns);
        if (is_null($element)) {
            abort(404, 'Not Found!');
        }
        return $element;
    }

    /**
     * Search a record with condition
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     * @param string $con
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'), $con = "=")
    {
        return $this->model->where($attribute, $con, $value)->first($columns);
    }

    /**
     * Pagination
     *
     * @param int $perPage
     * @param array $columns
     * @param string $order
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'), $order = 'asc')
    {
        return $this->model->orderBy('id', $order)->paginate($perPage, $columns);
    }

    /**
     * Get max value of a item
     *
     * @param string $field
     * @return mixed
     */
    public function max($field = 'id'){
        return $this->model->max($field);
    }

    /**
     * Get list record from list data
     * @param $field
     * @param array $data
     * @return mixed
     */
    public function whereIn($field, $data = array()){
        return $this->model->whereIn($field, $data);
    }

    /**
     * Get list record from list data
     * @param $field
     * @return mixed
     */
    public function whereRaw($field){
        return $this->model->whereRaw($field);
    }
}