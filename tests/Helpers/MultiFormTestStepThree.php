<?php

namespace SilverStripe\MultiForm\Tests\Helpers;

use SilverStripe\MultiForm\Step;
use SilverStripe\Dev\TestOnly;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class MultiFormTestStepThree extends Step implements TestOnly
{
    public static $is_final_step = true;

    public function getFields()
    {
        return new FieldList(
            new TextField('Test', 'Anything else you\'d like to tell us?')
        );
    }
}
