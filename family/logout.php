<?php
session_start();
//added error reporting because of this error
//Warning: Cannot modify header information - headers already sent by (output started at /home/cruelcou/public_html/family/functions.php:278) in /home/cruelcou/public_html/family/logout.php on line 8
error_reporting(E_ERROR | E_PARSE);
//created 11/26/13
include 'functions.php';
logout();
//session_unset();
//session_destroy();
header("Location:index.php");
exit();
?>