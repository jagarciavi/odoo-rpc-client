<?php

namespace OdooRPCClient;

use Ripcord\Ripcord;
use \ArrayAccess;

class Client implements ArrayAccess 
{
	/**
     * Host to connect to
	 *
	 * @var string
	 */
	protected $host;
	/**
	 * Unique identifier for current user
	 *
	 * @var integer
	 */
	protected $uid;
	/**
	 * Current users username
	 *
	 * @var string
	 */
	protected $user;
	/**
	 * Current database
	 *
	 * @var string
	 */
	protected $database;
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
	 * XmlRpc endpoint
	 *
	 * @var string
	 */
	protected $path;
	/**
	 * Odoo constructor
	 *
	 * @param string     $host       The url
	 * @param string     $database   The database to log into
	 * @param string     $user       The username
	 * @param string     $password   Password of the user
	 */
	protected $models;

	public function __construct($host, $database, $user, $password)
	{
		$this->host = $host . "/xmlrpc/2";
		$this->database = $database;
		$this->user = $user;
        $this->password = $password;
        $this->models = [];
	}
	/**
	 * Get version
	 *
	 * @return array Odoo version
	 */
	public function version()
	{
		$response = $this->getClient('common')->version();
		return $response;
    }

    /**
	 * Get version
	 *
	 * @return array Odoo version
	 */
    public function login($database, $login, $password)
    {

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
            $this->container[$offset] = new Resource($this->getClient('object'), $offset, $this->database, $this->uid(), $this->password);
        }
        return $this->container[$offset];

    }

    /**
	 * Get XmlRpc Client
	 *
	 * This method returns an XmlRpc Client for the requested endpoint.
	 * If no endpoint is specified or if a client for the requested endpoint is
	 * already initialized, the last used client will be returned.
	 *
	 * @param null|string $path The api endpoint
	 *
	 * @return Client
	 */
	protected function getClient($path = null)
	{
		if ($path === null) {
			return $this->client;
		}
		if ($this->path === $path) {
			return $this->client;
		}
		$this->path = $path;
		$this->client = Ripcord::client($this->host . '/' . $path);
        return $this->client;
	}
    /**
	 * Get uid
	 *
	 * @return int $uid
	 */
	protected function uid()
	{
		if ($this->uid === null) {
			$client = $this->getClient('common');
			$this->uid = $client->authenticate(
				$this->database,
				$this->user,
				$this->password,
                array()
			);
        }
		return $this->uid;
	}
}