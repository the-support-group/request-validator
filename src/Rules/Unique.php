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

class Unique extends BaseRule implements RuleInterface
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $config = $this->getConfig();

        $field = $this->getParams()[0];
        $value = $this->getParams()[1];
        $table = $this->getParams()[2];

        $instance = new $config[BaseRule::CONFIG_ORM]($field, $value, $table);

        return $instance->isUnique();
    }

    public function getMessage()
    {
        return 'Field :field: must be unique';
    }
}
