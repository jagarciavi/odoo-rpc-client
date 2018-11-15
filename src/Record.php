<?php

namespace OdooRPCClient;

class Record 
{
    private $__resource;
    private $__data;

    public function __construct($resource, $data)
    {
        $this->__resource = $resource;
        
        $this->__data = $data;
    }

    public function __set($name, $value)
    {
        $this->__data[$name] = $value;
    }

    public function __get($name)
    {

        if(array_key_exists($name, $this->__data)) {
            return $this->__data[$name];
        }
        
        return null;
    }

    public function __isset($name)
    {
        return isset($this->__data[$name]);
    }

    public function __unset($name)
    {
        unset($this->__data[$name]);
    }

    public function search($criteria, $offset = 0, $limit = 100, $order = '')
    {
        return $this->__resource->search($criteria, $offset, $limit, $order);
    }

	public function search_count($criteria)
    {
        return $this->__resource->search_count($criteria);
    }

	public function browse($ids, $fields = array())
    {
        return $this->__resource->browse($ids, $fields);
    }

	public function search_read($criteria, $fields = array(), $limit=100, $order = '')
    {
        return $this->__resource->search_read($criteria);
    }

    public function create($data)
    {
        return $this->__resource->create($data);
    }

	public function write($data)
    {
        return $this->__resource->create($this->id, $data);
    }

    public function unlink()
	{
        return $this->__resource->unlink($this->id);
	}
}
