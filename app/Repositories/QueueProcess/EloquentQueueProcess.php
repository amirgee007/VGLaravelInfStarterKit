<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/3/7
 * Time: 18:47
 */

namespace Vanguard\Repositories\QueueProcess;


use Vanguard\Models\QueueProcess;

class EloquentQueueProcess implements QueueProcessRepository
{
    public function __construct()
    {

    }

    /**
     * Create new user.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return QueueProcess::create($data);
    }


    /**
     * find by id
     */
    public function find($id)
    {
        return QueueProcess::find($id);
    }
}