<?php

/**
 * @author: Abdul Qureshi. <abdul@easyfundraising.org.uk>
 * 
 * This file has been modified from the original source.
 * See original here:
 *
 * @link: https://github.com/progsmile/request-validator
 */

namespace TheSupportGroup\Common\Validator\Rules;

use TheSupportGroup\Common\Validator\Contracts\Rules\RuleInterface;

class NotIn extends BaseRule implements RuleInterface
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
