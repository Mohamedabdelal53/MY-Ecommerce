<?php

session_start(); // Start The Session

unset($_SESSION['user']); // Unset The Session

header('Location: index.php');

exit();