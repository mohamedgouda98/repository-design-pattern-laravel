<?php

namespace Unlimited\Repository\Services;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class BaseEloquentService
{
    /**
     * Model name
     *
     * @var string
     */
    protected $modelName;

    /**
     * Current Object instance
     *
     * @var object
     */
    protected $instance;

    /**
     * Order Options
     *
     * @var array
     */
    protected $orderOptions = [];

    /**
     * Default order by
     *
     * @var string
     */
    private $orderBy = 'id';

    /**
     * Default order direction
     *
     * @var string
     */
    private $orderDirection = 'desc';

    protected $filterableFields = [];

    protected $searchableFields = [];

    /**
     * Return all records
     *
     * @param string $orderBy
     * @param array $relations
     * @param array $parameters
     * @return mixed
     */
    public function getAll($orderBy = 'id', array $relations = [],$relationCountWhere= '', $relationWhere = '' , array $condition=[], array $relationsCount = [], array $parameters = [], $fields = ['*'])
    {
        $instance = $this->getQueryBuilder($fields);


        $this->resolveSearchs($instance);

        $this->resolveFilters($instance);

        $this->applyParameters($instance, $parameters);

        if($relationCountWhere && $condition ){
            $instance->withCount([
                $relationCountWhere => function ($query) use ($condition) {
                    $query->where($condition);
                },

            ]);
        }

        if($relationWhere && $condition ){
            $instance->with([
                $relationWhere => function ($query) use ($condition,$orderBy) {
                    $query->where($condition)->orderBy($orderBy);
                },

            ]);
        }

        $instance->with($relations)->withCount($relationsCount)
                ->orderBy($this->getOrderBy(), $this->getOrderDirection());

        return$instance->get();
    }

    /**
     * Return paginated items
     *
     * @param string $orderBy
     * @param array $relations
     * @param int $paginate
     * @param array $parameters
     * @return mixed
     */
    public function paginate($orderBy = 'id', array $relations = [], array $relationsCount = [], $paginate = 25, array $parameters = [], $fields = ['*'])
    {
        $instance = $this->getQueryBuilder($fields);

        $this->resolveSearchs($instance);

        $this->resolveFilters($instance);

        $this->applyParameters($instance, $parameters);

        return $instance->with($relations)->withCount($relationsCount)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->paginate($paginate)->withQueryString();
    }

    /**
     * Apply parameters
     *
     * @param $query
     * @param array $filters
     * @return mixed
     */
    protected function applyParameters($instance, array $parameters = [])
    {
        foreach ($parameters as $parameter => $value) {

            if (in_array($parameter,$this->searchableFields)) {
                $instance->where($parameter, 'LIKE', "$value%");
            }else{
                $instance->where($parameter, $value);
            }

        }


    }

    /**
     * Apply filters query, which can be extended in child classes for filtering
     *
     * @param object $instance model instance
     * @param string $q search query
     * @return mixed
     */
    protected function applyFilters($instance, array $parameters = [])
    {
        // by default apply simple parameters
        $this->applyParameters($instance, $parameters);
    }

    /**
     * Apply search query, which can be extended in child classes for searching
     *
     * @param object $instance model instance
     * @param string $q search query
     * @return mixed
     */
    protected function applySearch($instance, $q)
    {
        foreach ($this->searchableFields as $field) {
            $instance->orWhere($field, 'LIKE', "$q%");
        }
    }

    /**
     * Get many records by a field and value
     *
     * @param array $parameters
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function getAllBy(array $parameters, array $relations = [], array $relationsCount = [], $fields = ['*'])
    {
        $instance = $this->getQueryBuilder($fields)
            ->with($relations)->withCount($relationsCount);

        $this->applyParameters($instance, $parameters);

        return $instance->get();
    }

    /**
     * List all records
     *
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     * @throws \Exception
     */
    public function pluck($listFieldName, $listFieldId = null)
    {
        $instance = $this->getQueryBuilder();

        return $instance
            ->pluck($listFieldName, $listFieldId)
            ->all();
    }

    /**
     * List records limited by a certain field
     *
     * @param string $field
     * @param string|array $value
     * @param string $listFieldName
     * @param string $listFieldId
     * @return mixed
     * @throws \Exception
     */
    public function pluckBy($field, $value, $listFieldName, $listFieldId = null)
    {
        $instance = $this->getQueryBuilder();

        if (!is_array($value)) {
            $value = [$value];
        }

        return $instance
            ->whereIn($field, $value)
            ->pluck($listFieldName, $listFieldId)
            ->all();
    }

   /**
     * return table colmuns
     * @param array $except
     * @return array $attributes
     * @throws \Exception
     */
    public function getTableColumns(array $except = [])
    {
        $attributes = collect(DB::select('describe '.$this->getNewInstance()->getTable()))->pluck('Field')->toArray();

        foreach($except as $value){
            unset($attributes[array_search($value, $attributes)]);
        }

        return array_values($attributes) ;
    }

