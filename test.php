<?php
var_dump($_SERVER['QUERY_STRING']);

var_dump($_GET);

unset($_GET['bar']);

var_dump($_SERVER['QUERY_STRING']);

echo password_hash('123', PASSWORD_DEFAULT);


$passwordHash = '$2y$10$q125OV7tlt5krQ2DgQ8jdecVNjiTalxU8Cgk0hByPB7NE85Sg5f.m';
echo '<br><br>';
echo password_verify('123', $passwordHash);