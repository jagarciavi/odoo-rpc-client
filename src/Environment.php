<?php

namespace OdooRPCClient;

use \ArrayAccess;

class Environment implements ArrayAccess 
{
    /**
	 * Ripcord Client
	 *
	 * @var Client
	 */
    public $client;

    protected $database;
    protected $userId;
    protected $password;

    public $user;

    public function __construct($client, $database, $userId, $password)
    {
        $this->client = $client;
        $this->database = $database;
        $this->userId = $userId;
        $this->password = $password;

        $Users = new Resource($this, 'res.users', $this->database, $this->userId, $this->password);
        $this->user = $Users->browse($this->userId);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->models[] = $value;
        } else {
            $this->models[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->models[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->models[$offset]);
    }

    public function offsetGet($offset) {
        if(!isset($this->container[$offset])) 
        {
            $this->container[$offset] = new Resource($this, $offset, $this->database, $this->userId, $this->password);
        }
        return $this->container[$offset];
    }

    public function execute($model, $method, $args)
    {
        return call_user_func_array([$this->client, 'execute_kw'],[
            $this->database,
            $this->userId,
            $this->password,
            $model,
            $method,
            $args
        ]);
    }
}