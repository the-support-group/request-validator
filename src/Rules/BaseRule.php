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

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

abstract class BaseRule
{
    const CONFIG_ALL = 'all';
    const CONFIG_DATA = 'data';
    const CONFIG_FIELD_RULES = 'fieldRules';

    /**
     * @var array $config
     */
    private $config;

    /**
     * @var array $params
     */
    private $params;

    /**
     * @var ValidationProviderInterface $validationProvider
     */
    private $validationProvider;

    /**
     * BaseRule constructor.
     *
     * @param $config
     */
    public function __construct(
        $config,
        ValidationProviderInterface $validationProvider
    ) {
        $this->config = $config;
        $this->validationProvider = $validationProvider;
    }

    /**
     * Validator calls captured and remapped here.
     *
     * @return BaseRule Validator rule object.
     */
    public function __call($method, array $params = array())
    {
        // Get the mapping out of the mapper file.
        $validatorMethod = $this->validationProvider->getMappedMethod($method);

        // Try running rule against params.
        $this->buildRule($validatorMethod, $params);

        // Return self for next call to be made via the base Rule.
        return $this;
    }

    /**
     * Run rule against validator.
     */
    private function buildRule($ruleName, $arguments = [])
    {
        return $this->validationProvider->rule($ruleName, $arguments);
    }

    /**
     * Validate rule against value provided.
     * We can control which method is called on the external library.
     */
    public function validate($value)
    {
        return $this->validationProvider->validate($value);
    }

    /**
     * Returns all config array, or specific one.
     *
     * @param string $type
     *
     * @return string
     */
    public function getConfig($type = self::CONFIG_ALL)
    {
        if ($type == self::CONFIG_ALL) {
            return $this->config;
        }

        return isset($this->config[$type]) ? $this->config[$type] : [];
    }

    /**
     * If field has specific rule.
     *
     * @param string $rule
     *
     * @return bool
     */
    public function hasRule($rule)
    {
        return strpos($this->getConfig(self::CONFIG_FIELD_RULES), $rule) !== false;
    }

    /**
     * Check if variable is not required - to prevent error messages from another validators.
     *
     * @param string $type | 'var' or 'file'
     *
     * @return bool
     */
    protected function isNotRequiredAndEmpty($type = 'var')
    {
        $condition = false;

        if ($type == 'var') {
            $condition = strlen($this->params[1]) == 0;
        } elseif ($type == 'file') {
            $fieldsName = $this->params[0];

            //when file field is not required and empty
            $condition = isset($_FILES[$fieldsName]['name']) && $_FILES[$fieldsName]['name'] == '';
        }

        return !$this->hasRule('required') && $condition;
    }

    /**
     * Set params to validator.
     *
     * @param $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Returns params.
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns pure class name.
     *
     * @return string
     */
    public function getRuleName()
    {
        $classPath = explode('\\', get_class($this));

        return array_pop($classPath);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    abstract public function getMessage();

    /**
     * The main function that validates the inputted value against
     * an existing one or similar.
     *
     * @return bool Return a if values were valid/matched or not
     */
    abstract public function isValid();
}
