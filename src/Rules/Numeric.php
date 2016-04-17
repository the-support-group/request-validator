<?php

namespace TheSupportGroup\Validator\Rules;

class Numeric extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $rule = $this->Numeric();

        return $this->validate($rule, trim($this->getParams()[1]));
    }

    public function getMessage()
    {
        return 'Field :field: is not a number';
    }
}
