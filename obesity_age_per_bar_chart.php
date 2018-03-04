<!--<!DOCTYPE html>-->
<!-- Modification of an example by Scott Murray from Knight D3 course -->
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Horizontal bar chart - Obesity</title>	
	
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
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>	
<style>

body {
  font: 10px sans-serif;
  
}

.vertical-text {
	transform: rotate(-90.0deg);
}
  
.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}

.bar {
  fill: lightblue;
}

.bar:hover {
  fill: orange ;
}


.d3-tip {
  line-height: 1;
  font-weight: bold;
  padding: 12px;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  border-radius: 2px;
}

/* Creates a small triangle extender for the tooltip */
.d3-tip:after {
  box-sizing: border-box;
  display: inline;
  font-size: 10px;
  width: 100%;
  line-height: 1;
  color: rgba(0, 0, 0, 0.8);
  content: "\25BC";
  position: absolute;
  text-align: center;
}

/* Style northward tooltips differently */
.d3-tip.n:after {
  margin: -1px 0 0 0;
  top: 100%;
  left: 0;
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
					<a href="#" role="button" class="btn btn-link Agbtn age_act" id="Total_Adults">Total_Adults</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="18-24">18-24</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="25-34">25-34</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="35-44">35-44</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="45-54">45-54</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="55-64">55-64</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Agbtn" id="65Plus">65+</a>
				</li>
			</ul>
			<label><input type="checkbox"> Sort by values</label>
        </div>
        <!-- Post Content Column -->
        <!--<div class="col-lg-8">-->
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <!-- Title -->
          <h3 class="mt-4">Obesity by age group - Bar Chart</h1>

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
			url: 'src/obesity_age_per_bar_chart.php',
			data: {
				age: age,
				year: yr
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindVerticalBarChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
}

$(document).ready(function(){
	ajaxCall('2016', 'Total_Adults');
	return false;
});

function bindVerticalBarChart(data)
{	
	var margin = {top: 15, right: 25, bottom: 15, left: 60};
    var width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

var formatPercent = d3.format(".0%");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    //.tickFormat(formatPercent);

var tip = d3.tip().attr('class', 'd3-tip')
                  .offset([-10, 0])
                  .html(function(d) {
						return "<strong>"+d.abbr+":</strong> <span style='color:red'>" + d.percentage + "</span>";
					});

var svg = d3.select("#svg_div").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
	.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

  x.domain(data.map(function(d) { return d.abbr; }));
  //y.domain(d3.extent(data.map(function(d) { return d.percentage;} )));
  y.domain([0, d3.max(data, function(d) { return +d.percentage; })]);
  //y.domain([0, 15]);
	  
	var gXAxis = svg.append("g")
      .attr("class", "x axis")
      //.attr("transform", "translate(0," + height + ")")
      .call(xAxis)
	
	gXAxis.selectAll("text")  
      .style("text-anchor", "end")
      .attr("dx", "-.8em")
      .attr("dy", ".15em")
      .attr("transform", "rotate(-65)" );

	// Find the maxLabel height, adjust the height accordingly and transform the x axis.
	var maxWidth = 0;
	gXAxis.selectAll("text").each(function () {
		var boxWidth = this.getBBox().width;
		if (boxWidth > maxWidth) maxWidth = boxWidth;
	});
	height = height - maxWidth;

	gXAxis.attr("transform", "translate(0," + height + ")");

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
	  .append("text")
      .attr("y", -15)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Percent");

  svg.selectAll(".bar")
      .data(data)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.abbr); })
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d.percentage); })
      .attr("height", function(d) { return height - y(d.percentage); })
  		
      .on('mouseover', tip.show)
      .on('mouseout', tip.hide)

	  
d3.select("input").on("change", change);

  /*var sortTimeout = setTimeout(function() {
    d3.select("input").property("checked", true).each(change);
  }, 2000);*/

  function change() {
    //clearTimeout(sortTimeout);

    // Copy-on-write since tweens are evaluated after a delay.
    var x0 = x.domain(data.sort(this.checked
        ? function(a, b) { return b.percentage - a.percentage; }
        : function(a, b) { return d3.ascending(a.abbr, b.abbr); })
        .map(function(d) { return d.abbr; }))
        .copy();

    svg.selectAll(".bar")
        .sort(function(a, b) { return x0(a.abbr) - x0(b.abbr); });

    var transition = svg.transition().duration(750),
        delay = function(d, i) { return i * 50; };

    transition.selectAll(".bar")
        .delay(delay)
        .attr("x", function(d) { return x0(d.abbr); });

    transition.select(".x.axis")
        .call(xAxis)
      .selectAll("g")
        .delay(delay);
  }
//});
}
</script>
<br/>
<br/>
<!-- Footer -->
<?php include('visualizehealth-footer.inc') ?>
	</body>
</html>