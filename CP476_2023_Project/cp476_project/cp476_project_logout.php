<?php

//destroys session and redirects user to login page
session_destroy();
header('location:cp476_project_login.php');
?>