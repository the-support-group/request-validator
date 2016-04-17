<?php

namespace TheSupportGroup\Validator\Rules;

class Equals extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $validationRule = $this->Equals($this->getParams()[1]);

        return $this->validate($validationRule, trim($this->getParams()[2]));
    }

    public function getMessage()
    {
        return 'Field :field: has wrong value';
    }
}
