<?php

class Config
{

    protected $parameter;

    public function __construct()
    {
        global $parameters;
        require_once __DIR__ . '/config/Parameters.php';
        $this->parameter = $parameters;
    }

    public function getParametersConnect()
    {
        return $this->parameter['connect'];
    }

}

?>