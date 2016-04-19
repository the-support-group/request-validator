<?php

namespace TheSupportGroup\Common\Validator\Rules;

class Alpha extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->getParams()[1]);

        return $this->alpha()->validate($value);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: may only contain letters';
    }
}
