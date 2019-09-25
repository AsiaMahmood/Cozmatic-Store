<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Members</title>

	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/fixed.css">
  <link rel="stylesheet" href="members.css">

</head>

<body>

<?php 

    include('includes/functions/connect.php');

/* 
    =============================================
    == Manage Memders Page
    == You can Add | Edit your profile | Delete Members from here
    =============================================  
*/

session_start();
if( isset($_SESSION['Username'])) {
    
    // include('includes/templates/header.php');

    // Check the navbar for User or Admin
    if( isset($_SESSION['GroupId']) == 0 )
    {

        include('includes/templates/navbar.php');

    } elseif (isset($_SESSION['GroupId']) == 1) {

        include('includes/templates/adminNavbar.php');

    }

    // Check user Type or Group
    if($_SESSION['GroupId']==0){
      $usertype = 'user';
    } elseif ( $_SESSION['GroupId']==1 ) {
      $usertype = 'moderator';
    } elseif ( $_SESSION['GroupId']==2) {
      $usertype = 'admin';
    }
    // Finish navbar
    $do = isset($_GET['do']) ? $_GET['do'] : 'Main';

    // Start Pages
    if($do == 'Main') {

      // Start Main members page
      ?>
      <div class="members">
        <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card text-center">
              <div class="card-body">
                <h5 class="card-title">Admins</h5> <br>
                <a href="?do=Admins" class="btn btn-primary text-center">Admins Page</a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="card text-center">
              <div class="card-body">
                <h5 class="card-title">Moderators</h5> <br>
                <a href="?do=Moderators" class="btn btn-primary text-center">Moderators Page</a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="card text-center">
              <div class="card-body">
                <h5 class="card-title text-center">Users</h5><br>
                <a href="?do=Users" class="btn btn-primary">Users Page</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      // Finish Main members page
    } elseif ($do == 'Moderators') {  

          // Start Moderators Profile
          $query = $con->prepare("Select  * from Users where GroupID = 1 ");                                     
          $query->execute();
          $rows = $query->fetchAll();
          $count = $query->rowCount();
          
          ?>

          <h4 class="text-center head">Manage Moderators</h4>
          <div class="container">
            <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Usename</th>
                    <th scope="col">Email</th>
                    <?php     
                        if($usertype == 'admin')
                        {
                          echo "<th scope='col'>Email</th>";
                        }
                    ?>
                    
                  </tr>
                </thead>
                <tbody>

                <?php
                      foreach($rows as $row){
                          $userid = $row['UserID'];
                          echo '<tr>';
                          echo  "<th scope='row'>" . $row['UserID'] . "</th>";
                          echo "<td>" . $row['Fullname'] . "</td>";
                          echo "<td>" . $row['Username'] . "</td>";
                          echo "<td>" . $row['Email'] . "</td>";

                          if($usertype == 'admin')
                          {
                              echo "<td>" . "<a href='?do=changeToUser&userid=$userid' class='btn btn-primary'>Change to User</a>" . '  ' . "</td>";
                          }
                          echo '</tr>';
                      }
                ?>

                </tbody>
            </table>
          </div>
                    
          <?php
          // Finish Moderators Page 

    } elseif ($do == 'Users') {  

          // Start Users Page
          $query = $con->prepare("Select  * from Users where GroupID = 0 ");
                                                    
          $query->execute();
          $rows = $query->fetchAll();
          $count = $query->rowCount();
            
          ?>

          <h4 class="text-center head">Manage Users</h4>
          <div class="container">
            <table class="table">

              <thead class="thead-dark">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Fullname</th>
                  <th scope="col">Usename</th>
                  <th scope="col">Email</th>
                  <th scope="col">Controls</th>
                </tr>
              </thead>

              <tbody>
                <?php

                      foreach($rows as $row){
                        $userid = $row['UserID'];
                          echo '<tr>';
                          echo  "<th scope='row'>" . $row['UserID'] . "</th>";
                          echo "<td>" . $row['Fullname'] . "</td>";
                          echo "<td>" . $row['Username'] . "</td>";
                          echo "<td>" . $row['Email'] . "</td>";

                          echo "<td>" . "<a href='?do=changeToModerator&userid=$userid' class='btn btn-primary'>Change to Moderator</a>" . '  ' . "<a href='?do=deleteUser&userid=$userid' class='btn btn-danger'>Delete</a>" . "</td>";
                          echo '</tr>';
                      }

                  ?>
              </tbody>
            </table>
          </div>

          <?php
          // Finish Users Page
          
    } elseif($do == 'changeToModerator'){

          // Start Change to Moderators Page
          $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
          $query = $con->prepare("UPDATE Users SET GroupID = 1 WHERE  UserID= $userid" );
          $query->execute(array($userid ));

          header('Location:?do=Users');

          // Finish Change to Moderators Page

    } elseif ( $do == 'deleteUser' ) {

        // Start delete Page

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $query = $con->prepare("DELETE FROM Users WHERE  UserID= :zuser" );
        $query->bindParam(":zuser" , $userid);
        $query->execute();

        header('Location:?do=Users');

        // Finish delete Page

    } elseif ($do == 'Admins') {  

      // Start Admins Profile
      $query = $con->prepare("Select  * from Users where GroupID = 2 ");                                     
      $query->execute();
      $rows = $query->fetchAll();
      $count = $query->rowCount();
      
      ?>

      <div class="container admins">
        <h4 class="text-center head">Manage Admins</h4>
        <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Fullname</th>
                <th scope="col">Usename</th>
                <th scope="col">Email</th>
              </tr>
            </thead>
            <tbody>

            <?php
                  foreach($rows as $row){
                      echo '<tr>';
                      echo  "<th scope='row'>" . $row['UserID'] . "</th>";
                      echo "<td>" . $row['Fullname'] . "</td>";
                      echo "<td>" . $row['Username'] . "</td>";
                      echo "<td>" . $row['Email'] . "</td>";
                      echo '</tr>';
                  }
            ?>

            </tbody>
        </table>
      </div>
                
      <?php
      // Finish Admin Page 

    } elseif($do == 'changeToUser'){

      // Start Change Moderator to User Page
      $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
      $query = $con->prepare("UPDATE Users SET GroupID = 0 WHERE  UserID= $userid" );
      $query->execute(array($userid ));

      header('Location:?do=Moderators');

      // Finish Change to User Page

    }
        // Finish Pages

    include('includes/templates/footer.php');

} else {        

    header('Location:login.php');

}

