<?php

$totalPages = getTotal('pages');
$totalRows = getTotal('rows');
// Page from URL
$currPage = isset($_GET['page']) ? (filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT)) : 1;
if ($currPage < 1) { // Not lower than 1st
    $currPage = 1;
}
if ($currPage > $totalPages) { // Not upper than last
    $currPage = $totalPages;
}
$sortBy = isset($_GET['sortBy']) ? strtolower((filter_input(INPUT_GET, 'sortBy', FILTER_SANITIZE_STRING))) : 'id';
$sortByHow = isset($_GET['sortByHow']) ? strtolower((filter_input(INPUT_GET, 'sortByHow', FILTER_SANITIZE_STRING))) : 'desc';
if (isset($_GET['changeSorting'])) {
    $sortByHow = ('desc' == $sortByHow) ? 'asc' : 'desc';
}
$tasks = getTasks($sortBy, ($currPage - 1) * TASKS_BY_PAGE, $sortByHow);