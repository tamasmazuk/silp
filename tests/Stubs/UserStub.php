<?php

namespace Mazuk\SILP\Test\Stubs;

use Mazuk\SILP\Structure;

class UserStub extends Structure {
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var FileStub */
    public $avatar;
    /** @var FileStub[] */
    public $images;

    protected $structureFields = [
        'avatar' => FileStub::class,
        'images' => [FileStub::class],
    ];
}
