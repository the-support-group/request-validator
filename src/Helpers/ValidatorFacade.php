<?php

/**
 * @author: Abdul Qureshi. <abdul@easyfundraising.org.uk>
 * 
 * This file has been modified from the original source.
 * See original here:
 *
 * @link: https://github.com/progsmile/request-validator
 */

namespace TheSupportGroup\Common\Validator\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Rules\BaseRule;
use TheSupportGroup\Common\Validator\Validator;
use TheSupportGroup\Common\Validator\Contracts\Helpers\ValidationResultProcessorInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers\ValidationFacadeInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers\RulesFactoryInterface;

class ValidatorFacade implements ValidationFacadeInterface
{
    /**
     * @var ValidationProviderInterface $validationProvider
     */
    private $validationProvider;

    /**
     * @var ValidationResultProcessorInterface $validationResultProcessor
     */
    private $validationResultProcessor;

    /**
     * @var RulesFactoryInterface $rulesFactory
     */
    private $rulesFactory;

    /**
     * @param ValidationProviderInterface $validationProvider
     * @param ValidationResultProcessorInterface $validationResultProcessor
     * @param RulesFactoryInterface $rulesFactory
     */
    public function __construct(
        ValidationProviderInterface $validationProvider,
        ValidationResultProcessorInterface $validationResultProcessor,
        RulesFactoryInterface $rulesFactory
    ) {
        $this->validationProvider = $validationProvider;
        $this->validationResultProcessor = $validationResultProcessor;
        $this->rulesFactory = $rulesFactory;
    }

    /**
     * @param array $inputData
     * @param array $rules
     * @param array $errorMessages
     * 
     * @return ValidationResultProcessor
     */
    public function validate(
        array $inputData,
        array $rules = [],
        array $errorMessages = []
    ) {
        return (new Validator(
            $this->validationProvider,
            $this->validationResultProcessor,
            $this->rulesFactory,
            $inputData,
            $rules,
            $errorMessages
        ))->validate();
    }
}
