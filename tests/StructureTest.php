<?php

namespace Mazuk\SILP\Test;

use Mazuk\SILP\Test\Stubs\FileStub;
use Mazuk\SILP\Test\Stubs\UserStub;
use PHPUnit\Framework\TestCase;

class StructureTest extends TestCase {

    public function providerConstructStructure() {
        return [
            'only some fields' => [
                ['id' => 1, 'name' => 'John Doe', 'avatar' => null, 'images' => null],
                ['id' => 1, 'name' => 'John Doe', 'avatar' => null, 'images' => null],
            ],
            'structure field' => [
                ['id' => 1, 'name' => 'John Doe',
                    'avatar' => [
                        'name' => 'avatar',
                        'url' => 'https://picsum.photos/200/300',
                        'size' => 3466,
                    ],
                ],
                ['id' => 1, 'name' => 'John Doe',
                    'avatar' => new FileStub([
                        'name' => 'avatar',
                        'url' => 'https://picsum.photos/200/300',
                        'size' => 3466,
                    ]),
                ],
            ],
            'structure list' => [
                ['id' => 1, 'name' => 'John Doe',
                    'images' => [
                        [
                            'name' => 'img1',
                            'url' => 'https://picsum.photos/20/30',
                            'size' => 28,
                        ], [
                            'name' => 'img2',
                            'url' => 'https://picsum.photos/25/35',
                            'size' => 142,
                        ],
                    ],
                ],
                ['id' => 1, 'name' => 'John Doe',
                    'images' => [
                        new FileStub([
                            'name' => 'img1',
                            'url' => 'https://picsum.photos/20/30',
                            'size' => 28,
                        ]),
                        new FileStub([
                            'name' => 'img2',
                            'url' => 'https://picsum.photos/25/35',
                            'size' => 142,
                        ]),
                    ],
                ],
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
            'get all field values' => [
                ['id' => 1, 'email' => 'john@unknown.com'],
                null,
                ['id' => 1, 'name' => null, 'avatar' => null, 'images' => null],
            ],
            'get only the given field values' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['name'],
                ['name' => 'John Doe'],
            ],
            'get structure field values' => [
                ['id' => 1,
                    'avatar' => [
                        'name' => 'avatar',
                        'url' => 'https://picsum.photos/200/300',
                        'size' => 3466,
                    ],
                ],
                ['id', 'avatar'],
            ],
            'get structure list field values' => [
                ['id' => 1,
                    'images' => [
                        [
                            'name' => 'img1',
                            'url' => 'https://picsum.photos/20/30',
                            'size' => 28,
                        ], [
                            'name' => 'img2',
                            'url' => 'https://picsum.photos/25/35',
                            'size' => 142,
                        ],
                    ],
                ],
                ['id', 'images'],
            ],
        ];
    }

    /**
     * @dataProvider providerToArray
     * @param array $data
     * @param array $expected
     * @param array|null $fieldList
     */
    public function testToArray(array $data, array $fieldList = null, array $expected = null) {
        $user = new UserStub($data);
        $result = $user->toArray($fieldList);

        $this->assertEquals($expected ?? $data, $result);
    }

}
