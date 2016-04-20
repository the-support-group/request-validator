<?php

namespace TheSupportGroup\Common\ValidatorTests;

use TheSupportGroup\Common\Validator\Validator;
use TheSupportGroup\Common\Validator\Helpers\ValidatorFacade;
use TheSupportGroup\Common\Validator\Helpers;
use PHPUnit_Framework_TestCase;
use Respect\Validation\Validatable;
use TheSupportGroup\Common\ValidationAdaptor\ValidationAdaptor;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

class ValidatorIntegrationTest extends PHPUnit_Framework_TestCase
{
    private $validationFacade = null;

    public function setUp()
    {
        $respectValidation = new \Respect\Validation\Validator();
        $validationProviderMock = new ValidationAdaptor($respectValidation);
        $errorBag = new Helpers\FieldsErrorBag();
        $validationResultProcessorMock = new Helpers\ValidationResultProcessor($errorBag);
        $rulesFactory = new Helpers\RulesFactory();

        $this->validationFacade = new ValidatorFacade(
            $validationProviderMock,
            $validationResultProcessorMock,
            $rulesFactory
        );
    }

    /**
     * @group integration
     */
    public function testTheRealThing()
    {
        $inputData = [
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

        $rules = [
            'firstname, lastname' => 'required|alpha|min:2',
            'lastname'            => 'max:18',
            'email'               => 'email',
            'age'                 => 'min:16|numeric',
            'date'                => 'dateFormat:(m-Y.d H:i)',
            'randNum'             => 'between:1, 100',
            'ip'                  => 'ip',
            'password'            => 'required|min:6',
            'password_repeat'     => 'same:password',
            'json'                => 'json',
            'site'                => 'url'
        ];
        
        $validationResult = $this->validationFacade->validate($inputData, $rules);

        $this->assertTrue(count($validationResult->getErrors()) == 0);
        $this->assertTrue(count($validationResult->getErrors('age')) == 0);
    }

    /**
     * @group integration
     */
    public function testTheRealThingWithErrorMessageOverriden()
    {
        $inputData = [
            'firstname'       => 'Denis',
            'lastname'        => 'Klimenko',
            'email'           => 'denis.klimenko.dx@gmail.com',
            'age'             => '15'
        ];

        $rules = [
            'firstname, lastname' => 'required|alpha|min:2',
            'lastname'            => 'max:18',
            'email'               => 'email',
            'age'                 => 'min:16|numeric'
        ];

        $errorMessage = 'this is so wrong';

        $errorMessages = [
            'age.min' => $errorMessage
        ];
        
        $validationResult = $this->validationFacade->validate($inputData, $rules, $errorMessages);

        $this->assertTrue(count($validationResult->getErrors()) == 1);
        $this->assertTrue(count($validationResult->getErrors('age')) == 1);
        $this->assertEquals($errorMessage, $validationResult->getErrors('age')[0]);
    }
}
