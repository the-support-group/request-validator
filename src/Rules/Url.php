<?php

namespace TheSupportGroup\Common\Validator\Rules;

class Url extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $input = trim($this->getParams()[1]);

        return $this->Url()->validate($input);
    }

    public function getMessage()
    {
        return 'Field :field: is not URL';
    }
}
