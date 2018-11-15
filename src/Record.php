<?php

namespace OdooRPCClient;

class Record 
{
    /**
     * Current Odoo Resource
	 *
	 * @var Resource
	 */
    private $__resource;

    /**
     * Current record data
	 *
	 * @var Array
	 */
    private $__data;

    /**
	 * Record constructor
	 *
	 * @param string     $resoource       The resource related to this record
	 * @param string     $data   The record data
	 */
    public function __construct($resource, $data)
    {
        $this->__resource = $resource;
        
        $this->__data = $data;
    }
    
    /**
	 * Magic setter
	 *
	 * @param string     $name    Name of the attribute
	 * @param string     $value   New value to assign
	 */
    public function __set($name, $value)
    {
        $this->__data[$name] = $value;
    }
    
    /**
	 * Magic getter
	 *
	 * @param string     $name    Name of the attribute
	 */
    public function __get($name)
    {
        if(array_key_exists($name, $this->__data)) {
            return $this->__data[$name];
        }
        
        return null;
    }

    /**
	 * Magic Caller
	 *
     * Call custom odoo method on this record
     *  
	 * @param string     $method    Name of the remote method
	 * @param string     $args   List of arguments
	 */
    public function __call($method, $args)
    {
        array_unshift($args, [$this->id]);
        return call_user_func_array([$this->__resource, $method], $args);
    }

    /**
	 * Magic Check attribute
	 *
	 * @param string     $name    Name of the attribute
	 */
    public function __isset($name)
    {
        return isset($this->__data[$name]);
    }
    
    /**
	 * Magic Delete attribute
	 *
	 * @param string     $name    Name of the attribute
	 */
    public function __unset($name)
    {
        unset($this->__data[$name]);
    }

    /**
	 * Write record remotely
	 *
	 */
    public function write()
    {
        return $this->__resource->create($this->id, $this->__data);
    }
    
    /**
	 * Delete record remotely
	 *
	 */
    public function unlink()
	{
        return $this->__resource->unlink($this->id);
	}
}
