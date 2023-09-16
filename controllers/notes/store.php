<?php

use \Core\Validator;

use Core\App;

$db = App::resolve(\Core\Database::class);

$errors = [];

if(! Validator::string($_POST['body'], 1,1000)) {

    $errors['body'] = "A body is not more than 1000 characters is required.";
}

if(! empty($errors)) {

    view("notes/create.view.php",[
        'heading' => 'Create Note',
        'errors' => $errors
    ]);

    return;
}

$db->query("INSERT INTO notes(body, user_id) VALUES (:body, :user)",[
    'body' => $_POST['body'],
    'user' => 1
]);

header('location: /notes');

die();
