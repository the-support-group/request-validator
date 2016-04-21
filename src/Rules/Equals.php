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

class Equals extends BaseRule implements RuleInterface
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
