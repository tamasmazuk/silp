<?php

namespace Mazuk\SILP\Test;

use Mazuk\SILP\Test\Stubs\UserStub;
use PHPUnit\Framework\TestCase;

class StructureTest extends TestCase {

    public function providerConstructStructure() {
        return [
            'ok' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 1, 'name' => 'John Doe'],
            ],
            'set less fields' => [
                ['id' => 1],
                ['id' => 1, 'name' => null],
            ],
        ];
    }

    /**
     * @dataProvider providerConstructStructure
     * @param $data
     * @param $expectations
     */
    public function testConstructStructure(array $data, array $expectations) {
        $user = new UserStub($data);

        foreach ($expectations as $field => $expectedValue) {
            $this->assertEquals($expectedValue, $user->{$field});
        }
    }

    public function providerToArray() {
        return [
            'ok' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 1, 'name' => 'John Doe'],
            ],
            'get unset field value too' => [
                ['id' => 1, 'email' => 'john@unknown.com'],
                ['id' => 1, 'name' => null],
            ],
            'get only the given field' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['name' => 'John Doe'],
                ['name'],
            ],
        ];
    }

    /**
     * @dataProvider providerToArray
     * @param array $data
     * @param array $expected
     * @param array|null $fieldList
     */
    public function testToArray(array $data, array $expected, ?array $fieldList = null) {
        $user = new UserStub($data);
        $result = $user->toArray($fieldList);

        $this->assertEquals($expected, $result);
    }

}
