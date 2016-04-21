# PHP Data Validator

Make your apps validation easily (inspired by Laravel Validation)

---

Page Index:
- [Quick Start](#quick-start)
- [Contributing](#contributing)
- [Testing](#testing)
- [License](#license)

Suggested Links:
- [Installation](/docs/installation.md)
- [Feature Guide](/docs/feature-guide.md)
- Rules
    - [Existing Rules](/docs/rules.md)
    - [Customization](/docs/rules-customization.md)
    - [Formatting Message](/docs/formatting-message.md)
- [Integrations](/docs/integrations.md)

----

<a name="quick-start"></a>
## Quick start :rocket:
```php
<?php

// Prepare the input data.
$inputData = $_POST;

// Prepare rules to be applied on the input data.
$rules = [
    # firstname and lastname must exists
    # they should be alphanumeric
    # atleast 2 characters
    'firstname, lastname' => 'required|alpha|min:2',

    # max until 18 characters only
    'lastname'            => 'max:18',

    # must be an email format
    # must be unique under 'users' table
    'email'               => 'email|unique:users',

    # must be numeric
    # must exists under 'users' table
    'id'                  => 'numeric|exists:users',
    'age'                 => 'min:16|numeric',
    'info[country]'       => 'required|alpha',

    # roll[0] or roll[1] values must be in the middle 1 to 100
    'roll[0], roll[1]'    => 'numeric|between:1, 100',

    # the format must be 'm-Y.d H:i'
    'date'                => 'dateFormat:(m-Y.d H:i)',

    # it must be an image format under $_FILES global variable
    'profileImg'          => 'image',

    # the provided phone number should follow the format
    # correct: +38(123)456-12-34
    # wrong: +38(123)56-123-56
    # wrong: +39(123)456-12-34
    'phoneMask'           => 'phoneMask:(+38(###)###-##-##)',
    'randNum'             => 'between:1, 10|numeric',

    # the value must be an IP Format
    'ip'                  => 'ip',
    'password'            => 'required|min:6',

    # the value from a key 'password' must be equal to 'password_repeat' value
    'password_repeat'     => 'same:password',

    # it must be a json format
    'json'                => 'json',
    'site'                => 'url',

    # cash10 or cash25 must only have these
    # 1 or 2 or 5 or 10 or 20 or 50
    'cash10, cash25'      => 'in:1, 2, 5, 10, 20, 50',

    # the value must not have 13 or 18 or 3 or 4
    'elevatorFloor'       => 'notIn:13, 18, 3, 4',
];

// Custom error messages for rules in case validation does not pass.
$customMessages = [
   'info[country].alpha' => 'Only letters please',
   'email.required'      => 'Field :field: is required',
   'email.email'         => 'Email has bad format',
   'email.unique'        => 'This email :value: is not unique',
   'elevatorFloor.notIn' => 'Oops',
];

// Prep up the validator, ideally done using DI.
$respectValidation = new \Respect\Validation\Validator();
$validationProviderMock = new ValidationAdaptor($respectValidation);
$errorBag = new Helpers\FieldsErrorBag();
$validationResultProcessorMock = new Helpers\ValidationResultProcessor($errorBag);
$rulesFactory = new Helpers\RulesFactory();

// Create the validation facade that will give us our validation object to work with.
$validationFacade = new ValidatorFacade(
    $validationProviderMock,
    $validationResultProcessorMock,
    $rulesFactory
);

// Run validation on input data.
$validationResult = $validationFacade->validate($inputData, $rules, $customMessages);

// Check if there are any errors.
$validationResult->hasErrors();

// Count number of errors.
$validationResult->count();

// Get all errors.
$validationResult->getErrors();

// Get error for a specific field.
$validationResult->getErrors('username');

// Get raw error messages with keys.
$validationResult->getRawErrors();

// Get only the first error message for each field.
$validationResult->firsts();

// Get the first error message for a specific field.
$validationResult->first('username');

// Appending an error message.
$validationResult->fieldsErrorBag->add($fieldName, $message);
```

### Error message variables.

You can use 3 variables in your error messages, these will be dynamically replaced by the actual values being used at the time. These are:

:field: => The field being validated.
:rule: => The rule being applied.
:value: => The value being validated against.


<a name="contributing"></a>
## Contributing :octocat:

Dear contributors , the project is just started and it is not stable yet, we love to have your fork requests.


<a name="testing"></a>
## Testing

This project is mostly unit tested down to its core.

The testing suite can be run on your own machine. The main dependency is [PHPUnit](https://github.com/sebastianbergmann/phpunit) which can be installed using [Composer](http://getcomposer.org):

```sh
# run this command from project root
$ composer install --dev --prefer-source
```

```sh
vendor/bin/phpunit --configuration phpunit.xml --coverage-text
```

For additional information see [PHPUnit The Command-Line Test Runner](http://phpunit.de/manual/current/en/textui.html).

<a name="license"></a>
## License

PHP Data Validator is open-sourced software licensed under the [GNU GPL](LICENSE).
