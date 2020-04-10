<?php

/* WORDS */
$_title = 'Задачи';
$_siteName = 'BeeJee Тестовое Задание';
$_changedByAdmin = 'Задача выполнена и отредактирована администратором';


/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DATABASE_HOST', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', 'l+JVW|hB!qjjDNJ2');
define('DATABASE_NAME', 'beejee');

// CONST
define('TASKS_BY_PAGE', 3);
$_error = false;
$_error_text = 'Что-то пошло не так:';
$_success = false;
$_success_text = 'Всё получилось!';
$_page = 'index';
$_id = 0;
$_inputName = '';
$_inputEmail = '';
$_textareaTask = '';
$_inputDone = false;