    /**
     * Find a single record
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findById($id, array $relations = [], array $relationsCount = [], $fields = ['*'])
    {
        $this->instance = $this->getQueryBuilder($fields)->with($relations)->withCount($relationsCount)->find($id);

        return $this->instance;
    }

    /**
     * Find a single record by a field and value
     *
     * @param string $field
     * @param string $value
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findBy($field, $value, array $relations = [], array $relationsCount = [], $fields = ['*'])
    {
        $this->instance = $this->getQueryBuilder($fields)->with($relations)->withCount($relationsCount)
            ->where($field, $value)->first();

        return $this->instance;
    }

    /**
     * Find a single record by multiple fields
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findByMany(array $data, array $relations = [], array $relationsCount = [], $fields = ['*'])
    {
        $model = $this->getQueryBuilder($fields)->with($relations)->withCount($relationsCount);

        foreach ($data as $key => $value) {
            $model->where($key, $value);
        }

        $this->instance = $model->first();

        return $this->instance;
    }

    /**
     * Find multiple models
     *
     * @param array $ids
     * @param array $relations
     * @return object
     * @throws \Exception
     */
    public function getAllWhereIn(array $ids, $whereInField = 'id', array $relations = [], array $relationsCount = [], array $parameters = [], $fields = ['*'])
    {
        $instance = $this->getQueryBuilder($fields);

        $this->applyParameters($instance, $parameters);

        return $instance->with($relations)->withCount($relationsCount)
            ->whereIn($whereInField, $ids)->get();;
    }


    /**
     * Insert bulk record
     *
     * @param array $records array of records data
     * @throws \Exception
     */
    public function insert(array $records)
    {
        return $this->getNewInstance()->insert($records);
    }

    /**
     * Create a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->instance = $this->getNewInstance();

        return $this->executeSave($data);
    }

    /**
     * find and update the model instance
     *
     * @param int $id The model id
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateById($id, array $data)
    {
        $this->instance = $this->findById($id);

        return $this->executeSave($data);

    }

    /**
     * Find a single record by multiple fields
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function update(array $parameters, array $data)
    {
        $model = $this->getQueryBuilder();

        foreach ($parameters as $key => $value) {
            $model->where($key, $value);
        }

        $this->instance = $model->update($data);

        return $this->instance;
    }

    /**
     * update model instance
     *
     * @param object $instance model instance
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateByInstance($instance, array $data)
    {
        $this->instance = $instance;

        return $this->executeSave($data);
    }

    /**
     * update model instance
     *
     * @param object $instance model instance
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateByInstanceOrCreate($instance, array $data)
    {
        if (!$instance) {
            $this->instance = $this->getNewInstance();
        } else {
            $this->instance = $instance;
        }

        return $this->executeSave($data);
    }

    /**
     * Save the model
     *
     * @param array $data
     * @return mixed
     */
    protected function executeSave(array $data)
    {
        $this->instance->fill($data);
        $this->instance->save();

        return $this->instance;
    }

    /**
     * Delete a record by id
     *
     * @param int $id Model id
     * @return object model instance
     * @throws \Exception
     */
    public function delete($id)
    {
        $instance = $this->getQueryBuilder();
        $model = $instance->findOrFail($id);
        return $model->delete();
    }

    /**
     * Delete records by ids
     *
     * @param array $ids
     * @throws \Exception
     */
    public function destroyAll($ids)
    {
        $instance = $this->getQueryBuilder();

        return $instance->whereIn("id", $ids)->delete();
    }

    /**
     * Delete records by conditions
     *
     * @param array $parameters
     * @throws \Exception
     */
    public function destroyAllBy(array $parameters, $ids = null)
    {
        $instance = $this->getQueryBuilder();

        $this->applyParameters($instance, $parameters);

        if ($ids) {
            $instance->whereIn("id", $ids);
        }

        return $instance->delete();
    }


    /**
     * Return model name
     *
     * @return string
     * @throws \Exception If model has not been set.
     */
    public function getModelName()
    {
        if (!$this->modelName) {
            throw new \Exception('Model has not been set in ' . get_called_class());
        }

        return $this->modelName;
    }

    /**
     * Return a new query builder instance
     *
     * @return mixed
     * @throws \Exception#
     */
    public function getQueryBuilder($fields = ['*'])
    {
        return $this->getNewInstance()->newQuery()->select($fields);
    }

    /**
     * Returns new model instance
     *
     * @return mixed
     * @throws \Exception
     */
    public function getNewInstance()
    {
        $model = $this->getModelName();

        return new $model;
    }

    /**
     * Set the order by field
     *
     * @param string $orderBy
     * @return void
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * Get the order by field
     *
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set the order direction
     *
     * @param string $orderDirection
     * @return void
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * Get the order direction
     *
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    protected function resolveSearchs($instance)
    {
        if (!$q = Request::query('q')) {
            return;
        }

        $this->applySearch($instance, $q);
    }


    protected function resolveFilters($instance)
    {
        $parameters = [];
        foreach ($this->filterableFields as $field) {
            if ($value = Request::query($field)) {
                $parameters[$field] = $value;
            }
        }
        foreach ($this->searchableFields as $field) {
            if ($value = Request::query($field)) {
                $parameters[$field] = $value;
            }
        }
        if (!$parameters) {
            return;
        }
        $this->applyParameters($instance, $parameters);
    }


    /**
     * Get count of records
     *
     * @param null
     * @return integer
     * @throws \Exception
     */
    public function count($parameters, $ids = null)
    {
        $instance = $this->getQueryBuilder();

        $this->applyParameters($instance, $parameters);

        if ($ids) {
            $instance->whereIn("id", $ids);
        }

        return $instance->count();
    }
}
