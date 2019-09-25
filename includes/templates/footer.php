<?php
	include('includes/functions/connect.php');
?>

<!-- FOOTER START -->
<div class="footer">
		<div class="contain">
		<div class="col">
		  <h1>Our Company</h1>
		  <ul>
			<li>About us</li>
			<li>Mission</li>
			<li>Services</li>
		  </ul>
		</div>
		<div class="col">
		  <h1>Products</h1>
		  <ul>
				<?php
					$query = $con->prepare("Select  Name from Categories");                                     
					$query->execute();
					$rows = $query->fetchAll();
					$count = $query->rowCount();
					if($count>0){
						foreach($rows as $row)
						{
							echo "<li>".$row['Name']."</li>";
						}
					}
				?>
		  </ul>
		</div>
		
		<div class="col">
		  <h1>Contact us</h1>
		  <ul>
			<li>aymen.rafiaa@gmail.com</li>
		  </ul>
		</div>
		<div class="col social">
		  <h1>Social</h1>
		  <ul>
				<li><a class="fab fa-telegram" target="_blank"><i ></i></a></li>
				<li><a class="fab fa-whatsapp" target="_blank"></a></li>
				<li><a class="fab fa-facebook-f" target="_blank"></a></li>
				<li><a class="fab fa-instagram" target="_blank"></a></li>
			</ul>
		</div>
	  <div class="clearfix"></div>
	  </div>
	  </div>
<!-- END OF FOOTER -->

<!--- Script Source Files -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/users.js"></script>
<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/releases/v5.6.1/js/all.js"></script>
<!--- End of Script Source Files -->

</body>
</html>

