<?php

namespace App\Contracts;

abstract class Model
{
    protected $table;

    protected $fillable = [];

    protected $attributes;

    /**
     * Insert given attributes to database
     *
     * @param array $attributes
     * @return Model $static
     */
    public static function create(array $attributes)
    {
        $static = new static;
        $allowedAttributes = array_intersect_key($attributes, array_flip($static->fillable));

        $static->attributes = app()->db->insert($static->table, $allowedAttributes);

        return $static;
    }

    /**
     * Get attribute like accessing a property
     *
     * @param string $name
     * @return mixed 
     */
    public function __get($name)
    {
        /**
         * Get the property instead if the property exist
         */
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        if (! array_key_exists($name, $this->attributes)) {
            return null;
        }

        return $this->attributes[$name];
    }
}
