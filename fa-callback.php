<?php

    include('includes/functions/connect.php');
    require_once "includes/functions/facebook.php";

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

    try {
        $accessToken = $helper->getAccessToken();
    } catch (\Facebook\Exceptions\FacebookSDKException $e){
        echo 'Response Exception: ' . $e->getMessage();
        exit();
    } catch (\Facebook\Exception\FacebookSDKException $e) {
        echo "SDK Exception: " . $e->getMessage();
        exit();
    }

    if(!$accessToken){
        header('Location:testWork.php');
        exit();
    }

    if(isset($accessToken))
    {
        $oAuth2Client = $FB->getOAuth2Client();
        if(!$accessToken->isLongLived())
        {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        }

        $response = $FB->get(  '/me?fields=id,name, first_name,email,birthday', $accessToken);
        $userData = $response->getGraphNode()->asArray();
        $username = $userData['first_name'];
        $email = $userData['email'];


        $query = $con->prepare("SELECT * FROM Users WHERE Username='$username' or Email='$email'");
        $query->execute(array($email));
        $result = $query->fetch();
        $count = $query->rowCount();
        echo $count;
        echo $result['Email'];
        if($count > 0){
            // Sign in to Users table
            $username = $userData['first_name'];
            $email = $userData['email'];
            $fullname = $userData['name'];

            $query = $con->prepare("UPDATE  Users SET 
                                                                                    Username = '$username' ,
                                                                                    Email = '$email' , 
                                                                                    Fullname = '$fullname' 
                                                     WHERE Username='$username' or Email='$email'");

            $query->execute(array($username , $email , $fullname ));
            
            $_SESSION['Username'] = $username;
            header('location: index.php');
        }
        
    }


?>