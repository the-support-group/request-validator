<?php

namespace TheSupportGroup\Validator\Rules;

class Alpha extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->getParams()[1]);

        $rule = $this->alpha();

        return $this->validate($rule, $value);
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
