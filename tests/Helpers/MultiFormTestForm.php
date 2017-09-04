<?php

namespace SilverStripe\MultiForm\Tests\Helpers;

use SilverStripe\MultiForm\Form;
use SilverStripe\Dev\TestOnly;


class MultiFormTestForm extends Form implements TestOnly
{
    public static $start_step = 'MultiFormTest_StepOne';

    public function getStartStep()
    {
        return self::$start_step;
    }
}
