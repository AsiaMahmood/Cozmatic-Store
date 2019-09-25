<?php
	
	include('includes/functions/connect.php');

	if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	if(isset($_SESSION['Username']))
	{

		include('includes/functions/connect.php');

		// Check user type
		$username= $_SESSION['Username'];
		$query = $con->prepare("Select  * from Users where Username = :zusername "  );                                     
		$query->bindParam(':zusername', $username);
		$query->execute();
		$row = $query->fetch();
		$GroupID = $row['GroupID'];
		$_SESSION['ID'] = $row['UserID'];
		if($GroupID == 0){

			$usertype ='user';

		} elseif ($GroupID == 1) {

			$usertype = 'moderator';

		} elseif ($GroupID == 2) {

			$usertype = 'admin';
			
		}
	} else {
		$usertype = 0;
	}

?>
<link rel="stylesheet" href="style.css">
		<!-- start navbar -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<a class="navbar-brand" href="index.php">MANO</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
				  <ul class="navbar-nav mr-auto">	
						<li class="nav-item dropdown"  >
							<a class="nav-link dropdown-toggle " href="#" id="CategoryDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Categories
							</a>
							<div class="dropdown-menu"  aria-labelledby="CategoryDropdown">
							<?php 
							        $query = $con->prepare("Select  Name from Categories");                                     
									$query->execute();
									$rows = $query->fetchAll();
									$count = $query->rowCount();
									if($count>0){
										foreach($rows as $row)
										{
											echo "<a class='dropdown-item text-muted' href='CategoryHome.php?do=". $row['Name'] ."'>".$row['Name']."</a>";
										}
									}
							?>
	
							
								
							</div>
						</li>
						<!-- Start Dashboard -->
						<?php 
							if( $usertype==='admin' || $usertype==='moderator' ){
							?>
								<li class="nav-item">
									<a class="nav-link" href="dashboard.php?Main&userid=<?php echo $_SESSION['ID']; ?>">Dashboard</a>
								</li>
							<?php
							}
						?>
						<!-- Finish Dashboard -->
						<li class="nav-item">
							<a class="nav-link" href="about-us.php">About-us</a>
						</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
				
					<?php 
						if($usertype === 0){
						?>
						<li class="nav-item">
							<a class="nav-link" href="signup.php">Login</a>
						</li>
						<?php
						}
					?>
					<?php
					if( $usertype==='user' || $usertype==='admin' || $usertype==='moderator' )
					{
					?>
						<li class="nav-item dropdown navbar-right "  >
							<a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php echo $_SESSION['Username']; ?>
							</a>
							<div class="dropdown-menu" style="margin:.125rem -6em; border-radius:0px;" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="profile.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit Profile</a>
								<a class="dropdown-item" href="logout.php">Logout</a>
							</div>
						</li>
						<li class="nav-item active"><a class="nav-link" href=""><i class="fas fa-shopping-cart"></i></a></li>
					<?php
					}
					?>
				</ul>
			</div>
		</nav>
		<!-- end navbar -->
	