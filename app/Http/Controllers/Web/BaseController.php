<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class BaseController
 * @package Vanguard\Http\Controllers
 */
class BaseController extends Controller
{

    /**
     * success return
     *
     * @param array $data
     * @param string $msg
     * @return string
     */
    public function success($data = [], $msg = '') {
        return json_encode([
           'code' => 1,
            'msg' => $msg,
            'data'=> $data
        ]);
    }

    /**
     * error return
     *
     * @param array $data
     * @param string $msg
     * @return string
     */
    public function error($msg = '', $data = []) {
        return json_encode([
            'code' => 0,
            'msg' => $msg,
            'data'=> $data
        ]);
    }
}
