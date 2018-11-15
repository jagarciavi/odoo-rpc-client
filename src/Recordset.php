<?php

namespace OdooRPCClient;

use \IteratorAggregate;
use \Countable;

class RecordSet implements IteratorAggregate, Countable
{
    private $__resource;
    private $__isSingletone;
    private $__data;

    public function __construct($resource, $data)
    {
        $this->__isSingletone = count($data) == 1;
        $this->__resource = $resource;

        $this->__data = array_map(function($d) {
            return new Record($this->__resource, $d);
        }, $data);

        $this->ids = array_map(function($d) {
            return $d['id'];
        }, $data);

        if($this->__isSingletone) 
        {
            $this->id = $this->ids[0];
        }
    }

    public function count() {
        return count($this->__data);
    }

    public function getIterator()
    {
        return (function () {
            while(list($key, $val) = each($this->__data)) {
                yield $key => $val;
            }
        })();
    }

    public function __set($name, $value)
    {
        if($this->__isSingletone) {
            $this->__data[0]->$name = $value;
        }
    }

    public function __get($name)
    {
        if($this->__isSingletone) {
            return $this->__data[0]->$name;
        }
        
        return null;
    }

    public function __isset($name)
    {
        if($this->__isSingletone) {
            return isset($this->__data[0]->$name);
        }
    }

    public function __unset($name)
    {
        if($this->__isSingletone) {
            unset($this->__data[0]->$name);
        }
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
        return $this->__resource->create($this->ids, $data);
    }

    public function unlink()
	{
        return $this->__resource->unlink($this->ids);
	}
}
