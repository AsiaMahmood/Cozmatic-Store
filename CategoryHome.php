<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Categories</title>

    <link rel="stylesheet" href="categories.css">
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/fixed.css">
    <link rel="stylesheet" href="categories.css">
</head>
<body>

<?php 
    include('includes/functions/connect.php');
    $do = isset($_GET['do']);
?>

<!-- Start Products section -->
<section>
	
	<br><br>
	<p class="Head">Our Products</p>
	<br>
    <p class="sub"></p> 
	<div class="product">
	<!-- Start Products -->
	<?php
        // Get all products
        
		$query = $con->prepare("Select  * from Products where Category='$do'");                                     
		$query->execute();
		$rows = $query->fetchAll();
		$count = $query->rowCount();
		foreach($rows as $row)
		{
	?>
	
		<a class="product-card">
			<div class="product-img">
				<img src="img/<?php echo $row['imgUrl']; ?>" alt="">
			</div>
			<div class="product-info">
				<h5><?php echo $row['Name']; ?></h5>
				<h6><?php echo $row['Price']; ?></h6>
			</div>
		</a>
	<?php
		}
	?>
	
	</div>
	<!-- Finish Products -->
</section>
<!-- End Products section -->


