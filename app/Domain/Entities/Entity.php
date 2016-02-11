<?php
namespace App\Domain\Entities;


interface Entity {

    /**
     * Create an instance of the the entity
     *
     * @param array $attributes
     * @return mixed
     */
    public static function create(array $attributes = []);

    /**
     * Truncate the table for the current entity.
     */
    public static function truncate();

    /**
     * Fill the entity with attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes = []);

    /**
     * Persist the entity
     *
     * @return self
     */
    public function save();

    /**
     * Alias for remove
     */
    public function delete();
    /**
     * Remove the entity from storage
     */
    public function remove();
}