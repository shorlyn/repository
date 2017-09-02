<?php
namespace Shorlyn\Repositories\Criteria;

use Shorlyn\Repositories\Contracts\RepositoryInterface as Repository;
use Shorlyn\Repositories\Contracts\RepositoryInterface;

/**
 *
 */
abstract class Criteria
{

    public abstract function apply($model, Repository $repository);
}
