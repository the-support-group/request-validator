<?php

namespace TheSupportGroup\Common\Validator\Rules;

use Exception;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

abstract class BaseRule
{
    const CONFIG_ALL = 'all';
    const CONFIG_DATA = 'data';
    const CONFIG_FIELD_RULES = 'fieldRules';

    private $config;
    private $params;
    private $validator;
    private static $validatorMapping = [];

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

        // Import mapping only once.
        if (! self::$validatorMapping) {
            self::$validatorMapping = require __DIR__ . '/../Mapping/Mapping.php';
        }
    }

    /**
     * Validator calls captured and remapped here.
     *
     * @return object Validator rule object.
     */
    public function __call($method, array $params = array())
    {
        // Get the mapping out of the mapper file.
        $validatorMethod = $this->getMappedMethod($method);

        // Try running rule against params.
        $ValidationResultProcessor = $this->buildRule($validatorMethod, $params);

        // Return resulting method.
        return $ValidationResultProcessor;
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
     * Get mapped method.
     */
    private function getMappedMethod($method)
    {
        // Check if the method called is provided in the mapping.
        if (! array_key_exists($method, self::$validatorMapping)) {
            throw new Exception(sprintf(
                'Mapping for method "%s" not found, make sure it exists in the mapping file.',
                $method
            ));
        }

        return self::$validatorMapping[$method];
    }

    /**
     * Returns all config array, or specific one.
     *
     * @param string $type
     *
     * @return array
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
     * @param $rule
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
