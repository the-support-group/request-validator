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

class Required extends BaseRule implements RuleInterface
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->getParams()[1]);

        return (bool) $value || $value == '0'
            || !empty($_FILES) && isset($_FILES[$this->getParams()[0]]) && $_FILES[$this->getParams()[0]]['name'];
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
