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
     * Fetch a row in database
     *
     * @param string $column
     * @param mixed $value
     * @return Model|null $static
     */
    public static function findBy(string $column, $value)
    {
        $static = new static;
        $static->attributes = app()->db->findBy($static->table, $column, $value);

        // Return null if the record not found
        return is_null($static->attributes) ? null : $static;
    }

    /**
     * Update by given attributes
     *
     * @param array $attributes
     * @return Model $this
     */
    public function update(array $attributes)
    {
        $this->attributes = app()->db->update($this->table, $attributes, 'id', $this->id);

        return $this;
    }

    /**
     * Get attribute like accessing a property
     *
     * @param string $name
     * @return mixed 
     */
    public function __get(string $name)
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
