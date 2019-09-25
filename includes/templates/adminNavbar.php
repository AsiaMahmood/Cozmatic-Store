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

<!-- start navbar -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<a class="navbar-brand" href="index.php">MANO</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
				  <ul class="navbar-nav mr-auto">
				  		<li class="nav-item">
							<a class="nav-link" href="dashboard.php?Main&userid=<?php echo $_SESSION['ID']; ?>">Dashboard</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="categories.php?Main&userid=<?php echo $_SESSION['ID']; ?>">Categories</a>
						</li>
						<li class="nav-item">
								<a class="nav-link" href="products.php">Products</a>
						</li>
						<li class="nav-item">
								<a class="nav-link" href="members.php?do=Main&userid=<?php echo $_SESSION['ID']; ?>">Members</a>
						</li>
						
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="nav-item dropdown navbar-right "  >
						<a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo $_SESSION['Username']; ?>
						</a>
						<div class="dropdown-menu" style="margin:.125rem -6em; border-radius:0px;" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="profile.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit Profile</a>
							<a class="dropdown-item" href="logout.php">Logout</a>
						</div>
					</li>
				</ul>
				
			</div>
		</nav>
		<!-- end navbar -->
