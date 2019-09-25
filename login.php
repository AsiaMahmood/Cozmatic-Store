<?php 

include('includes/functions/connect.php');

    session_start();
    if(isset($_SESSION['Username'])) {
        header('Location: admin/index.php');
    }
    // Check http post request
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // Check Admin exist
        $query = $con->prepare("Select 
                                                            UserID, GroupID, Username,Password 
                                                    from 
                                                            Users 
                                                    where 
                                                            Username= '". $username ."' 
                                                    AND 
                                                            Password='". $hashedPass. "' 
                                    
                                                    ");
        $query->execute(array($username,$hashedPass));
        $row = $query->fetch();
        $countAdmin = $query->rowCount();

        if($countAdmin)
        {
            $_SESSION['Username'] = $username;
            $_SESSION['ID'] = $row['UserID'];
            $_SESSION['GroupId'] = $row['GroupID'];
            header("Location:index.php");
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/fixed.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>

<!-- start navbar -->
<?php include('includes/templates/navbar.php'); ?> 
<!-- end navbar -->

<!-- Start Login -->

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h4 class="text-center"><label>LOGIN TO MANO</label></h4>
    <div class="form-group">
       <input type="text" class="form-control" name='user' placeholder="Enter Username">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name='pass' placeholder="Password">
    </div>
    <!-- Button trigger modal -->
    <span  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#loginModal">Login</span>
    <a href="signup.php"><small id="emailHelp" class="form-text nav-link text-center">Sign Up if you don't have account?</small></a>
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        Are you sure?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>&nbsp;
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>
        </div>
    </div>
</form>



<!-- Finish Login -->

<!-- Footer -->

<?php 

	include('includes/templates/footer.php');

?>



<!--- Script Source Files -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="js/users.js"></script>
<script src="https://use.fontawesome.com/releases/v5.6.1/js/all.js"></script>
<!--- End of Script Source Files -->

</body>
</html>

