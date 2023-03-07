<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/3/7
 * Time: 18:45
 */

namespace Vanguard\Repositories\QueueProcess;


interface QueueProcessRepository
{
    /**
     * Create new user.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Find process by its id.
     *
     * @param $id
     * @return null|User
     */
    public function find($id);
}