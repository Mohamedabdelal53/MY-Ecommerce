<?php


/**
 ** Get All Funtion v1.0
 ** Function To Get All Records From Database
 */

function getAll($field,$table, $where=NULL,$option=NULL, $order, $ordering='DESC'){
    global $db;
    
    $getAll = $db->prepare("SELECT $field FROM $table $where $option ORDER BY $order $ordering");
    
    $getAll->execute();
    
    $All = $getAll->fetchAll();

    return $All;

}

/*
Check If User Is Not Activated
Function To Check The RegStatus Of The User
*/
function checkUserStatus($user){
    // Check If The User Exist In Database
    global $db;
    $stmt = $db->prepare("SELECT 
                                Username, RegStatus 
                            FROM 
                                users 
                            WHERE 
                                Username = ? 
                            AND 
                                RegStatus = 0");
    $stmt->execute(array($user));
    $count = $stmt->rowCount();
    return $count;
}


/* 
 ** Title Function v1.0
 ** That Echo The Page Title In Case The Page
 ** Has The Variable $pagetitle And Echo The Default Title For Other Pages
*/

function getTitle(){

    global $pageTitle;
    
    if(isset($pageTitle)){
    
        echo $pageTitle ;
    
    }else{
    
        echo 'Default';

    }
}


/**
 ** Home Redirect Function v1.0
 ** This Function Accept Parameters
 ** $theMsg = Echo The Message [ Error | Success | Warning ]
 ** $seconds = Seconds Before Redirecting
*/


function redirectHome($theMsg, $url = null , $seconds = 3){
    
    if($url == null){
        $url = 'index.php';
        $page = 'Home';
    }elseif($url == 'members.php'){
        $url = 'members.php';
        $page = 'Members Page';
    }elseif($url == 'categories.php'){
        $url = 'categories.php';
        $page = 'Categories';
    }elseif($url == 'items.php'){
        $url = 'items.php';
        $page = 'Items';
    }elseif($url == 'comments.php'){
        $url = 'comments.php';
        $page = 'Comments';
    }
    else{
        $url = isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] !== '' ? $_SERVER["HTTP_REFERER"]: 'index.php';
        $page = 'Previous';
    }
    echo $theMsg;
    echo "<div class='container alert alert-info'>You Will Be Redirected To $page Page After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();
}



/**
 ** Checkl Item Function v1.0
 ** Function To Check Item In DataBase [ Function Accept Parameters ]
 ** $select = The Item To Select [ Example: user, item ,category ]
 ** $from = The Table To Select From It [ Example: users, items ,categorys ]
 ** $value = The Value Of Select [ Example: Mobile, Box, Electronics ]
*/

function checkItem($select, $from, $value){
    global $db;
    $stmt = $db->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();
    return $count;
}

/**
 ** Count Number Of Items Function v1.0
 ** Function To Count Number Of Item Rows
 ** $item = The Item To Count
 ** $table = The Table To Choose From
 */

function countItems($item, $table){
    global $db;

    $stmt2 = $db->prepare("SELECT COUNT($item) FROM $table");
    
    $stmt2->execute();
    
    $count = $stmt2->fetchColumn();
    
    return $count;
}


/**
 ** Get Latest Record Funtion v1.0
 ** Function To Get Latest Items From Database [Users, Items, Comments ]
 ** $select = Field To Select
 ** $table = The Table To Choose From
 ** $limit = Number Of Records To Get
 ** $order = The DESC Ordering
 */

function getLatest($select, $table, $order, $limit = 5){
    global $db;
    
    $getstmt = $db->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    
    $getstmt->execute();
    
    $row = $getstmt->fetchAll();

    return $row ;
}