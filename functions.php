<?php

/**
 * getTasks
 *
 * @param  string $column
 * @param  int $offset
 * @param  string $sortByHow
 *
 * @return array
 */
function getTasks($column = 'id', $offset = 0, $sortByHow = 'desc')
{
    // sanitize
    $offset = filter_var($offset, FILTER_SANITIZE_NUMBER_INT);
    $column = filter_var($column, FILTER_SANITIZE_STRING);
    switch (strtolower($column)) {
        case 'status':
            $column = 'done';
            break;
        case 'name':
            $column = 'name';
            break;
        case 'email':
            $column = 'email';
            break;
        case 'task':
            $column = 'task';
            break;
        default:
            $column = 'id';
            break;
    }
    $sortByHow = filter_var($sortByHow, FILTER_SANITIZE_STRING);
    switch (strtolower($sortByHow)) {
        case 'asc':
            $sortByHow = 'asc';
            break;
        default:
            $sortByHow = 'desc';
            break;
    }

    // connect
    $mysqli = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    // Change character set to utf8
    mysqli_set_charset($mysqli, "utf8");

    // tasks
    $tasks = [];
    $query  = "SELECT * FROM `tasks` ORDER BY " . $column . " " . $sortByHow . " LIMIT ? , " . TASKS_BY_PAGE . ";";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($query)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $offset);

        /* execute query */
        if ($stmt->execute()) {
            /* instead of - $stmt->bind_result($district);*/
            $result = $stmt->get_result();

            /* now you can fetch the results into an array - NICE */
            // global $tasks;
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row; // while($tasks[] = ) appEnds NULL
            }
        }
        /* close statement */
        $stmt->close();
    }
    /* close connection */
    $mysqli->close();

    return $tasks;
}

/**
 * getTotal
 *
 * @param  string $mode
 *
 * @return int
 */
function getTotal($mode = 'pages')
{
    // sanitize
    $mode = filter_var($mode, FILTER_SANITIZE_STRING);

    // connect
    $mysqli = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    // Change character set to utf8
    mysqli_set_charset($mysqli, "utf8");

    // tasks
    $query  = "SELECT * FROM `tasks`;";
    $result = $mysqli->query($query);
    $totalRows = $result->num_rows;
    /* close connection */
    $mysqli->close();
    switch ($mode) {
        case 'pages':
            return ceil($totalRows / TASKS_BY_PAGE);
            break;
        case 'rows':
            return $totalRows;
            break;
    }
}

/**
 * INSERT task
 *
 * @param  string $inputName
 * @param  email $inputEmail
 * @param  string $textareaTask
 *
 * @return bool
 */
function insert($inputName, $inputEmail, $textareaTask)
{
    // sanitize - double check
    $inputName = filter_var($inputName, FILTER_SANITIZE_STRING);
    $inputEmail = filter_var($inputEmail, FILTER_SANITIZE_EMAIL);
    $textareaTask = filter_var($textareaTask, FILTER_SANITIZE_STRING);

    // connect
    $mysqli = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    // Change character set to utf8
    mysqli_set_charset($mysqli, "utf8");

    // prepare
    $query  = "INSERT INTO `tasks` (`name`, `email`, `task`) VALUES (?, ?, ?);";

    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($query)) {
        /* bind parameters for markers */
        $stmt->bind_param("sss", $inputName, $inputEmail, $textareaTask);
        /* execute query */
        if (!$stmt->execute()) {
            return false;
        }
        /* close statement */
        $stmt->close();
    }
    /* close connection */
    $mysqli->close();

    return true;
}

/**
 * login
 *
 * @param  string $inputName
 * @param  string $inputPassword
 *
 * @return bool
 */
function login($inputName, $inputPassword)
{
    // sanitize - double check
    $inputName = filter_var($inputName, FILTER_SANITIZE_STRING);
    $inputPassword = filter_var($inputPassword, FILTER_SANITIZE_STRING);

    // TODO: db
    $passwordHash = '$2y$10$q125OV7tlt5krQ2DgQ8jdecVNjiTalxU8Cgk0hByPB7NE85Sg5f.m';
    if ($inputName == 'admin' && password_verify($inputPassword, $passwordHash)) {
        setcookie('inputName', $inputName, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie('inputPassword', $inputPassword, time() + (86400 * 30), "/"); // 86400 = 1 day
        return true;
    }

    return false;
}

/**
 * fillInFormVars
 *
 * @param  int $id
 *
 * @return bool
 */
function fillInFormVars($id)
{
    global $_error, $_error_text, $_id, $_inputName, $_inputEmail, $_textareaTask, $_inputDone;
    // sanitize - double check
    $id =  filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    // connect
    $mysqli = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    // Change character set to utf8
    mysqli_set_charset($mysqli, "utf8");

    // prepare
    $query  = "SELECT `id`, `name`, `email`, `task`, `done` FROM `tasks` WHERE `id`= ? LIMIT 1;";
    $stmt = $mysqli->prepare($query);
    // Bind parameters (s = string, i = int, b = blob, etc)
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        $_error_text = 'SQL запрос не правильно составлен:' . $stmt->error;
        $_error = true;
    }
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($_id, $_inputName, $_inputEmail, $_textareaTask, $_inputDone);
        $stmt->fetch();
        $stmt->close();
        /* close connection */
        $mysqli->close();
        return true;
    } else {
        $_error_text = 'Задача #' . $id . ' не найдена:' . $stmt->error;
        $_error = true;
    }
    $stmt->close();
    /* close connection */
    $mysqli->close();


    return false;
}


/**
 * UPDATE task
 *
 * @param  int $id
 * @param  string $inputName
 * @param  email $inputEmail
 * @param  string $textareaTask
 * @param  string $inputDone
 *
 * @return bool
 */
function update($id, $inputName, $inputEmail, $textareaTask, $inputDone)
{
    // sanitize - double check
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $inputName = filter_var($inputName, FILTER_SANITIZE_STRING);
    $inputEmail = filter_var($inputEmail, FILTER_SANITIZE_EMAIL);
    $textareaTask = filter_var($textareaTask, FILTER_SANITIZE_STRING);
    $inputDone = filter_var($inputDone, FILTER_SANITIZE_STRING);
    if ($inputDone == 'done' || $inputDone == true || $inputDone == 'checked') {
        $inputDone = 1; // true - checked
    } else {
        $inputDone = 0; // false - not checked
    }

    // connect
    $mysqli = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    // Change character set to utf8
    mysqli_set_charset($mysqli, "utf8");

    // prepare
    $query  = "UPDATE `tasks` SET `name` = ?, `email` = ?, `task` = ?, `done` = ? WHERE `tasks`.`id` = ?;";

    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($query)) {
        /* bind parameters for markers */
        $stmt->bind_param("sssii", $inputName, $inputEmail, $textareaTask, $inputDone, $id);
        /* execute query */
        if (!$stmt->execute()) {
            return false;
        }
        /* close statement */
        $stmt->close();
    }
    /* close connection */
    $mysqli->close();

    return true;
}

/**
 * logout
 *
 * @return void
 */
function logout()
{
    if (isset($_COOKIE['inputName'])) {
        unset($_COOKIE['inputName']);
        setcookie('inputName', null, -1, '/');
    }
    if (isset($_COOKIE['inputPassword'])) {
        unset($_COOKIE['inputPassword']);
        setcookie('inputPassword', null, -1, '/');
    }
}