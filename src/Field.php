<?php

namespace OdooRPCClient;

class Field
{
    private $__data;

    public function __construct($data)   
    {
        $this->__data = $data;    
    }

    public function isRelation() 
    {
        return isset($this->__data['relation']);
    }

    public function model()
    {   
        if($this->isRelation())
        {
            return $this->__data['relation'];
        }

        return null;
    } 
}