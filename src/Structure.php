<?php

namespace Mazuk\SILP;

class Structure {

    /** @var array */
    protected $structureFields = [];

    public function __construct(iterable $data = []) {
        foreach ($data as $field => $value) {
            if (!property_exists($this, $field)) {
                continue;
            }
            if ($this->isSimpleStructureField($field)) {
                if (is_iterable($value)) {
                    $this->{$field} = new $this->structureFields[$field]($value);
                }
            } elseif ($this->isStructureListField($field)) {
                if (is_iterable($value)) {
                    $this->{$field} = array_map(function (iterable $item) use ($field) {
                        return new $this->structureFields[$field][0]($item);
                    }, $value);
                }
            } else {
                $this->{$field} = $value;
            }
        }
    }

    public function toArray(array $fieldList = null): array {
        $fieldList = $fieldList ?? $this->getFieldList();
        $result = [];

        foreach ($fieldList as $field) {
            if (!property_exists($this, $field)) {
                continue;
            }
            if ($this->isSimpleStructureField($field) && $this->isStructureInstance($field)) {
                $result[$field] = $this->{$field}->toArray();
            } elseif ($this->isStructureListField($field) && is_iterable($this->{$field})) {
                $result[$field] = array_map(function (Structure $structure) {
                    return $structure->toArray();
                }, $this->{$field});
            } else {
                $result[$field] = $this->{$field};
            }
        }

        return $result;
    }

    protected function getFieldList(): array {
        $reflectionObject = new \ReflectionObject($this);

        $reflectionProperties = $reflectionObject->getProperties(\ReflectionProperty::IS_PUBLIC);

        return array_map(function (\ReflectionProperty $properties) {
            return $properties->name;
        }, $reflectionProperties);
    }

    protected function isSimpleStructureField(string $field): bool {
        return $this->isStructureField($field) && is_string($this->structureFields[$field]);
    }

    protected function isStructureListField(string $field): bool {
        return $this->isStructureField($field) && is_array($this->structureFields[$field]);
    }

    protected function isStructureField(string $field): bool {
        return array_key_exists($field, $this->structureFields);
    }

    protected function isStructureInstance(string $field): bool {
        return $this->{$field} instanceof Structure;
    }

}
