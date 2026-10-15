<?php
ini_set('display_errors', 1);
include('../../config.php');
include('../sessions/save_sessions.php');
include('../sessions/use_sessions.php');

/* Defining a new MySQL connection for the AI */
$databaseConnection = mysqli_connect($al_host, $al_user, $al_password, $al_db_name);
mysqli_set_charset($databaseConnection, 'utf8');
