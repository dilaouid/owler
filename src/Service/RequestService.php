<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class RequestService
{

    /**
     * @var ParameterBag
     */
    private $request;

    public function __construct(ParameterBag $request)
    {
        $this->request = $request;
    }

    public function check_key($key, $default = null)
    {
        if ($this->request->has($key)) {
            $value = $this->request->get($key);
            return $value ?? $default;
        }
        return $default;
    }

    public function all()
    {
        return $this->request->all();
    }

}