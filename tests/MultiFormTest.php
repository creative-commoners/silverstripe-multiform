<?php

namespace SilverStripe\MultiForm\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Dev\TestOnly;

use SilverStripe\Control\Controller;
use SilverStripe\MultiForm\Form;
use SilverStripe\MultiForm\Step;
use SilverStripe\Security\Member;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Core\Config\Config;

/**
 * MultiFormTest
 * For testing purposes, we have some test classes:
 *
 *  - MultiFormTest_Controller (simulation of a real Controller class)
 *  - MultiFormTest_Form (subclass of MultiForm)
 *  - MultiFormTest_StepOne (subclass of MultiFormStep)
 *  - MultiFormTest_StepTwo (subclass of MultiFormStep)
 *  - MultiFormTest_StepThree (subclass of MultiFormStep)
 *
 * The above classes are used to simulate real-world behaviour
 * of the multiform module - for example, MultiFormTest_Controller
 * is a simulation of a page where MultiFormTest_Form is a simple
 * multi-step contact form it belongs to.
 *
 * @package multiform
 * @subpackage tests
 */
class MultiFormTest extends FunctionalTest
{
    public static $fixture_file = 'multiform/tests/MultiFormTest.yml';

    protected $usesDatabase = true;

    protected $controller;

    protected $extraDataObjects = array(
        'MultiFormTest_StepOne',
        'MultiFormTest_StepTwo',
        'MultiFormTest_StepThree'
    );


    public function setUp()
    {
        parent::setUp();

        $this->controller = new MultiFormTest_Controller();
        $this->form = $this->controller->Form();
    }

    public function testInitialisingForm()
    {
        $this->assertTrue(is_numeric($this->form->getCurrentStep()->ID) && ($this->form->getCurrentStep()->ID > 0));
        $this->assertTrue(is_numeric($this->form->getMultiFormSession()->ID) && ($this->form->getMultiFormSession()->ID > 0));
        $this->assertEquals('MultiFormTest_StepOne', $this->form->getStartStep());
    }

    public function testSessionGeneration()
    {
        $this->assertTrue($this->form->session->ID > 0);
    }

    public function testMemberLogging()
    {
        // Grab any user to fake being logged in as, and ensure that after a session is written it has
        // that user as the submitter.
        $userId = Member::get_one("Member")->ID;
        $this->session()->inst_set('loggedInAs', $userId);

        $session = $this->form->session;
        $session->write();

        $this->assertEquals($userId, $session->SubmitterID);
    }

    public function testSecondStep()
    {
        $this->assertEquals('MultiFormTest_StepTwo', $this->form->getCurrentStep()->getNextStep());
    }

    public function testParentForm()
    {
        $currentStep = $this->form->getCurrentStep();
        $this->assertEquals($currentStep->getForm()->class, $this->form->class);
    }

    public function testTotalStepCount()
    {
        $this->assertEquals(3, $this->form->getAllStepsLinear()->Count());
    }

    public function testCompletedSession()
    {
        $this->form->setCurrentSessionHash($this->form->session->Hash);
        $this->assertInstanceOf('MultiFormSession', $this->form->getCurrentSession());
        $this->form->session->markCompleted();
        $this->assertNull($this->form->getCurrentSession());
    }

    public function testIncorrectSessionIdentifier()
    {
        $this->form->setCurrentSessionHash('sdfsdf3432325325sfsdfdf'); // made up!

        // A new session is generated, even though we made up the identifier
        $this->assertInstanceOf('MultiFormSession', $this->form->session);
    }

    public function testCustomGetVar()
    {
        Config::nest();
        Config::inst()->update('MultiForm', 'get_var', 'SuperSessionID');

        $form = $this->controller->Form();
        $this->assertContains('SuperSessionID', $form::$ignored_fields, "GET var wasn't added to ignored fields");
        $this->assertContains('SuperSessionID', $form->FormAction(), "Form action doesn't contain correct session
			ID parameter");
        $this->assertContains('SuperSessionID', $form->getCurrentStep()->Link(), "Form step doesn't contain correct
			session ID parameter");

        Config::unnest();
    }
}

