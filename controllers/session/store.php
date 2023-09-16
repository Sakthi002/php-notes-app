<?php

use Core\App;

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];

if(! \Core\Validator::email($email)) {

    $errors['email'] = "Please provide a valid email address";
}

if(! \Core\Validator::string($password)) {

    $errors['password'] = "Please provide a valid password";
}

if(! empty($errors)) {

    view('session/create.view.php', ['errors' => $errors]);

    return;
}

$db = App::resolve(\Core\Database::class);

$user = $db->query('select * from users where email = :email',[
   'email' => $email
])->find();

if($user) {

    if(password_verify($password, $user['password'])) {

        login([
            'email' => $email
        ]);

        header('location: /');

        exit();
    }
}

view('session/create.view.php',[
    'errors' => [
        'password' => 'No matching account for that email address and password'
    ]
]);

