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

class Max extends BaseRule implements RuleInterface
{
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $input = trim($this->getParams()[1]);
        $value = trim($this->getParams()[2]);

        if ($this->hasRule('numeric') !== false && is_numeric($input)) {
            $this->isNumeric = true;

            return $this->Max($value, true)->validate($input);
        }

        // there is no way respect/validator supports string for rule 'Max'
        return is_string($input) && strlen($input) <= $value;
    }

    public function getMessage()
    {
        if ($this->isNumeric) {
            return 'Field :field: must be less than or equal to :param:';
        }

        return 'Field :field: should be maximum of :param: characters';
    }
}
