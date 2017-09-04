<?php

namespace SilverStripe\MultiForm\Tests\Helpers;

use SilverStripe\MultiForm\Step;
use SilverStripe\Dev\TestOnly;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextField;

class MultiFormTestStepOne extends MultiFormStep implements TestOnly
{
    public static $next_steps = 'MultiFormTest_StepTwo';

    public function getFields()
    {
        return new FieldList(
            new TextField('FirstName', 'First name'),
            new TextField('Surname', 'Surname'),
            new EmailField('Email', 'Email address')
        );
    }
}
