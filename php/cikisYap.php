<?php ob_start();

 if(!isset($_SESSION))
 {
     session_start();
 }

 $_SESSION = array();
 session_destroy();

 header("location:../index.php");

ob_end_flush(); ?>