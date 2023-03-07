<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Model;

class QueueProcess extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'queue_process';

    protected $fillable = [
        'queue_type','total','affect','status','message'
    ];

}
