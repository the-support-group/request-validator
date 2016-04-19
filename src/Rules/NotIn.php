<?php

namespace TheSupportGroup\Common\Validator\Rules;

class NotIn extends BaseRule
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

        $in = $this->In([$values]);

        return $this->Not([$in])->validate($input);
    }

    public function getMessage()
    {
        return 'Field :field: has wrong values';
    }
}
