<?php

namespace TheSupportGroup\Common\ValidatorTests;

use TheSupportGroup\Common\Validator\Validator;
use TheSupportGroup\Common\Validator\Helpers;
use TheSupportGroup\Common\Validator\Rules;
use PHPUnit_Framework_TestCase;
use Respect\Validation\Validatable;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers as Contracts;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    private $postData;
    private $nonUniqueEmail;
    private $validateable;
    private $validationProviderMock;
    private $validationResultProcessorMock;
    private $rulesFactoryMock;

    public function setUp()
    {
        $this->postData = [
            'firstname'       => 'Denis',
            'lastname'        => 'Klimenko',
            'email'           => 'denis.klimenko.dx@gmail.com',
            'age'             => '21',
            'date'            => '12-2013.01 23:32',
            'rule'            => 'on',
            'ip'              => '192.168.0.0',
            'password'        => '123456789',
            'password_repeat' => '123456789',
            'json'            => '[{"foo":"bar"}]',
            'randNum'         => rand(1, 100)
        ];

        $this->nonUniqueEmail = 'dd@dd.dd';

        $this->rulesFactoryMock = $this->getMockBuilder(Contracts\RulesFactoryInterface::class)
            ->getMock();

        $this->validateable = $this->getMockBuilder(Validatable::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationProviderMock = $this->getMockBuilder(ValidationProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationProviderMock->expects($this->any())
            ->method('rule')
            ->will($this->returnValue($this->validationProviderMock));

        $this->validationProviderMock->expects($this->any())
            ->method('validate')
            ->will($this->returnValue($this->validateable));

        $this->validationResultProcessorMock = $this->getMockBuilder(Contracts\ValidationResultProcessorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationResultProcessorMock->fieldsErrorBag = $this->getMockBuilder(Contracts\FieldsErrorBagInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationFacade = new Helpers\ValidatorFacade(
            $this->validationProviderMock,
            $this->validationResultProcessorMock,
            $this->rulesFactoryMock
        );
    }

    /**
     * Calls the __call method and runs through the validation.
     */
    public function testValidate()
    {
        $inputData = [
            'firstname' => 'Abdul',
            'lastname' => 'Qureshi',
            'age' => 26
        ];

        $rules = [
            'firstname,lastname' => 'alpha|equals:bla',
            'age' => 'in:21,34'
        ];

        $errorMessages = [
            'age.in' => 'this is so wrong'
        ];

        $ruleType = new Rules\Alpha(
            [],
            $this->validationProviderMock
        );

        $ruleMock = $this->getMockBuilder(Rules\Alpha::class)
            ->disableOriginalConstructor()
            ->getMock();
        $ruleMock->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        // Mock objects.
        $this->validationProviderMock->expects($this->never())
            ->method('chooseErrorMessage')
            ->with($ruleType);

        $this->rulesFactoryMock->expects($this->any())
            ->method('createRule')
            ->with(
                $this->logicalOr('alpha', 'equals', 'in'),
                $this->isType('array'),
                $this->isType('array'),
                $this->validationProviderMock
            )
            ->will($this->returnValue($ruleMock));

        $ValidationResultProcessor = $this->validationFacade->validate($inputData, $rules, $errorMessages);

        $this->assertInstanceOf(Contracts\ValidationResultProcessorInterface::class, $ValidationResultProcessor);
    }

    /**
     * Calls the __call method and runs through the validation.
     */
    public function testValidateInvalidResult()
    {
        $inputData = [
            'firstname' => 'Abdul',
            'lastname' => 'Qureshi',
            'age' => 26
        ];

        $rules = [
            'firstname,lastname' => 'alpha|equals:bla',
            'age' => 'in:21,34'
        ];

        $errorMessages = [
            'age.in' => 'this is so wrong'
        ];

        $ruleType = new Rules\Alpha(
            [],
            $this->validationProviderMock
        );

        $ruleMock = $this->getMockBuilder(Rules\Alpha::class)
            ->disableOriginalConstructor()
            ->getMock();
        $ruleMock->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));

        // Mock objects.
        $this->validationProviderMock->expects($this->any())
            ->method('chooseErrorMessage')
            ->with($ruleType);

        $this->rulesFactoryMock->expects($this->any())
            ->method('createRule')
            ->with(
                $this->logicalOr('alpha', 'equals', 'in'),
                $this->isType('array'),
                $this->isType('array'),
                $this->validationProviderMock
            )
            ->will($this->returnValue($ruleMock));

        $ValidationResultProcessor = $this->validationFacade->validate($inputData, $rules, $errorMessages);

        $this->assertInstanceOf(Contracts\ValidationResultProcessorInterface::class, $ValidationResultProcessor);
    }
}
