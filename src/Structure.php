<?php

namespace Mazuk\SILP;

class Structure {

    /**
     * @param array $fields
     */
    public function __construct(array $fields = []) {
        foreach ($fields as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * @param array|null $fieldList
     * @return array
     */
    public function toArray(array $fieldList = null): array {
        $structureFields = array_keys(get_class_vars(static::class));
        $result = [];

        foreach ($structureFields as $field) {
            if (isset($fieldList) && !in_array($field, $fieldList)) {
                continue;
            }
            $result[$field] = $this->{$field};
        }

        return $result;
    }

}
