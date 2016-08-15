# Slim 3 API

A RESTful API Boilerplate built on Slim 3 that supports JSON/XML/HTML.

This is a skeleton application with enough boilerplate code to quickly get setup and working on Slim Framework 3.

###Features

- Vagrantfile with bootstrap for server setup
- PHP dotenv (and sample file)
- Respect\Validation
- Fractal
- RKA Content Type Renderer (supports JSON/XML/HTML)
- Monolog
- Swift Mailer
- Twig Template Engine
- JSON Web Tokens
- System Messages, Email, and UUID helper services

###Tests
I've created a test collection in Postman. Feel free to use my collection and the dev environment in the runner with the test data files located in this repository (app/tests/Postman).

It should be noted that by setting ```APP_ENV``` to ```dev``` within the .env file additional user information will be returned by some endpoints. This information (such as tokens) is useful for testing but should not be enabled for a production environment. In a production environment set the ```APP_ENV``` to ```prod```.

The .env file has other useful variables for testing purposes such as ```APP_DEBUG``` and ```APP_SENDMAIL``` which are self-explanatory.

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/979149b5d38f030228bf#?env%5Bslim3-api%20%40%20dev%5D=W3sia2V5IjoidXJsIiwidmFsdWUiOiJodHRwOi8vMTI3LjAuMC4xOjQ1NjciLCJ0eXBlIjoidGV4dCIsImVuYWJsZWQiOnRydWV9LHsia2V5IjoiY29udGVudFR5cGUiLCJ2YWx1ZSI6ImFwcGxpY2F0aW9uL2pzb24iLCJ0eXBlIjoidGV4dCIsImVuYWJsZWQiOnRydWV9LHsia2V5IjoicyIsInZhbHVlIjoiMCIsInR5cGUiOiJ0ZXh0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJpZCIsInZhbHVlIjoiYjU4NmJhNzEtOGU2Ni01YmI3LThmZTUtMTgwZWI4ZTI1NGM3IiwidHlwZSI6InRleHQiLCJlbmFibGVkIjp0cnVlfSx7ImtleSI6Im5hbWUiLCJ2YWx1ZSI6IkdyZWVyIExpdmluZ3N0b24iLCJ0eXBlIjoidGV4dCIsImVuYWJsZWQiOnRydWV9LHsia2V5IjoibmV3TmFtZSIsInZhbHVlIjoiS2VycmkgVmFsZW56dWVsYSIsInR5cGUiOiJ0ZXh0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJlbWFpbCIsInZhbHVlIjoiZ3JlZXIubGl2aW5nc3RvbkBvYXRmYXJtLmluZm8iLCJ0eXBlIjoidGV4dCIsImVuYWJsZWQiOnRydWV9LHsia2V5IjoicGFzc3dvcmQiLCJ2YWx1ZSI6ImxxY2JBbHpiQT4iLCJ0eXBlIjoidGV4dCIsImVuYWJsZWQiOnRydWV9LHsia2V5IjoibmV3UGFzc3dvcmQiLCJ2YWx1ZSI6IipKMy11QXEheSVnQiheLXoqQUFmIzNGKWt4Y0B1KSQ1MyRxKkh1PE9JT25KIiwidHlwZSI6InRleHQiLCJlbmFibGVkIjp0cnVlfSx7ImtleSI6InRva2VuIiwidmFsdWUiOiI0NDlmOWYzYmU0ZmI5NjVhYWViOWNlNmY0ZTkwZWViZiIsInR5cGUiOiJ0ZXh0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJyZXNldFRva2VuIiwidmFsdWUiOiI1MTBhNzRkNzBhMDc4YWE3Y2FmODJhZmQyOTE5NjUwYSIsInR5cGUiOiJ0ZXh0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJhdXRob3JpemF0aW9uIiwidmFsdWUiOiJleUowZVhBaU9pSktWMVFpTENKaGJHY2lPaUpJVXpVeE1pSjkuZXlKcWRHa2lPaUkwY0VOSFdVbHhaMEV5UTA4M2FGVmpUbEJSY1RKUVYwTlVVR2hvVUUxemFYSnpjbVJ3TnpsTFIyaFZQU0lzSW1semN5STZJbWgwZEhBNlhDOWNMekV5Tnk0d0xqQXVNVG8wTlRZM0lpd2lZWFZrSWpvaWFIUjBjRHBjTDF3dllYQndMbXh2WTJGc0lpd2lhV0YwSWpveE5EY3hNamt3TURrNUxDSnVZbVlpT2pFME56RXlPVEF3T1Rrc0ltVjRjQ0k2TVRRM01UZzVORGc1T1N3aVpHRjBZU0k2ZXlKMWMyVnlTV1FpT2lKaU5UZzJZbUUzTVMwNFpUWTJMVFZpWWpjdE9HWmxOUzB4T0RCbFlqaGxNalUwWXpjaWZYMC5tWVZnLTMxVFg4ZWxaZ1YzWERfd2dnYlNaRkFjZUdqRExUb21jc1c4VS04Ymg2eHI3OXF3U3NFeGxDRlJWUUUtRkR2clo2eXhBcVRHcGZZV1dvQ2dHZyIsInR5cGUiOiJ0ZXh0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJhdXRob3JpemF0aW9uS2V5IiwidmFsdWUiOiJudWxsIiwidHlwZSI6InRleHQiLCJlbmFibGVkIjp0cnVlfSx7ImtleSI6ImF1dGhvcml6YXRpb25WYWx1ZSIsInZhbHVlIjoibnVsbCIsInR5cGUiOiJ0ZXh0IiwiZW5hYmxlZCI6dHJ1ZX1d)

###Validation
Sometimes you want to validate, other times not, and sometimes a field is optional. In edge cases your field name may be different from your validator name. This validation middleware takes that all into consideration.

By setting the "validators" route argument you can specify any fields which you would like to validate. The validation rules are located in (app/src/Middleware/ValidationRules.php).
```
->setArguments(['validators' => ['id', 'email']])
```

The above example validates two fields, id and email, against the "id" and "email" validation rules.

For more complex validation supply the optional array as value to the parameter.
```
->setArguments(['validators' => [
    'id' => ['uuid'],
    'name' => [false, true],
    'password' => [false, true],
    'newPassword' => ['password', true, 'New Password']
]])
```

In the above example the "id" parameter is being validated using the "uuid" validation rule. The "name" parameter is optional. The "password" parameter is optional. And the "newPassword" is being validated using the "password" validation rules, is optional, and the translator is using the phrase "New Password" as the human-readable term for the field in any error messages.

For reference, the optional array uses the following signature. ```[string $validationRule, bool $optional, string $name]```

This middleware uses Respect\Validation so check out their documentation for further details: http://respect.github.io/Validation/

The validation middleware is loosely based on Davide Pastore's Slim-Validation. https://github.com/DavidePastore/Slim-Validation