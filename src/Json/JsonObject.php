<?php

namespace JDesrosiers\Json;

use Jsv4;
use SchemaStore;

class JsonObject implements \ArrayAccess
{
    protected $value;
    protected $schemaStore;

    public function __construct($value, $schema)
    {
        if (is_string($schema)) {
            if ($schema[0] !== "{") {
                $schema = file_get_contents($schema);
                if ($schema === false) {
                    throw new \InvalidArgumentException("Could not access schema by URI");
                }
            }

            $schema = json_decode($schema);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException("The schema does not contain valid JSON");
            }
        } elseif (!is_object($schema)) {
            throw new \InvalidArgumentException("The schema must be either JSON, a URI, or an Object");
        }

        try {
            $this->schemaStore = new SchemaStore();
            $this->schemaStore->add("self", $schema);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode, $e);
        }

        $this->setValue($value);
    }

    public function __set($offset, $newval)
    {
        if ($newval instanceof self) {
            $newval = $newval->getValue();
        }

        if (!is_object($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an object");
        }

        $value = unserialize(serialize($this->value));
        $value->$offset = $newval;

        $this->validate($value);
        $this->value->$offset = $newval;
    }

    public function __get($offset)
    {
        if (!is_object($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an object");
        }

        $schema = $this->schemaStore->get("self#/properties/$offset") ?: new \stdClass();

        return new self($this->value->$offset, $schema);
    }

    public function __isset($name)
    {
        if (!is_object($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an object");
        }

        return isset($this->value->$name);
    }

    public function __unset($name)
    {
        if (!is_object($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an object");
        }

        if (!property_exists($this->value, $name)) {
            return;
        }

        $value = unserialize(serialize($this->value));
        unset($value->$name);

        $this->validate($value);
        unset($this->value->$name);
    }

    public function offsetExists($offset)
    {
        if (!is_array($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an array");
        }

        return array_key_exists($offset, $this->value);
    }

    public function offsetGet($offset)
    {
        if (!is_array($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an array");
        }

        $schema = $this->schemaStore->get("self#/items") ?: new \stdClass();

        return new self($this->value[$offset], $schema);
    }

    public function offsetSet($offset, $newval)
    {
        if ($newval instanceof self) {
            $newval = $newval->getValue();
        }

        if (!is_array($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an array");
        }

        if ($offset === null) {
            $offset = count($this->value);
        }

        if (!is_int($offset) || $offset < 0 || $offset > count($this->value)) {
            throw new \InvalidArgumentException("Invalid array index");
        }

        $value = unserialize(serialize($this->value));
        $value[$offset] = $newval;

        $this->validate($value);
        $this->value[$offset] = $newval;
    }

    public function offsetUnset($offset)
    {
        if (!is_array($this->value)) {
            throw new \InvalidArgumentException("Cannot use value as an array");
        }

        if (!array_key_exists($offset, $this->value)) {
            return true;
        }

        if ($offset !== count($this->value) - 1) {
            throw new \InvalidArgumentException("Items can only be removed from the end of the array");
        }

        $value = unserialize(serialize($this->value));
        unset($value[$offset]);

        $this->validate($value);
        unset($this->value[$offset]);
    }

    protected function validate($value)
    {
        $validation = Jsv4::validate($value, $this->schemaStore->get("self"));
        if (!$validation->valid) {
            $message = array();
            foreach ($validation->errors as $error) {
                $message[] = $error->getMessage();
            }
            throw new \InvalidArgumentException(print_r($message, true));
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue(&$newval)
    {
        if ($newval instanceof self) {
            $newval = $newval->getValue();
        }

        $this->validate($newval);
        $this->value = $newval;
    }
}
