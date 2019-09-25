<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Members</title>

	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/fixed.css">
  <link rel="stylesheet" href="profile.css">

</head>

<body>

<?php 

 /* 
    =============================================
    ==Profile Page
    == You can Edit | Update
    =============================================
*/

    include('includes/functions/connect.php');

    $pageTitle = 'Profile';
    $addCss = 'profile.css';

    session_start();
    if( isset($_SESSION['Username'])) {


        // Check the navbar for User or Admin
        $query = $con->prepare("Select * from Users where Username='". $_SESSION['Username'] ."'");
        $query->execute(array($_SESSION['Username']));
        $row = $query->fetch();
        $count = $query->rowCount();

        $GroupID = $row['GroupID'];
        $userid = $row['UserID'];
        if($GroupID ==0 )
        {
            include('includes/templates/navbar.php');

        } elseif ($GroupID==1 || $GroupID==2) {

            include('includes/templates/adminNavbar.php');

        }
        // Finish navbar

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // Start Manage Page
        if($do == 'Manage') {

            // Manage Page

        } 
        elseif ($do == 'Edit') {                                   // Start Edit Profile
        
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
 
            $query = $con->prepare("Select  * from Users where UserID = ".$userid);
                                           
            $query->execute(array($userid));
            $row = $query->fetch();
            $count = $query->rowCount();
            if($count>0)
            {
                ?>

                <form class="Edit" action="?do=Update" method="POST">
                        <h4 class="text-center"><label>Edit Your Profile</label></h4>

                        <input type="hidden" name='userid' value="<?php echo $userid?>">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" name='fullname' value="<?php echo $row['Fullname']; ?>" required="required">
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name='user' value="<?php echo $row['Username']; ?>" required="required">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="hidden" name='oldPassword' value="<?php echo $row['Password']; ?>">
                            <input type="password" class="form-control" name='newPassword' value="">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name='email' value="<?php echo $row['Email']; ?>" required="required">
                        </div>
                        <button type="submit" class="btn btn-secondary btn-block">Update</button>
                </form>

                <?php   
            } else {
                echo 'there is not such ID';
            }
            // Finish Edit Profile
        }   elseif ($do == 'Update') {  
            // Start Update Profile

                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $userid             =       $_POST['userid'];
                        $fullname         =       $_POST['fullname'];
                        $username       =       $_POST['user'];
                        $email              =       $_POST['email'];

                        // Password Trick
                        $password ='';
                        if(empty($_POST['newPassword'])){
                            $password = $_POST['oldPassword'];
                        } else {
                            $password = sha1($_POST['newPassword']);
                        }

                         // Message Errors
                        $messageError = array();

                        if(!empty($username)){

                            // Check username length
                            if(strlen($username) < 4){
                                
                            $messageError[] = 'Username must be more than 4 ';

                            } elseif (strlen($username) > 20 ) {
                            
                            $messageError[] = 'Username must be less than 20 ';

                        } elseif (empty($username)) {

                            $messageError[] = 'Username can not be empty ';

                        } elseif (empty($email)) {

                            $messageError[] = 'email can not be empty ';

                        } elseif (empty($fullname)) {

                            $messageError[] = 'fullname can not be empty ';

                        }
                    
                        
                        // Update if there is not any error
                        if(empty($messageError)){

                            $query = $con->prepare("UPDATE Users SET Fullname = '$fullname' , Username = '$username' , Password = '$password' ,  Email = '$email' WHERE  UserID= $userid" );
                            $query->execute(array($username , $email , $fullname , $password, $userid ));
                
                        }
                    }
                        
                }
            
                header("Location:?do=Edit&userid=$userid");
                  
            // Finish Update Profile
        }                                                       

        include('includes/templates/footer.php');

    } else {        

        header('Location:login.php');

    }