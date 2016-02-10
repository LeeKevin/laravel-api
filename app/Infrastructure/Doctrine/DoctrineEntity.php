<?php

namespace App\Infrastructure\Doctrine;

use Illuminate\Support\Str;

class DoctrineEntity
{

    protected static $table;

    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists($this, $key)) continue;

            $this->setAttribute($key, $value);
        }
    }

    public static function truncate()
    {
        $connection = \EntityManager::getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL(self::getTable(), false));
    }

    public static function getTable()
    {
        //if the table is overridden, return that
        if (isset(self::$table)) return self::$table;

        //else get the pluralized table name from the name of the entity
        $entityClassPath = strtolower(get_called_class());
        $entityName = substr(strrchr($entityClassPath, '\\'), 1);
        switch ($entityName[strlen($entityName) - 1]) {
            case 'y':
                return substr($entityName, 0, - 1) . 'ies';
            case 's':
                return $entityName . 'es';
            default:
                return $entityName . 's';
        }
    }

    public static function create(array $attributes = [])
    {
        $model = new static($attributes);

        return $model;
    }

    public function save()
    {
        $em = app()['em'];
        $em->persist($this);
        $em->flush();

        return $this;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
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
}