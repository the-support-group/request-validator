<?php

namespace TheSupportGroup\Common\Validator\Contracts\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

interface ValidationFacadeInterface
{
    /**
     * @param ValidationProviderInterface $validationProvider
     * @param ValidationResultProcessorInterface $validationResultProcessor
     * @param RulesFactoryInterface $rulesFactory
     * @return void
     */
    public function __construct(
        ValidationProviderInterface $validationProvider,
        ValidationResultProcessorInterface $validationResultProcessor,
        RulesFactoryInterface $rulesFactory
    );

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
    );
}