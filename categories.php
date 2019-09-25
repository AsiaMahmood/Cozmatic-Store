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

    /* 
    =============================================
    ==Categories Page
    == Add | Edit | Delete
    =============================================
    */

    include('includes/functions/connect.php');

    session_start();
    if( isset($_SESSION['Username'])) {

        // Check the navbar for User or Admin
        if( isset($_SESSION['GroupId']) == 0 )
        {

            include('includes/templates/navbar.php');

        } elseif (isset($_SESSION['GroupId']) == 1) {

            include('includes/templates/adminNavbar.php');

        }
        // Finish navbar

        // *********************************************************
   
        $do = isset($_GET['do']) ? $_GET['do'] : 'Main';

        // Start Pages
        if($do == 'Main') {

          // Start Categories Main Page
          $query = $con->prepare("Select  * from Categories");                                     
          $query->execute();
          $rows = $query->fetchAll();
          $count = $query->rowCount();
          
          ?>

          <h4 class="text-center head">Manage Categories</h4>
          <div class="Main">
            <div class="container">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Control</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        foreach($rows as $row){
                            $ID = $row['CategoryID'];
                            echo '<tr>';
                            echo  "<th scope='row'>" . $row['CategoryID'] . "</th>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . "<a href='?do=Edit&ID=$ID' class='btn btn-primary'>Edit</a>" . '   ' . "<a href='?do=Delete&ID=$ID' class='btn btn-danger' >Delete</a>"  . "</td>";
                            echo '</tr>';
                        }
                    //  Finish Delete Message
                    ?>
                    </tbody>
                </table>
            </div>
          </div>

          <!-- Add new Category -->
          <div class="Add-btn">
              <a class="btn btn-secondary"  href="?do=Add"><i class="fas fa-plus"></i>  Add New Category</a>
          </div>

          <?php
          //Start Categories Main Page

        } elseif ( $do == 'Edit' ) {

            // Start Edit
            $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
            $query = $con->prepare("Select  * from Categories where CategoryID = ".$ID);

            $query->execute(array($ID));
            $row = $query->fetch();
            $count = $query->rowCount();

            if($count>0)
            {
            ?>
            <form class="Edit" action="?do=Update" method="POST">
                <h4 class="text-center"><label>Edit Category</label></h4>
                <div class="form-group">
                <input type="text" class="form-control" name='id' value="<?php echo $row['CategoryID']; ?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name='name' value="<?php echo $row['Name']; ?>">
                </div>
                <!-- Start Message sure -->
                    <!-- Button trigger modal -->
                    <span  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#UpdateModal">Update</span>
                    <!-- Modal -->
                    <div class="modal fade" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Update</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                        Are you sure?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>&nbsp; &nbsp;
                                    <button type="submit" class="btn btn-primary">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Finish Message sure -->
            </form>

            <?php
            } else {
                echo 'there is not such ID';
            }

            // Finish Edit

        } elseif ( $do == 'Update' ) {

            // Start Update Page
           if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $id             =       $_POST['id'];
                $name         =       $_POST['name'];

                $query = $con->prepare("UPDATE Categories SET Name = '$name' WHERE  CategoryID=$id" );
                $query->execute(array($id , $name ));
                
                header('Location:?do=Main');

            }
            // Finish Update Page

        } elseif ( $do == 'Delete' ) {

            // Start Delete Edit
           $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
           
            $query = $con->prepare("DELETE FROM Categories WHERE  CategoryID= :zuser" );
            $query->bindParam(":zuser" , $ID);
            $query->execute();

            header('Location:?do=Main');

            // Finish Delete Edit

        } elseif ( $do == 'Add' ) {

            // Start Add Page
            ?>

            <form class="Add" action="?do=Add" method="POST">
                <h4 class="text-center"><label>Add Category</label></h4>
                <div class="form-group">
                <input type="text" class="form-control" name='name' placeholder="Enter Category Name">
                </div>
                <span  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#AddModal">Add</span>
                <!-- Start Add Message  -->
                <!-- Modal -->

                    <div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                        Are you sure?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>&nbsp; &nbsp;
                                    <button class='btn btn-primary'>Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
               
                <!-- Finish Add Message -->
            </form>

            <?php
           if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = $_POST['name'];
      
            $query = $con->prepare("INSERT INTO Categories(Name) VALUES(:zname)"  );
            $query->bindParam(":zname" , $name);
            $query->execute();

            header('Location:?do=Main');
           }
           // Finish Add Page
        }

        // *********************************************************

        include('includes/templates/footer.php');

    } else {        

        header('Location:login.php');

    }