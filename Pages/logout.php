<?php

// initialise session data
if (session_status() == PHP_SESSION_NONE) { session_start(); }
// destroy session data
session_destroy();
// redirect to index page
header("Location: ../PHP/index.php");

