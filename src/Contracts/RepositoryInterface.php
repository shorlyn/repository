<?php
namespace Shorlyn\Repositories\Contracts;

/**
 *
 */
interface InterfaceName
{
    public function all($columns = ['*']);

    public function paginate($perPage = 15, $columns = ['*']);

    public function store(array $data);

    public function update(array $data, $id);

    public function find($id, $columns = ['*']);

    public function findBy($field, $value, $columns = ['*']);

    public function delete($id);
}
