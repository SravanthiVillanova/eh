<?php
include_once 'src/vizHealth_db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="refresh" content="0; URL:http://localhost:8080/Final Project/index.php">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Visualize Health Conditions</title>
  
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Custom Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script> 

    <!-- Custom CSS -->
    <link href="css/visualizehealth - style.css" rel="stylesheet">
    
    	<!-- Btn CSS -->
	  <!--<link rel="stylesheet" href="css/spritebtn.css">  -->
<style>
.business-header {
  height: 50vh;
  min-height: 500px;
  background: url('images/home1.jpg') center center no-repeat scroll;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  background-size: cover;
  -o-background-size: cover;
}
</style> 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

   <!-- Navigation header -->
   <?php include('visualizehealth-header.inc') ?>
   
    
    <!-- Header with Background Image -->
    <header class="business-header">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="display-3 text-center text-white mt-4">Health Data Visualization</h1>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <div class="container">

        <!-- Marketing Icons Section -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Public Health Data Visualization
                </h1>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-check"></i> Diabetes - Fun Facts </h4>
                    </div>
                    <div class="panel-body">				
						<p>
                            <ul class="featured-list">
                            <li>Diabetes is the seventh leading cause of death in the United States.</li>                            
                            <li>29.1 million people in the United States have diabetes which is about 9.3% of the population. <br/>Of this, 8.1 million are undiagnosed and unaware</li>
                            <li>The CDC projects that one in three adults could have diabetes by 2050.</li>
                        </ul>
                        </p>
						<br/><br/>
                        <a href="diabetes.php" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-check"></i> States - Obesity Rates </h4>
                    </div>
                    <div class="panel-body">   
                        <p>
                            <ul class="featured-list">
                            <li>Nine of the 11 states with the highest obesity rates are in the South</li>                            
                            <li>Nationally, nearly 38 percent of adults are obese.<br/>Nearly 8 percent are extremely obese.</li>
                            <li>Colorado has the lowest obesity rate (22.3%) and the lowest rate of physical inactivity (15.8%)</li>
                        </ul>
                        </p>
						<br/><br/><br/>
                        <a href="obesity.php" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-check"></i> Medical Definitions </h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <ul class="featured-list">
                            <li>Obesity is a BMI (Body Mass Index) of 30 and above. A BMI of 30 is about 30 pounds overweight.</li>                            
                            <li>Type 1 diabetes, also known as insulin-dependent diabetes, is a chronic condition in which the pancreas produces little or no insulin.</li>
                            <li>Type 2 diabetes, also known as noninsulin-dependent diabetes, is a chronic condition that affects the way body metabolizes sugar (glucose), body's important source of fuel.</li>
                        </ul>
                        </p>
                        <a href="index.php" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <!-- Footer -->
         <?php include('visualizehealth-footer.inc') ?>
    </div>
    <!-- /.container -->
    
</body>

</html>
