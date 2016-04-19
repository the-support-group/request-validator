<?php

namespace TheSupportGroup\Common\Validator\Rules;

class Email extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return $this->Email()->validate($this->getParams()[1]);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: has a bad email format';
    }
}
