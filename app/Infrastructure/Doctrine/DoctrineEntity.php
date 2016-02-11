<?php

namespace App\Infrastructure\Doctrine;

use App\Domain\ValueObjects\ValueObject;
use Illuminate\Support\Str;

abstract class DoctrineEntity
{

    protected static $table;


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
        $model = new static();

        return $model->fill($attributes);
    }

    public function fill(array $attributes = [])
    {
        $attributeList = $this->getAttributes();
        foreach ($attributes as $key => $value) {
            if (!array_key_exists($key, $attributeList)) continue;

            if (!isset($attributeList[$key]['mappingClass'])) {
                $this->setAttribute($key, $value);
                continue;
            }

            /**
             * @var ValueObject $embed
             */
            $embed = $this->__get($attributeList[$key]['declaredField']);
            if (!isset($embed)) { //create value object and assign attribute
                $embedClass = $attributeList[$key]['mappingClass'];
                $embed = new $embedClass;
                $this->setAttribute($attributeList[$key]['declaredField'], $embed);
            }
            $embed->setAttribute($attributeList[$key]['columnName'], $value);
        }
        $this->validate($attributes);

        return $this;
    }

    private function getAttributes()
    {
        /**
         * @var \EntityManager $em
         */
        $em = app()['em'];
        $attributes = [];

        $metaData = $em->getClassMetadata(get_called_class());
        foreach ($metaData->getFieldNames() as $fieldName) {
            if (strpos($fieldName, '.') === false) {
                //does not contain a '.' => not reference to mapping
                $attributes[$fieldName] = [
                    'fieldName'    => $fieldName,
                    'columnName'   => $fieldName,
                    'mappingClass' => null
                ];
                continue;
            }

            //contains a '.' => references embed mapping
            $fieldMapping = $metaData->getFieldMapping($fieldName);
            $attributes[$fieldMapping['columnName']] = [
                'fieldName'     => $fieldName,
                'columnName'    => $fieldMapping['columnName'],
                'mappingClass'  => $fieldMapping['originalClass'],
                'declaredField' => $fieldMapping['declaredField']
            ];
        }

        return $attributes;
    }

    public function save()
    {
        $em = app()['em'];
        $em->persist($this);
        $em->flush();

        return $this;
    }

    /**
     * Alias for remove
     */
    public function delete()
    {
        $this->remove();
    }

    public function remove()
    {
        $em = app()['em'];
        $em->remove($this);
        $em->flush();
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

    protected abstract function rules();

    /**
     * @var \Illuminate\Support\MessageBag
     */
    private $errors;
    private $valid;


    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param $data
     * @return bool
     */
    public function validate($data)
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

    public function valid()
    {
        return $this->valid;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}