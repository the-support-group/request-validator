<?php

namespace TheSupportGroup\Validator\Tests\Rules;

use PHPUnit_Framework_TestCase;

class RulesTest extends PHPUnit_Framework_TestCase
{
    public function testValidateAllRules()
    {
        $namespace = 'TheSupportGroup\\Validator\\Rules\\';
        $rulesDir = __DIR__ . '/../../../src/Rules';
        $ruleConfig = [];

        $files = scandir($rulesDir);
        $filesToProcess = array_diff($files, ['.', '..', 'BaseRule.php']);

        foreach ($filesToProcess as $file) {
            $class = basename($file, '.php');

            $qualifiedClassNamespace = $namespace . $class;
            $rule = new $qualifiedClassNamespace($ruleConfig);

            // Check that these methods exist on these rules.
            $this->assertTrue(method_exists($rule, 'isValid'));
            $this->assertTrue(method_exists($rule, 'getMessage'));
            $this->assertInternalType('string', $rule->getMessage());
        }
    }
}
