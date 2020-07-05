# PHP form-validation

Currently checks to see if a name, email and password have been populated. 
If they have it will then perform a check to see if the values, indeed suit the 
criteria of such an input.

Code can easily be added to in order to handle more/other input types.

## Installation

add to your file composer.json this settings
```json
 "name": "yourProjectName",
    "description": "Your description",
    "type": "your type",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/lika1995/validator"
        }
    ],
    "require": {
        "lika1995/validator": "dev-master"
    },
    "minimum-stability": "dev"
}
```
Add this package to your composer.

composer require lika1995/validator

## How to use
```php
require '/vendor/autoload.php';
use Lika\Validate\Validate;

    $pdo = new PDO("dns...");
    $validate = new Validate($pdo);

// here you must choose the array $ _GET or $ _POST,
// in which the data will be sent and write the rules by which you will check the data
//you can add new rules in switch/case in Validate.php
    $validation = $validate->check($_POST, [
        'username' => [
            'required' => true,
            'min' => 3,
            'max' => 15,
            'unique' => 'users',
        ],
        'email'=>[
            'required' => true,
            'email' => 'email'
        ]
        ,
        'password' => [
            'required' => true,
            'min' => 3,
        ],
        'password_again' => [
            'required' => true,
            'matches' => 'password'
        ]
    ]);

    if ($validation->passed()) {
        echo 'passed';
    } else {
        foreach($validation->errors() as $error){
            echo $error . '<br>';
        }
    }
```