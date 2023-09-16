<?php

use Core\Validator;

$email = $_POST['email'];

$password = $_POST['password'];

// validate the form inputs

$errors = [];

if(! Validator::email($email)) {

    $errors['email'] = "Please provide a valid email address";
}

if(! Validator::string($password, 7, 255)) {

    $errors['password'] = "Please provide a password of at least 7 characters";
}

if(! empty($errors)) {

    return view('registration/create.view.php', ['errors' => $errors]);
}


$db = \Core\App::resolve(\Core\Database::class);

// check if the account already exists

$user = $db->query('select * from users where email = :email', ['email'=>$email])->find();

// If yes, redirect to login page
if($user) {
    header('location: /');

    exit();
// If not, register one and login then redirect to dashboard
} else {

    $db->query('insert into users(email, password) values(:email, :password)',[
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    login([
        'email' => $email
    ]);

    header('location: /');

    exit();
}