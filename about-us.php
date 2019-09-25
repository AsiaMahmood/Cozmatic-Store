<?php
	if(!isset($_SESSION)) 
	{ 
			session_start(); 
	} 
	if(isset($_SESSION['Username']))
	{

		include('includes/functions/connect.php');

		// Check user type
		$id= $_SESSION['ID'];
		$query = $con->prepare("Select  GroupID from Users where UserID = " . $id );                                     
		$query->execute();
		$GroupID = $query->fetch();

		if($GroupID['GroupID'] == 0){

			$usertype ='user';

		} elseif ($GroupID['GroupID'] == 1) {

			$usertype = 'moderator';

		} elseif ($GroupID['GroupID'] == 2) {

			$usertype = 'admin';
			
		}
	} else {
		$usertype = 0;
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>About us</title>

	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="css/fixed.css">
  <link rel="stylesheet" href="about-us.css">
</head>

<body data-spy="scroll" data-target="#navbarResponsive">



	<!-- start  navbar -->
<?php 
	if($usertype === 0 )
	{
		include('includes/templates/navbar.php');
	} elseif($usertype === 'admin' || $usertype === 'moderator' ){
		include('includes/templates/adminNavbar.php');
	}
?>
	<!-- end navbar -->

    <!-- Section2 -->
    <div class="section2">
        <p class="main">Mano House UK</p>
        <p class="sub">Mano House UK is a leading total beauty care company based in Iraq. Our main goal is providing affordable luxury for people who demand excellence in beauty. As part of our mission to empower and support women, </p>
    </div>




<!-- FOOTER START -->
<?php include('includes/templates/footer.php');