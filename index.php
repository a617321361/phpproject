<?php
 

$pdo = require_once __DIR__.'/lib/db.php';

require_once __DIR__.'/class/users.php';
require_once __DIR__.'/class/Rest.php';

$user = new User($pdo);
$rest = new Rest($pdo);
$rest->Run();

// //  $user->register('admin','admin');
// var_dump( $user->login('admin','admin'));

//  var_dump($_SERVER['PATH_INFO']);