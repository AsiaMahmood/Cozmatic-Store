<?php 

    $pageTitle = 'Signup';
    include('includes/templates/header.php');
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

    if(isset($accessToken))
    {
        $oAuth2Client = $FB->getOAuth2Client();
        if(!$accessToken->isLongLived())
        {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        }

        $response = $FB->get(  '/me?fields=id,name, first_name,email,birthday,gender', $accessToken);
        $userData = $response->getGraphNode()->asArray();

        $_SESSION['username'] = $userData['first_name'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['fullname'] = $userData['name'];
        $_SESSION['gender'] = $userData['gender'];

    }

    // Check http post request
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
        $password = $_POST['password'];
        $hashedPass = sha1($password);
        $phonNumber = $_POST['phonNumber'];

        // Other infromation from facebook by session
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $fullname = $_SESSION['fullname'];
        $gender = $_SESSION['gender'];

        $query = $con->prepare("SELECT * FROM Users WHERE username='$username' OR email='$email'");
        $query->execute();
        $result = $query->fetch();
        $count = $query->rowCount();

        if($count>0) // This username or email already exist
        {
            if($username === $result['username'])
            {
                echo 'Username Already Exist';
            } elseif ($email === $result['email']) {
                echo 'Email Already Exist';
            }
        } else { // This username or email already doesn't exist
            // Sign up 
                $query = $con->prepare("INSERT INTO 
                                                                users (username , password, email , Fullname, phonNumber, Gender ) 
                                                                    VALUES('$username', '$hashedPass' , '$email' , '$fullname', '$phonNumber' , '$gender' )");
            $query->execute(array($username ,$hashedPass, $email , $fullname , $phonNumber ));
            
            $_SESSION['Username'] = $username;
            // header('location: index.php');
        }
    }

?>
<link rel="stylesheet" href="signup.css">

<form class="Signup" style="height:50%;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h4 class="text-center"><label>SIGN UP WITH FACEBOOK</label></h4>
    <div class="form-group">
       <input type="password" class="form-control" name='password' placeholder="Password">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name='phonNumber' placeholder="Phone Number">
    </div>
    <!-- Button trigger modal -->
    <span  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#loginModal">SIGN UP</span>
        <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">SIGN UP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>&nbsp;
                <button type="submit" class="btn btn-primary">SIGN UP</button>
            </div>
        </div>
    </div>
    </div>
</form>

    <!--- Script Source Files -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/users.js"></script>
    <script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.6.1/js/all.js"></script>
    <!--- End of Script Source Files -->

</body>
</html>
