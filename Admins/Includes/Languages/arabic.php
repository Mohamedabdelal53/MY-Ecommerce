<?php

function lang($phrase){
    static $lang = array(
        'Message'=>'مرحبا',
        'Admin'=>'المسؤل'
    );
    return $lang[$phrase];
}
?>