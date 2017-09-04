<?php

namespace SilverStripe\MultiForm\Tests\Helpers;

use SilverStripe\Control\Controller;
use SilverStripe\Dev\TestOnly;

class MultiFormTestController extends Controller implements TestOnly
{
    public function Link($action = null)
    {
        return 'MultiFormTestController';
    }

    public function Form($request = null)
    {
        $form = new MultiFormTestForm($this, 'Form');
        $form->setHTMLID('MultiFormTestForm');
        return $form;
    }
}
