<?php

namespace TheSupportGroup\Common\ValidatorTests;

use TheSupportGroup\Common\Validator\Validator;
use TheSupportGroup\Common\Validator\Helpers\ValidatorFacade;
use TheSupportGroup\Common\Validator\Helpers;
use PHPUnit_Framework_TestCase;
use Respect\Validation\Validatable;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

class ValidatorIntegrationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group integration
     */
    public function testTheRealThing()
    {
        $inputData = [
            'firstname' => 'Abdul',
            'lastname' => 'Qureshi',
            'age' => 26
        ];

        $rules = [
            'firstname,lastname' => 'alpha|equals:bla,in:nice one',
            'age' => 'in:21,34'
        ];

        $errorMessages = [
            'age.in' => 'this is so wrong'
        ];

        $respectValidation = new \Respect\Validation\Validator();
        $validationProviderMock = new \TheSupportGroup\Common\ValidationAdaptor\ValidationAdaptor($respectValidation);
        $errorBag = new Helpers\FieldsErrorBag();
        $validationResultProcessorMock = new Helpers\ValidationResultProcessor($errorBag);

        $validationFacade = new ValidatorFacade(
            $validationProviderMock,
            $validationResultProcessorMock
        );
        
        $validationResult = $validationFacade->validate($inputData, $rules, $errorMessages);

        // $this->assertTrue(count($validationResult->getErrors()) == 3);
        // $this->assertEquals($validationResult->getErrors()[0] == '');
        // $this->assertEquals($validationResult->getErrors()[1] == '');
        // $this->assertEquals($validationResult->getErrors()[2] == 'this is so wrong');

        // $this->assertTrue(count($validationResult->getErrors('age')) == 1);
        // $this->assertEquals($validationResult->getErrors('age')[0] == 'this is so wrong');

        // $this->assertTrue(count($validationResult->getErrors('firstname')) == 2);
        // $this->assertEquals($validationResult->getErrors('firstname')[0] == 'this is so wrong');
        // $this->assertEquals($validationResult->getErrors('firstname')[0] == 'this is so wrong');

        // $this->assertTrue(count($validationResult->getErrors('lastname')) == 2);
        // $this->assertEquals($validationResult->getErrors('lastname')[0] == 'this is so wrong');
        // $this->assertEquals($validationResult->getErrors('lastname')[0] == 'this is so wrong');
    }
}
