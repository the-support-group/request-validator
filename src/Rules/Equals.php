<?php

namespace TheSupportGroup\Common\Validator\Rules;

class Equals extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return $this->Equals($this->getParams()[1])->validate(trim($this->getParams()[2]));
    }

    public function getMessage()
    {
        return 'Field :field: has wrong value';
    }
}
