<?php 

	include('includes/templates/header.php');
	include('includes/templates/navbar.php');
	include('includes/functions/connect.php');

?>

	<!-- Start header section -->

	<header>
	  <div id="text-box">
			<p class="main">MANO HOUSE UK</p>
			<p class="sub">A little bit detail about Mano cozmatic</p>
		</div>
	</header>
<!-- End header section -->

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
		$query = $con->prepare("Select  * from Products");                                     
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

<!-- Footer -->

<?php 

	include('includes/templates/footer.php');

?>

