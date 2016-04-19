<?php

namespace TheSupportGroup\Common\ValidatorTests\Helpers;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\Validator\Helpers;
use TheSupportGroup\Common\ValidationAdaptor\ValidationAdaptor;

class ValidatorFacadeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $validationProviderMock = $this->getMockBuilder(ValidationAdaptor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validationResultProcessorMock = $this->getMockBuilder(Helpers\ValidationResultProcessor::class)->disableOriginalConstructor()
            ->getMock();

        $validationResultProcessorMock->fieldsErrorBag = $this->getMockBuilder(Helpers\FieldsErrorBag::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->testObject = new Helpers\ValidatorFacade(
            $validationProviderMock,
            $validationResultProcessorMock
        );
    }

    /**
     * testValidation Test that validate executes as expected.
     */
    public function testValidate()
    {
        // Prepare / Mock
        $inputData = [];
        $rules = [];
        $userMessages = [];
    
        // Execute
        $result = $this->testObject->validate($inputData, $rules, $userMessages);
    
        // Assert Result
        $this->assertInstanceOf(Helpers\ValidationResultProcessor::class, $result);
        $this->assertNull($result->getErrors());
    }
}