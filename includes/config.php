<?php
session_start();

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$rec_per_page = 3;
$start = ($page - 1) * $rec_per_page;