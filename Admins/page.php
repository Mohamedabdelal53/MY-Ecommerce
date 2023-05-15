<?php
/*
    Categories => [ Manage | Edit | Update | Add | Insert | Delete | stats]
*/
$do='';

isset($_GET['do'])? $do = $_GET['do']: $do = 'Manage';


//if page is main page

if($do=='Manage'){
    echo 'Welcome You Are In Manage Category Page <br>';
    echo '<a href="page.php?do=Add"> Add Category + </a>';
}elseif($do=='Add'){
    echo 'Welcome You Are In Add Category';
}elseif($do=='Insert'){
    echo 'Welcome You Are In Insert Category';
}else{
    echo 'Error There\'s No Page With This Name';
}