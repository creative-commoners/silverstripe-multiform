<?php

namespace SilverStripe\MultiForm;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Security;

/**
 * Serializes one or more {@link MultiFormStep}s into a database object.
 *
 * MultiFormSession also stores the current step, so tha the {@link MultiForm} and {@link MultiFormStep} classes know
 * what the current step is.
 *
 * @package multiform
 */
class Session extends DataObject
{
    private static $db = array(
        'Hash' => 'Varchar(40)',
        'IsComplete' => 'Boolean'
    );

    private static $has_one = array(
        'Submitter' => 'SilverStripe\Security\Member',
        'CurrentStep' => 'SilverStripe\MultiForm\Step'
    );

    private static $has_many = array(
        'FormSteps' => 'SilverStripe\MultiForm\Step'
    );

    private static $table_name = 'MultiFormSession';

    /**
     * Mark this session as completed.
     *
     * This sets the flag "IsComplete" to true and writes the session back.
     */
    public function markCompleted()
    {
        $this->IsComplete = 1;
        $this->write();

        return $this;
    }

    /**
     * These actions are performed when write() is called on this object.
     */
    public function onBeforeWrite()
    {
        $currentMember = Security::getCurrentUser();

        if (!$this->SubmitterID && $currentMember) {
            $this->SubmitterID = $currentMember->ID;
        }

        parent::onBeforeWrite();
    }

    /**
     * These actions are performed when delete() is called on this object.
     */
    public function onBeforeDelete()
    {
        $steps = $this->FormSteps();

        if ($steps) {
            foreach ($steps as $step) {
                if ($step && $step->exists()) {
                    $steps->remove($step);
                    $step->delete();
                    $step->destroy();
                }
            }
        }

        parent::onBeforeDelete();
    }
}
