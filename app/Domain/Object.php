<?php
namespace App\Domain;

use Illuminate\Support\Str;

abstract class Object
{

    /**
     * @var \Illuminate\Support\MessageBag
     */
    private $errors;

    /**
     * @var bool
     */
    private $valid;

    /**
     * Magic method for accessing properties
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->getAttribute($property);
        }
    }

    /**
     * Magic method for mutating properties
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->setAttribute($property, $value);
        }
    }

    /**
     * Get a given attribute on the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!property_exists($this, $key)) return null;
        // First we will check for the presence of an accessor for the get operation
        // which simply lets the developers tweak the attribute as it is pulled from
        // the model.
        if ($this->hasGetAccessor($key)) {
            $method = 'get' . Str::studly($key) . 'Attribute';

            return $this->{$method}($this->$key);
        }

        return $this->$key;
    }


    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed $value
     * @return static
     */
    public function setAttribute($key, $value)
    {
        if (!property_exists($this, $key)) return $this;

        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            $method = 'set' . Str::studly($key) . 'Attribute';

            return $this->{$method}($value);
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
    }

    /**
     * Determine if a get accessor exists for an attribute.
     *
     * @param  string $key
     * @return bool
     */
    public function hasGetAccessor($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }


    /**
     * Apply validation rules to set attributes
     *
     * @param array $data
     * @return bool
     */
    public function validate(array $data)
    {
        // make a new validator object
        $v = \Validator::make($data, $this->rules());
        // return the result
        if ($v->fails()) {
            $this->errors = $v->errors();

            return $this->valid = false;
        }

        return $this->valid = true;
    }

    /**
     * Rules for validation
     *
     * @return array
     */
    protected abstract function rules();

    /**
     * Retrieve errors after validation
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Retrieve valid state
     *
     * @return bool
     */
    public function valid()
    {
        return (bool) $this->valid;
    }

}