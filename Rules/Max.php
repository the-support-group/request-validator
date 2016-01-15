<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Max extends BaseRule implements RulesInterface
{
    private $params;

    private $isNumeric = false;

    public function isValid()
    {
        if (is_numeric($this->params[1])) {

            $this->isNumeric = true;

            return $this->params[1] <= $this->params[2];
        }

        return is_string($this->params[1]) && strlen($this->params[1]) <= $this->params[2];
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        if ($this->isNumeric) {

            $message = 'Field :field: must be less than or equal to :value:.';

        } else {

            $message = 'Field :field: should be maximum of :value: characters.';
        }

        return $message;
    }
}
