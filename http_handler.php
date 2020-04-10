<?php

global $_page, $_success, $_error;

/* INSERT TASK */
if (
    isset($_GET['insert'])
    && isset($_POST['inputName'])
    && isset($_POST['inputEmail'])
    && isset($_POST['textareaTask'])
) {
    if (insert(
        filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_STRING),
        filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL),
        filter_input(INPUT_POST, 'textareaTask', FILTER_SANITIZE_STRING)
    )) {
        $_success = true;
    } else {
        $_error = true;
        $_error_text .= 'Не получается добавить задачу.';
    }
}

/* LOGIN FORM */
if (isset($_GET['login'])) {
    $_page = 'login';
}

/* LOGOUT */
if (isset($_GET['logout'])) {
    logout();
}

/* ADMIN LOGIN */
if (isset($_POST['inputName']) && isset($_POST['inputPassword'])) {
    if (login(
        filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_STRING),
        filter_input(INPUT_POST, 'inputPassword', FILTER_SANITIZE_STRING)
    )) {
        $_page = 'admin';
    } else {
        $_error = true;
        $_error_text .= 'Неверные имя или пароль';
    }
}

/* ADMIN COOKIE */
if (isset($_COOKIE['inputName']) && isset($_COOKIE['inputPassword'])) {
    if (login(
        filter_input(INPUT_COOKIE, 'inputName', FILTER_SANITIZE_STRING),
        filter_input(INPUT_COOKIE, 'inputPassword', FILTER_SANITIZE_STRING)
    )) {
        $_page = 'admin';
    } else {
        $_error = true;
        $_error_text .= 'Неверные имя или пароль в куках';
    }
}

/* EDIT TASK */
if ( // every var is set
    isset($_GET['edit'])
    && isset($_GET['id'])
) {
    if (
        isset($_COOKIE['inputName'])
        && isset($_COOKIE['inputPassword'])
        && login(
            filter_input(INPUT_COOKIE, 'inputName', FILTER_SANITIZE_STRING),
            filter_input(INPUT_COOKIE, 'inputPassword', FILTER_SANITIZE_STRING)
        )
    ) {
        fillInFormVars(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $_page = 'admin';
    } else {
        $_error = true;
        $_error_text .= 'Неверные имя или пароль в куках. Вы входили в систему?';
    }
}

/* UPDATE TASK */
if ( // every var is set
    isset($_GET['save'])
    && isset($_POST['id'])
    && isset($_POST['inputName'])
    && isset($_POST['inputEmail'])
    && isset($_POST['textareaTask'])
    && isset($_POST['inputDone'])
    && login(
        filter_input(INPUT_COOKIE, 'inputName', FILTER_SANITIZE_STRING),
        filter_input(INPUT_COOKIE, 'inputPassword', FILTER_SANITIZE_STRING)
    )
) {
    if (update(
        filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
        filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_STRING),
        filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL),
        filter_input(INPUT_POST, 'textareaTask', FILTER_SANITIZE_STRING),
        filter_input(INPUT_POST, 'inputDone', FILTER_SANITIZE_STRING)
    )) {
        $_success = true;
        $_page = 'admin';
    } else {
        $_error = true;
        $_error_text .= 'Не получается изменить задачу.';
    }
}