<?php

namespace OdooRPCClient;

class Resource
{
    /**
	 * Current model
	 *
	 * @var string
	 */
	protected $model;
	/**
	 * Current database
	 *
	 * @var string
	 */
    protected $database;
    /**
	 * Current user
	 *
	 * @var string
	 */
	protected $userId;
	/**
	 * Password for current user
	 *
	 * @var string
	 */
	protected $password;
	/**
	 * Ripcord Client
	 *
	 * @var Client
	 */
	protected $client;
    
    /**
	 * Odoo constructor
	 *
	 * @param string     $host       The url
	 * @param string     $database   The database to log into
	 * @param string     $user       The username
	 * @param string     $password   Password of the user
	 */
    public function __construct($client, $model, $database, $userId, $password)
	{
        $this->client = $client;
        $this->model = $model;
		$this->database = $database;
		$this->userId = $userId;
		$this->password = $password;
	}
	
    public function __call($method, $args)
	{	
		if(count($args) < 1) 
		{
			throw new Exception("Invalid number of arguments for custom method");
		}
		
		return  $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            $method,
            $args
        );
	}
    
    /**
	 * Search models
	 *
	 * @param string  $model    Model
	 * @param array   $criteria Array of criteria
	 * @param integer $offset   Offset
	 * @param integer $limit    Max results
	 *
	 * @return array Array of model id's
	 */
	public function search($criteria = [], $offset = 0, $limit = 100, $order = '')
	{
		$response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'search',
            [$criteria],
            ['offset'=>$offset, 'limit'=>$limit, 'order' => $order]
        );
		return $response;
	}
	/**
	 * Search_count models
	 *
	 * @param string  $model    Model
	 * @param array   $criteria Array of criteria
	 *
	 * @return array Array of model id's
	 */
	public function search_count($criteria = [])
	{
		$response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'search_count',
            [$criteria]
        );
		return $response;
	}
	/**
	 * Read model(s)
	 *
	 * @param string $model  Model
	 * @param array  $ids    Array of model id's
	 * @param array  $fields Index array of fields to fetch, an empty array fetches all fields
	 *
	 * @return array An array of models
	 */
	public function browse($ids, $fields = array())
	{
        $response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'read',
            [$ids],
            ['fields'=>$fields]
        );
		return new Recordset($this, $response);
	}
	/**
	 * Search and Read model(s)
	 *
	 * @param string $model     Model
     * @param array  $criteria  Array of criteria
	 * @param array  $fields    Index array of fields to fetch, an empty array fetches all fields
     * @param integer $limit    Max results
	 *
	 * @return array An array of models
	 */
	public function search_read($criteria = [], $fields = array(), $limit=100, $order = '')
	{
        $response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'search_read',
            [$criteria],
            ['fields'=>$fields,'limit'=>$limit, 'order' => $order]
        );
		return $response;
	}
    /**
   	 * Create model
   	 *
   	 * @param string $model Model
   	 * @param array  $data  Array of fields with data (format: ['field' => 'value'])
   	 *
   	 * @return integer Created model id
   	 */
   	public function create($data)
   	{
        $response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'create',
            [$data]
        );
//        print_r($response);
   		return $response;
   	}
	/**
	 * Update model(s)
	 *
	 * @param string $model  Model
	 * @param array  $ids     Model ids to update
	 * @param array  $fields A associative array (format: ['field' => 'value'])
	 *
	 * @return array
	 */
	public function write($ids, $fields)
	{
        $response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'write',
            [
                 $ids,
                $fields
            ]
        );
		return new Recordset($this, $response);
	}
	/**
	 * Unlink model(s)
	 *
	 * @param string $model Model
	 * @param array  $ids   Array of model id's
	 *
	 * @return boolean True is successful
	 */
	public function unlink($ids)
	{
        $response = $this->client->execute_kw(
            $this->database,
            $this->userId,
            $this->password,
            $this->model,
            'unlink',
            [$ids]
        );
		return $response;
	}

}