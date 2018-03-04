<!--<!DOCTYPE html>-->
<!-- Modification of an example by Scott Murray from Knight D3 course -->
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>High Diabetes States - Bar Chart</title>
	
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

		.bar {
			fill: lightblue;
		}
		
        .axis {
            font-size: 13px;
        }
        
        .axis path,
        .axis line {
            fill: none;
            display: none;
        }
        
        .label {
			//fill: white;
            font-size: 13px;
        }
		
		.active {
			background-color: #5f89ad !important;
		}
		
		.ylabel {
			font-famile: sans-serif;
			font-size: 15px;
			color: blue;
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
					<!--<button type="button" class="btn btn-link" id="Total_Adults">Total_Adults</button>-->
					<a href="#" role="button" class="btn btn-link Agbtn age_act" id="Total_Adults">Total_Adults</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="18-44">18-44</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="45-64">45-64</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="65-74">65-74</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="75Plus">75+</a>
				</li>
			</ul>
        </div>
        <!-- Post Content Column -->
        <!--<div class="col-lg-8">-->
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <!-- Title -->
          <h1 class="mt-4">High percent states by age group - Bar Chart</h1>

          <hr>

		  <!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
			<button type="button" class="btn btn-secondary active Yrbtn">2015</button>
			<button type="button" class="btn btn-secondary Yrbtn">2014</button>
			<button type="button" class="btn btn-secondary Yrbtn">2013</button>
			<button type="button" class="btn btn-secondary Yrbtn">2012</button>
			<button type="button" class="btn btn-secondary Yrbtn">2011</button>

          <hr>
			<div id="svg_div">
			</div>
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
		var ag = $(".age_act").text();
		ajaxCall(y, ag)
	});
	
	$(".Agbtn").click(function(){
		$(".Agbtn").removeClass("age_act");
		$(this).addClass("age_act");

		var y = $(".Yrbtn.active").text();
		var ag = $(this).text();
		ajaxCall(y, ag)
	});

function ajaxCall(yr, age) {
	$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/diabetes_age_high_states_per.php',
			data: {
				age: age,
				year: yr
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindHorizontalBarChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
}

$(document).ready(function(){
	ajaxCall('2015', 'Total_Adults');
	return false;
});

function bindHorizontalBarChart(data)
{
        //sort bars based on percentage
        data = data.sort(function (a, b) {
            return d3.ascending(a.percentage, b.percentage);
        })
		//console.log(typeof data);
		//console.log(data);
        //set up svg using margin conventions - we'll need plenty of room on the left for labels
        var margin = {
            top: 15,
            right: 25,
            bottom: 15,
            left: 60
        };

        var width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        var svg = d3.select("#svg_div").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var x = d3.scale.linear()
            .range([0, width])
            .domain([0, d3.max(data, function (d) {
                return d.percentage;
            })]);

        var y = d3.scale.ordinal()
            .rangeRoundBands([height, 0], .1)
            .domain(data.map(function (d) {
                return d.State;
            }));

        //make y axis to show bar names
        var yAxis = d3.svg.axis()
            .scale(y)
            //no tick marks
            .tickSize(0)
            .orient("left");

        var gy = svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)

        var bars = svg.selectAll(".bar")
            .data(data)
            .enter()
            .append("g")

        //append rects
        bars.append("rect")
            .attr("class", "bar")
            .attr("y", function (d) {
                return y(d.State);
            })
            .attr("height", y.rangeBand())
            .attr("x", 0)
            .attr("width", function (d) {
                return x(d.percentage);
            });

        //add a value label to the right of each bar
        bars.append("text")
            .attr("class", "label")
            //y position of the label is halfway down the bar
            .attr("y", function (d) {
                return y(d.State) + y.rangeBand() / 2 + 4;
            })
            //x position is 3 pixels to the right of the bar
            .attr("x", function (d) {
                return x(d.percentage) - 40;
            })
            .text(function (d) {
                return d.percentage + "%";
            });
			
		// Label for the y axis
		svg.append("text")
			.attr("class", "ylabel")
			.attr("transform", "rotate(-90)")
			.attr("y", 0 - margin.left)
			.attr("x",0 - (height / 2))
			.attr("dy", "1em")
			.style("text-anchor", "middle")
			.text("States"); 
			
}
</script>
		        <!-- Footer -->
         <?php include('visualizehealth-footer.inc') ?>
	</body>
</html>