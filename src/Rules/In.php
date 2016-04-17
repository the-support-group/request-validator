<?php

namespace TheSupportGroup\Validator\Rules;

class In extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $input = trim($this->getParams()[1]);

        $values = array_map(function ($elem) {
            return trim($elem);
        }, explode(',', $this->getParams()[2]));

        $validationObject = $this->In($values);

        return $this->validate($validationObject, $input);
    }

    public function getMessage()
    {
        return 'Field :field: has wrong values';
    }
}
