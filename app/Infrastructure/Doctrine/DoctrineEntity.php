<?php

namespace App\Infrastructure\Doctrine;

trait DoctrineEntity
{

    /**
     * Create an instance of the the entity
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $model = new static();

        return $model->fill($attributes);
    }

    /**
     * Fill the entity with attributes
     *
     * @param array $attributes
     * @return static
     */
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
             * @var Object $embed
             */
            $embed = $this->__get($attributeList[$key]['declaredField']);
            if (!isset($embed)) { //create value object and assign attribute
                $embedClass = $attributeList[$key]['mappingClass'];
                $embed = new $embedClass;
                $this->setAttribute($attributeList[$key]['declaredField'], $embed);
            }
            $embed->setAttribute($attributeList[$key]['columnName'], $value);
        }
        if (method_exists($this, 'validate')) $this->validate($attributes);

        return $this;
    }

    /**
     * Retrieve the current attributes from the entity
     *
     * @return array
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
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

    /**
     * Truncate the table for the current entity.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function truncate()
    {
        $connection = \EntityManager::getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL(self::getTable(), false));
    }

    /**
     * Retrieve the name of the storage table for the current entity.
     *
     * @return string
     */
    protected static function getTable()
    {
        /**
         * @var \EntityManager $em
         */
        $em = app()['em'];

        $metaData = $em->getClassMetadata(get_called_class());

        return $metaData->getTableName();
    }

    /**
     * Persist the entity
     *
     * @return static
     */
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

    /**
     * Remove the entity from storage
     */
    public function remove()
    {
        $em = app()['em'];
        $em->remove($this);
        $em->flush();
    }

}