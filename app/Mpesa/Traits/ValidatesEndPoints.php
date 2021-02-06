<?php

namespace App\Mpesa;

class ValidatesEndpoints
{
    protected $default_endpoint = [];
    protected $endpoint = null;

    public function ValidatesEndpoints(string $env)
    {
        if (!$this->endpoint) {
            if (!array_key_exists($env, $this->default_endpoint)) {
                throw new \ErrorException('Endpoint Missing');
            }
            $this->endpoint = $this->default_endpoint[$env];
        }
    }
}
