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

class DateFormat extends BaseRule implements RuleInterface
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $time = $this->getParams()[1];
        $format = trim($this->getParams()[2], '()');

        return $this->Date($format)->validate($time);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: has bad date format';
    }
}
