<?php

namespace TheSupportGroup\Common\ValidatorTests\Helpers;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\Validator\Helpers\FieldsErrorBag;

class FieldsErrorBagTest extends PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     */
    private $testObject;

    /**
     * Set up the testing object.
     */
    public function setUp()
    {
        $this->testObject = new FieldsErrorBag();
    }

    /**
     * testSetUserMessages Test that setUserMessages executes as expected.
     */
    public function testSetGetUserMessages()
    {
        // Prepare / Mock
        $messages = [
            'first' => 'this is a message',
            'second' => 'this is the second message'
        ];

        // Execute
        $this->testObject->setUserMessages($messages);

        // Assert Result
        $this->assertEquals($this->testObject->getUserMessages(), $messages);
    }

    /**
     * testGetErrorMessages Test that getErrorMessages executes as expected.
     */
    public function testGetErrorMessages()
    {
        // Prepare / Mock
        $this->testObject->setErrorMessages(['asdfsf']);

        // Execute
        $result = $this->testObject->getErrorMessages();

        // Assert Result
        $this->assertEquals(['asdfsf'], $result);
    }

    /**
     * testClear Test that clear executes as expected.
     */
    public function testClear()
    {
        // Prepare / Mock
        $this->testObject->setErrorMessages(['asdfsf']);

        // Execute
        $result = $this->testObject->clear();

        // Assert Result
        $this->assertEquals(null, $result);
    }

    /**
     * testAdd Test that add executes as expected.
     */
    public function testAdd()
    {
        // Prepare / Mock
        $fieldName = 'firstname';
        $message = 'This field is too long';

        // Execute
        $result = $this->testObject->add($fieldName, $message);

        // Assert Result
        $this->assertInstanceOf(FieldsErrorBag::class, $result);
        $this->assertEquals($message, $this->testObject->getErrorMessages()['firstname'][0]);
    }

    /**
     * testSetField Test that setField executes as expected.
     */
    public function testGetSetField()
    {
        // Prepare / Mock
        $fieldName = 'lastname';

        // Execute
        $result = $this->testObject->setField($fieldName);

        // Assert Result
        $this->assertInstanceOf(FieldsErrorBag::class, $result);
        $this->assertEquals($fieldName, $result->getFieldName());
    }
}
