<?php

    include('includes/functions/connect.php');
 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    
    if(isset($_SESSION['Username'])) {
        header('Location:index.php');
        exit();
    }

    // Facebook registeration

    require_once 'includes/functions/facebook.php';

    $redirectURL = 'http://localhost:8080/Mano/fa-callback.php';
    $redirectSignupURL = 'http://localhost:8080/Mano/fa-callback-signup.php';
    $permissions = ['email'];
    $loginURL  =  $helper->getLoginUrl($redirectURL,$permissions);
    $signupURL = $helper->getLoginUrl($redirectSignupURL,$permissions);
 
    // ---------------------------
    //  Variables
    // ---------------------------
    $usernameErr = $passwordErr = $emailErr = $fullnameErr = $phonNumberErr = $genderErr =$phonNoErr = $usernameErrSignin= $passwordErrSignin = '';
    $Errors = array();
    $ErrorsSignin = array();
    // Check http post request
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $form  = $_POST['form'];

        // -------------------------
        // Signin Form
        // -------------------------

        if($form === 'SigninForm')
        {
            // Username
            if(empty($_POST['username'])){
                $usernameErrSignin = 'Name is required';
                $ErrorsSignin = 'Name is required';
            } else{
                $username = test_input($_POST['username']);
            }

            // Password
            if(empty($_POST['password'])){
                $usernameErrSignin = 'Password is required';
                $ErrorsSignin = 'password is required';
            } else{
                $password = $_POST['password'];
                $hashedPass = sha1($password);
            }
            
            if(!$ErrorsSignin)
            {
                $query = $con->prepare("Select 
                                                                    UserID, GroupID, Username,Password 
                                                            from 
                                                                    Users 
                                                            where 
                                                                    Username= '". $username ."' 
                                                            AND 
                                                                    Password= '". $hashedPass. "' 
                                                            ");
                $query->execute(array($username,$hashedPass));
                $row = $query->fetch();
                $count = $query->rowCount();
            }
            if($count)
            {
                $_SESSION['Username'] = $username;
                $_SESSION['ID'] = $row['UserID'];
                $_SESSION['GroupId'] = $row['GroupID'];
                header("Location:index.php");
            }
        }  

        // ------------------------------------------------
        // Sign Up Form
        // ------------------------------------------------

        if($form === 'SignupForm')
        {
            // Username 
            if(empty( $_POST['username'])){
                $usernameErr = "Username is required";
                $Errors[] = "Username is required";
            } else {
                $username = test_input($_POST['username']);
            }

            // Password
            if(empty( $_POST['password']))
            {
                $passwordErr = "Password is required";
                $Errors[] = "Password is required";
            } 
            else 
            {
                $password = $_POST['password'];
                $hashedPass = sha1($password);
            }

            // Email
            if(empty( $_POST['email'])){
                $emailErr = "Email is required";
                $Errors[] = "Email is required";
            } else {
                $email = test_input($_POST['email']);
            }

            // Fullname
            if(empty( $_POST['fullname'])){
                $fullnameErr = "Fullname is required";
                $Errors[] = "Fullname is required";
            } else {
                $fullname = test_input($_POST['fullname']);
            }

            // Gender 
            if(empty( $_POST['gender'])){
                $genderErr = "Gender is required";
                $Errors[] =  "Gender is required";
            }else{
                $gender = $_POST['gender'];
            }

            // phonNumber 
            if(empty( $_POST['phonNumber'])){
                $phonNoErr = "Phone Number is required";
                $Errors[] = "Phone Number is required";
            }else{
                $phonNo = $_POST['phonNumber'];
            }
         

            // first check the database to make sure 
            // a user does not already exist with the same username and/or email
            $query = $con->prepare("SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1");
            $result = $query->fetch();
            $count = $query->rowCount();

            if ($count) { // if user exists
                if ($result['username'] === $username) {
                echo "Username already exists";
                }
            
                if ($result['email'] === $email) {
                echo "email already exists";
                }
            } else {
                // Sign Up to Users table
                if(!$Errors)
                {
                    $query = $con->prepare("INSERT INTO 
                                                                            users (username, password , email , Fullname , phonNumber , gender) 
                                                                            VALUES('$username', '$hashedPass', '$email' , '$fullname' , '$phonNo' , '$gender')");
                    $query->execute(array($username , $hashedPass , $email , $fullname , $phonNo,$gender ));
                    
                    $_SESSION['Username'] = $username;
                    header('location: index.php');
                }
            }
            
        } 
    }
    // Test input data 
    function test_input($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up</title>

	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

</head>

<body>
        <div class="container_SignUp">
         <!-- Sign In - Hero Image -->
            <div class="heroSignInBackground">
                <div class="heroSignInBackgroundOverlay">
                    <div class="containerHeroSignIn">
                        <div class="signInContent">
                            <h1>Hello friend, welcome!</h1>
                            <p>Login your details to start<br>your journey.</p>
                        </div> 
                        <div class="heroSignInButton">   
                            <button class="buttonHeroSignIn" onclick="signIn()">SIGN IN</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign Up - Form --> 
            <div class="containerSignUp">
                <div class="socialIcons">
                    <h1>Create Account</h1>
                    <button href="#" class="social" onclick="window.location = '<?php echo $signupURL; ?>'"><i class="fab fa-facebook-f"></i></button>
                    <button href="#" class="social"><i class="fab fa-google"></i></button>
                </div>
                <div class="containerFormSignUp">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <span class ="title">or use your email for registration:</span>
                        <input type="hidden" name='form' value='SignupForm'>

                        <div class="rowInput">
                            <span class='starRow'>* </span>
                            <input type="text" name='username' placeholder="Username"/>
                            <span class='starRow'>* </span>
                            <input type="password" name='password' placeholder="Password" />
                        </div>
                        <span class="Error"><?php echo $usernameErr . " " . $passwordErr;   ?></span>

                        <span class='star'>*</span>
                        <input type="email" name='email' placeholder="Email"/>
                        <span class="Error"><?php echo $emailErr; ?></span>
                        
                        <span class='star'>*</span>
                        <input type="text" name='fullname' placeholder="Fullname"/>
                        <span class="Error"><?php echo $fullnameErr; ?></span>
                         
                        <span class="star">* </span>
                        <input type="text" name='phonNumber' placeholder="Phone number"/> <br>
                        <span class="Error"><?php echo $phonNoErr; ?></span>

                        <div class="rowInput radioRow">
                            <input type="radio" name="gender" value="male">Male
                            <input type="radio" name="gender"  value="female">Female
                        </div>
                        <span class="Error"><?php echo $genderErr; ?></span>
                        <br>
                        <button  class="buttonFormSignUp">SIGN UP</button>
                    </form>
                </div>
           </div>
        </div>
        <div class="container_SignIn" id="container_SignIn">
            <!-- Sign In - Form -->
            <div class="containerSignIn">
                <div class="socialIcons">
                    <h1>Sign In</h1>
                    <button href="#" class="social" onclick="window.location = '<?php echo $loginURL; ?>'"><i class="fab fa-facebook-f"></i></button>
                    <button href="#" class="social" ><i class="fab fa-google"></i></button>
                </div>
                <div class="containerFormSignIn">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <span class ="title">or use your email account:</span>
                        <input type="hidden" name='form' value='SigninForm'>

                        <span class="star">* </span>
                        <input type="text" name='username' placeholder="Username" />
                        <span class='Error'><?php echo $usernameErrSignin; ?></span>

                        <span class="star">*</span>
                        <input type="password" name="password" placeholder="Password" />
                        <span class='Error'><?php echo $passwordErrSignin; ?></span>

                        <a href="#">forgot your password?</a>
                        <!-- Button trigger modal -->
                        <div class="formSignInButton">
                            <button class="buttonFormSignIn">SIGN IN</button>
                        </div>
                        
                    </form>
                </div>
            </div>
            <!-- Sign Up - Hero Image -->
            <div class="heroSignUpBackground" id="containerHeroSignUp">
                <div class="heroSignUpBackgroundOverlay">
                    <div class="containerHeroSignUp">
                        <div class="signUpContent">
                            <h1>Not a member?</h1>
                            <p>Enter your personal details<br>and start your journey.</p>
                        </div> 
                        <div class="heroSignUpButton" onclick="signUp()">   
                            <button class="buttonHeroSignUp">SIGN UP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>     
			
	<!--- Script Source Files -->
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/users.js"></script>
	<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v5.6.1/js/all.js"></script>
	<!--- End of Script Source Files -->

</body>
