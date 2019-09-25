<?php
	$pageTitle = 'Dashboard';

	session_start();

	if(isset($_SESSION['Username'])){
		
		include('includes/templates/header.php');
    include('includes/templates/adminNavbar.php');
    include('includes/functions/connect.php');


    // Get number of products
    $query = $con->prepare("Select  * from Products");
    $query->execute();
    $productNo = $query->rowCount();

    // Get number of Admins
    $query = $con->prepare("Select  * from Users where GroupID=1 or GroupID=2");
    $query->execute();
    $adminNo = $query->rowCount();

      // Get number of Users
      $query = $con->prepare("Select  * from Users where GroupID=0");
      $query->execute();
      $usersNo = $query->rowCount();

		?>

		<!-- Dashboard title -->
		<main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" >Share</a></button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
        </div>
      </div>

    </main>

    
    <!-- Statistic -->
     <!-- Card stats -->
     <div class="row">
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0"> Posts</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $productNo; ?></span>
                    </div>
                    <div class="col-auto my-icon-col">
                      <div class="icon icon-shape bg-gradient-red text-white  ">
                        <img src="img/products.png" alt="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Admins</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $adminNo; ?></span>
                    </div>
                    <div class="col-auto my-icon-col">
                      <div class="icon icon-shape bg-gradient-red text-white  ">
                          <img src="img/admin.png" alt="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Users</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $usersNo; ?></span>
                    </div>
                    <div class="col-auto my-icon-col">
                      <div class="icon icon-shape bg-gradient-red text-white  ">
                          <img src="img/users.png" alt="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Visitors</h5>
                      <span class="h2 font-weight-bold mb-0">122</span>
                    </div>
                    <div class="col-auto my-icon-col">
                      <div class="icon icon-shape bg-gradient-red text-white  ">
                          <img src="img/visitor.png" alt="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

 <!-- Page content -->
 <div class="container-fluid mt--6 my-chart">
 
 <div class="row">
   <div class="col-xl-12">
     <div class="card bg-default">
       <div class="card-header bg-transparent">
         <div class="row align-items-center">
           <div class="col">
             <h6 class=" text-uppercase ls-1 mb-1 text-muted">Overview</h6>
             <h5 class="h3  mb-0">Sales value</h5>
           </div>
           <div class="col">
             <ul class="nav nav-pills justify-content-end">
               <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}' data-prefix="$" data-suffix="k">
                 <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                   <span class="d-none d-md-block">Month</span>
                   <span class="d-md-none">M</span>
                 </a>
               </li>
               <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}' data-prefix="$" data-suffix="k">
                 <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                   <span class="d-none d-md-block">Week</span>
                   <span class="d-md-none">W</span>
                 </a>
               </li>
             </ul>
           </div>
         </div>
       </div>
       <div class="card-body">
         <!-- Chart -->
         <div class="chart">
           <!-- Chart wrapper -->
           <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
<!-- Finish Page content -->

    
	<?php
		include('includes/templates/footer.php');

	} else {
		header("Location: ../../login.php");
		exit();
	}
	
