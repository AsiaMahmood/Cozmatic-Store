<?php
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	include('includes/functions/connect.php');

    require_once 'includes/Api/Facebook/autoload.php';

    $FB = new  \Facebook\Facebook([
        'app_id' => '1661248114006602' ,
        'app_secret' => 'c8f57f623273acde1cfbd10c8715335b' , 
        'default_graph_version' => 'v2.10' 
    ]);

    $helper = $FB->getRedirectLoginHelper();


?>
