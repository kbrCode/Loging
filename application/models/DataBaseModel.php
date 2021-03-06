<?php

class Application_Model_DataBaseModel
{
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.get_class($this).' property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.get_class($this).' property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function toArray() {
        $options = array();
        $methods = get_class_methods($this);
        foreach ($methods as $key)
        {
            if (substr($key, 0, 3) == 'get') {
                $value = $this->$key();
                $fieldName = substr($key, 3);
                $options[lcfirst($fieldName)] = $value;
            }
        }
        return $options;
    }
}

