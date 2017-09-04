<?php

namespace SilverStripe\MultiForm\Tests\Helpers;

use SilverStripe\MultiForm\Step;
use SilverStripe\Dev\TestOnly;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;

class MultiFormTestStepTwo extends MultiFormStep implements TestOnly
{
    public static $next_steps = 'MultiFormTest_StepThree';

    public function getFields()
    {
        return new FieldList(
            new TextareaField('Comments', 'Tell us a bit about yourself...')
        );
    }
}
