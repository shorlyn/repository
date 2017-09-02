<?php

namespace Shorlyn\Repositories\Contracts;

use Shorlyn\Repositories\Criteria\Criteria;

/**
 * Interface CriteriaInterface
 */
interface CriteriaInterface
{
    /**
     * [skipCriteria description]
     * @param  boolean $status [description]
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * [getCriteria description]
     * @return mixed
     */
    public function getCriteria();

    /**
     * [getByCriteria description]
     * @param  Criteria $criteria [description]
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * [pushCriteria description]
     * @param  Criteria $criteria [description]
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * [applyCriteria description]
     * @return $this
     */
    public function applyCriteria();
}
