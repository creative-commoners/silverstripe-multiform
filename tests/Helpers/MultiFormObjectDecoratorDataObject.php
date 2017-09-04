<?php

namespace SilverStripe\MultiForm\Tests\Helpers;

use SilverStripe\ORM\DataObject;
use SilverStripe\Dev\TestOnly;

class MultiFormObjectDecoratorDataObject extends DataObject implements TestOnly
{
    private static $db = array(
        'Name' => 'Varchar'
    );
}
