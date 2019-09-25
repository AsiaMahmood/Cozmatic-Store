<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Products</title>

    <link rel="stylesheet" href="products.css">
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/fixed.css">
</head>
<body>

<?php 

    /* 
    =============================================
    ==Products Page
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

            // Start Main Page
            $query = $con->prepare("Select  * from products");                                     
            $query->execute();
            $rows = $query->fetchAll();
            $count = $query->rowCount();
            
            ?>
  
            <h4 class="text-center head">Manage Products</h4>
            <div class="container">
              <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Name</th>
                      <th scope="col">Price</th>
                      <th scope="col">dtail</th>
                      <th scope="col">Category</th>
                      <th scope="col">Controls</th>
                    </tr>
                  </thead>
                  <tbody>
  
                    <?php
                        foreach($rows as $row){
                            $ID = $row['ProductID'];
                            echo '<tr>';
                            echo  "<th scope='row'>" . $row['ProductID'] . "</th>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['Price'] . "</td>";
                            echo "<td>" . $row['Detail'] . "</td>";

                            // Get Category Name
                            $query = $con->prepare("SELECT  Name FROM Categories WHERE CategoryID =" .$row['CategoryID']);                                     
                            $query->execute();
                            $Category =  $query->fetch();
                            echo "<td>" . $Category['Name'] . "</td>";

                            echo "<td>" . "<a href='?do=Edit&ID=$ID' class='btn btn-primary'>Edit</a>" . '   ' . "<a href='?do=Delete&ID=$ID'  class='btn btn-danger'>Delete</a>";
                            
                            echo   "</td>";
                            echo '</tr>';
                        }
                        //  Finish Delete Message

                    ?>
                 
                    <div id="dialog" title="Attention!" style="display:none">
                        Please enter a domain name to search for.
                    </div>
     
                  </tbody>
              </table>
            </div>
  
            <!-- Add new Product -->
            <div class="Add-btn">
                <a class="btn btn-secondary"  href="?do=Add"><i class="fas fa-plus"></i>  Add New Product</a>
            </div>
  
            <?php
            //Start Main Page
  
        } elseif ( $do == 'Update' ) {

            // Start Update Page
           if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $ID                    =       $_POST['ID'];
                $name              =       $_POST['name'];
                $price               =       $_POST['price'];
                $description     =       $_POST['description'];
                $Category        =       $_POST['Category'];

  
                $query = $con->prepare("update products set Name = '$name' , Price = '$price' , Detail= '$description' ,  CategoryID= $Category  where ProductID = $ID" );
                $query->execute(array( $name, $price , $description , $Category, $ID ));
                
                header('Location:?do=Main');

            }
            // Finish Update Page

        } elseif ( $do == 'Delete' ) {

            // Start Delete 
            $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;
            echo "HHHH : " . $ID;

            $query = $con->prepare("DELETE FROM Products WHERE  ProductID= :zuser" );
            $query->bindParam(":zuser" , $ID);
            $query->execute();

            // header('Location:?do=Main');

            // Finish Delete 

        } elseif ( $do == 'Add' ) {

            // Start Add Page
            ?>
            <div class="Add">
                <form action="?do=Insert" method="POST">
                    <h4 class="text-center"><label>Add New Product</label></h4>
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" name='name' placeholder="Enter Product Name">
                    </div>
                    <div class="form-group">
                    <label class="control-label">Price</label>
                        <input type="text" class="form-control" name='price' placeholder="Enter Product Price">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description</label>
                        <input type="text" class="form-control" name='description' placeholder="Enter Description">
                    </div>
                    
                    <label class="control-label">Select Category</label>
                    <select class="form-control" name="Category">
                        <option>---</option>
                        <?php
                        // Get Category Name
                            $query = $con->prepare("SELECT  * FROM Categories");                                     
                            $query->execute();
                            $Categories =  $query->fetchAll();
                            foreach( $Categories as $Category)
                            {
                                echo "<option value=". $Category['CategoryID']." >" . $Category['Name'] . "</option>";
                            }
                        ?>
                    </select>
                    <br>
                    <div class="form-group">
                        <input type="file" class="form-control-file " id="file" name="imageUrl">
                        <label class="file-label " for="file"><i class="fas fa-plus"></i> Choose Image</label>
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
            </div>
            <?php

            // Finish Add Page
        } elseif ( $do == 'Insert' ) {

            // Start Insert Product 

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $name             =       $_POST['name'];
                $price              =       $_POST['price'];
                $description    =       $_POST['description'];
                $Category       =       $_POST['Category'];
                $imageUrl       =       $_POST['imageUrl'];
                echo $name . "   " . $price . "   " . $Category;

                // Message Errors
                $messageError = array();

                if(empty($name)){

                    $messageError[] = 'Name can not be empty ';

                } elseif (empty($price)) {

                    $messageError[] = 'Price can not be empty ';

                } elseif (empty($imageUrl)) {

                    $messageError[] = 'Image can not be empty ';

                }
            
                // Insert new Product if there is not any error
                if(empty($messageError)){
                    $query = $con->prepare("INSERT INTO 
                                                                        Products (Name , Price , Detail , imgUrl , CategoryID) 
                                                                        VALUES ( :zname , :zprice , :zdescription , :zimageUrl , :zCategory)" );
                    
                    $query->execute(array(
                                                'zname'               => $name, 
                                                'zprice'                =>   $price , 
                                                'zdescription'      =>$description , 
                                                'zimageUrl'         =>$imageUrl, 
                                                'zCategory'         =>$Category 
                                            ));
                }   // Finish Insertion

            }
                
            header('Location:?do=Main');
            // Finish Insert Product
        } elseif ( $do == 'Edit' ) {

            // Start Edit
            $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

            $query = $con->prepare("Select  * from Products where ProductID = ".$ID);
            $query->execute(array($ID));
            $row = $query->fetch();
            $count = $query->rowCount();
                
            if($count>0)
            {
                ?>
                <div class="Add">
                    <form action="?do=Update" method="POST">
                        <h4 class="text-center"><label>Edit <?php echo $row['Name']; ?></label></h4>
                        
                        <input type="hidden" class="form-control" name='ID' value="<?php echo $row['ProductID']; ?>">
 
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" class="form-control" name='name' value="<?php echo $row['Name']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Price</label>
                            <input type="text" class="form-control" name='price' value="<?php echo $row['Price']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <input type="text" class="form-control" name='description' value="<?php echo $row['Detail']; ?>">
                        </div>
                        
                        <label class="control-label">Select Category</label>
                        <select class="form-control" name="Category">
                      
                            <?php
                            // Get Category Name
                                $query = $con->prepare("SELECT  * FROM Categories");                                     
                                $query->execute();
                                $Categories =  $query->fetchAll();
                                foreach( $Categories as $Category)
                                {
                                    if($Category['CategoryID'] == $row['CategoryID']){
                                     
                                        echo "<option value=". $Category['CategoryID'].">" . $Category['Name'] . "</option>";
                                    }
                                }

                                foreach( $Categories as $Category)
                                {
                                    if(!($Category['CategoryID'] == $row['CategoryID']))
                                    {
                                        echo "<option value=". $Category['CategoryID'].">" . $Category['Name'] . "</option>";
                                    }
                                }
                            ?>
                        </select>
                        <br>
                        <div class="form-group">
                            <input type="file" class="form-control-file " id="file" name="imageUrl">
                            <label class="file-label " for="file"><i class="fas fa-plus"></i> Choose Image</label>
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
                    </div>
                <?php
             } else {
                 echo 'there is not such ID';
            }

            // Finish Edit

        }

        // *********************************************************

        include('includes/templates/footer.php');

    } else {        

        header('Location:login.php');

    }
 