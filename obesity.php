<!--<!DOCTYPE html>-->
<!-- Modification of an example by Scott Murray from Knight D3 course -->
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Line Chart with Multiple Lines</title>
	
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />	
	
	<!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Custom Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Custom CSS -->
    <link href="css/visualizehealth - style.css" rel="stylesheet">
	<link href="css/page - style.css" rel="stylesheet">
	
	<!-- d3.js -->
		<!--<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
		<style type="text/css">

			body {
				background-color: white;
				font-family: Helvetica, Arial, sans-serif;
			}

			h1 {
				font-size: 24px;
				margin: 0;
			}

			p {
				font-size: 14px;
				margin: 10px 0 0 0;
				width: 700px;
			}
			.active {
				background-color: #5f89ad !important;
			}
		</style>
</head>
<body>
   <!-- Navigation header -->
   <?php include('visualizehealth-header.inc') ?>

 <!-- Page Content -->
    <div class="container">

      <div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="active">
					<a href="obesity.php" role="button" class="btn btn-link" id="diabetes_page">Obesity</a>
				</li>
				<li>
					<a href="obesity_age_lineChart.php" role="button" class="btn btn-link" id="line_chart">All States Line Chart</a>
				</li>
				<li>
					<a href="obesity_age_per_bar_chart.php" role="button" class="btn btn-link" id="vert_bar">Age and Year wise Bar Chart</a>
				</li>
				<li>
					<a href="obesity_age_highh_states_per.php" role="button" class="btn btn-link" id="hor_bar">Highest Percent States</a>
				</li>
				<li>
					<a href="obesity_age_per_stack_chart.php" role="button" class="btn btn-link" id="stack_chart">All States Stack Chart</a>
				</li>
			</ul>
        </div>
        <!-- Post Content Column -->
        <!--<div class="col-lg-8">-->
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <!-- Title -->
          <h1 class="mt-4">Obesity Rate by State</h1>
		    <hr>
				<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
				<button type="button" class="btn btn-secondary active Yrbtn">2016</button>		  
				<button type="button" class="btn btn-secondary Yrbtn">2015</button>
				<button type="button" class="btn btn-secondary Yrbtn">2014</button>
				<button type="button" class="btn btn-secondary Yrbtn">2013</button>
				<button type="button" class="btn btn-secondary Yrbtn">2012</button>
				<button type="button" class="btn btn-secondary Yrbtn">2011</button>
			<hr>
			<div id="svg_div">
				<!--<img src="images/adult_obesity_overall_2016.jpg" alt="Overall Adult Obesity Rate in USA-2016" />-->
			</div>
			<p style="font-size:7px">Source: 
				<a href="https://stateofobesity.org/adult-obesity/">https://stateofobesity.org/adult-obesity/</a>
			</p>
        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
	<script>
	$(".nav-sidebar a").on("click", function(){
		$(".nav-sidebar").find(".active").removeClass("active");
		$(this).parent().addClass("active");
	});
	
	$(".Yrbtn").click(function(){
		$(".Yrbtn").removeClass("active");
		$(this).addClass("active");

		var y = $(this).text();
		//var ag = $(".age_act").text();
		imgCall(y)
	});
	
	function imgCall(y) {
		$("#svg_div").html('');
		var img_path = 'images/obesity_adults_' + y + '.png';
		var image = $('<img src="' + img_path + '" />');

		image.appendTo("#svg_div");
	}
	
	$(document).ready(function(){
		imgCall('2016');
		return false;
	});
	</script>
		<!-- Footer -->
        <?php include('visualizehealth-footer.inc') ?>
	</body>
</html>