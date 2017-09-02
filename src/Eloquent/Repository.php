<?php
namespace Shorlyn\Repositories\Eloquent;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use Shorlyn\Repositories\Contracts\CriteriaInterface;
use Shorlyn\Repositories\Contracts\RepositoryInterface;
use Shorlyn\Repositories\Criteria\Criteria;
use Shorlyn\Repositories\Exceptions\RepositoryException;

abstract class ClassName implements RepositoryInterface, CriteriaInterface
{
    /**
     * 容器
     *
     * @var $app
     */
    private $app;

    /**
     * 模型
     *
     * @var $model
     */
    protected $model;

    /**
     * [protected description]
     * @var collection
     */
    protected $criteria;

    /**
     * [protected description]
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * 构造函数
     *
     * @param App        $app        容器
     * @param Collection $collection [description]
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->criteria = $collection;
        $this->resetScope();
        $this->makeModel();
    }

    /**
     * 模型
     *
     * @return mixed
     */
    public abstract function model();

    /**
     * 查询所有数据
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }

    /**
     * 分页查询数据
     *
     * @param  integer perPage  分页大小
     * @param  array   $columns 查询的列
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * 插入数据
     *
     * @param  array  $data 要插入的数据
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * 更新数据
     *
     * @param  array  $data      要更新的数据
     * @param  [type] $id        id
     * @param  string $attribute 属性
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * 删除数据
     *
     * @param  [type] $id id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * 查询数据
     *
     * @param  [type] $id      id
     * @param  array  $columns 要返回的列
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->find($id, $columns);
    }

    /**
     * 查询数据
     *
     * @param  [type] $attribute 属性
     * @param  [type] $value     条件
     * @param  array  $columns   要返回的列
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $id)->first($columns);
    }

    /**
     * 创建模型
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException('类 {$this->model()} 必须是 Illuminate\\Database\\Eloquent\\Model 的一个实例');
        }

        return $this->model = $model->newQuery();
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    /**
     * [skipCriteria description]
     * @param  boolean $status [description]
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * [getCriteria description]
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * [getByCriteria description]
     * @param  Criteria $criteria [description]
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * [pushCriteria description]
     * @param  Criteria $criteria [description]
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * [applyCriteria description]
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        foreach ($this->getCriteria as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria-apply($this->model, $this);
            }
        }

        return $this;
    }
}
