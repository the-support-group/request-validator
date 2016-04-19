<?php

namespace TheSupportGroup\Common\Validator\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Rules\BaseRule;
use TheSupportGroup\Common\Validator\Validator;
use TheSupportGroup\Common\Validator\Helpers\ValidationResultProcessor;
use TheSupportGroup\Common\Validator\Contracts\Helpers\ValidationFacadeInterface;

class ValidatorFacade implements ValidationFacadeInterface
{
    private $validationProvider;

    private $validationResultProcessor;

    /**
     * @param ValidationProviderInterface $validationProvider
     * @param array $userMessages
     */
    public function __construct(
        ValidationProviderInterface $validationProvider,
        ValidationResultProcessor $validationResultProcessor
    ) {
        $this->validationProvider = $validationProvider;
        $this->validationResultProcessor = $validationResultProcessor;
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
            $inputData,
            $rules,
            $errorMessages
        ))->validate();
    }
}
