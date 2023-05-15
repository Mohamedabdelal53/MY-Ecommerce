<?php
include 'connect.php';
$tpl = 'Includes/Templates/';
$lang = 'Includes/Languages/';
$func = 'Includes/Functions/';
$css= 'Layout/CSS/';
$JS= 'Layout/JS/';


// include the important files

include $func.'functions.php';
include $lang.'eng.php';
include  $tpl.'header.php';
if(!isset($noNavbar)){
    include  $tpl.'navbar.php';
}
?>