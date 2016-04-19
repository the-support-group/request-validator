<?php

namespace TheSupportGroup\Common\Validator\Rules;

class Boolean extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->getParams()[1]);

        return $this->BoolVal()->validate($value);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: is not a boolean';
    }
}
