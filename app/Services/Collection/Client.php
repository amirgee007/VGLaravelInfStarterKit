<?php

namespace Vanguard\Services\Collection;


class Client
{
    use \Vanguard\Services\Collection\Traits\Curl;

    public $connectTimeout;

    public $readTimeout;

    protected static $instance = null;

    // get instance
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // request
    public function execute(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            $result = $this->curl_get($request->url, $request->udfParams, $request->headerParams);
        } else {
            $result = $this->curl_post($request->url, $request->udfParams, $request->headerParams);
        }

        if (empty($result)) {
            throw new \Exception("fetch error: Please replace the request header manually！");
        }
        $parseData = call_user_func_array([$request->parser, 'parse'], [$result]);
        $request->setPayload($parseData);
        return $request;

    }
}
