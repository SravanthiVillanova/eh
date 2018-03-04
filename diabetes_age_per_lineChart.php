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
	<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
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

			svg {
				background-color: white;
			}

			path.highlight {
				fill: none;
				stroke: red;
				stroke-width: 3px;
			}			
			
			path.line {
				fill: none;
				stroke: gray;
				stroke-width: 0.5px;
				stroke-opacity: 80%;
			}

			.unfocused {
				stroke: gray;
				stroke-width: 1px;
				stroke-opacity: 0.8;
			}

			path.line.focused {
				stroke: orange;
				stroke-opacity: 1;
				stroke-width: 2px
			}

			.axis path,
			.x axis,
			.y axis,
			.axis line {
				fill: none;
				stroke: black;
				stroke-width: 1px;
				shape-rendering: crispEdges;
			}

			.axis text {
				font-family: sans-serif;
				font-size: 11px;
			}

			text.linelabel {
				font-size: 9pt;
				color: gray;
			}

			circle {
				fill: orange;
			}

			.tooltip {
				position: absolute;
				z-index: 10;
			}

			.tooltip p {
				background-color: white;
				border: gray 1px solid;
				padding: 2px;
				max-width: 180px;
			}
			
			.xlabel {
				font-famile: sans-serif;
				font-size: 15px;
				color: black;
			}

			.ylabel {
				font-famile: sans-serif;
				font-size: 15px;
				color: blue;
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
					<a href="#" role="button" class="btn btn-link" id="Total_Adults">Total_Adults</a>
				</li>
				<li>
					<!--<button type="button" class="btn btn-link" id="18-44">18-44</button>-->
					<a href="#" role="button" class="btn btn-link" id="18-44">18-44</a>
				</li>
				<li>
					<!--<button type="button" class="btn btn-link" id="45-64">45-64</button>-->
					<a href="#" role="button" class="btn btn-link" id="45-64">45-64</a>
				</li>
				<li>
					<!--<button type="button" class="btn btn-link" id="65-74">65-74</button>-->
					<a href="#" role="button" class="btn btn-link" id="65-74">65-74</a>
				</li>
				<li>
					<!--<button type="button" class="btn btn-link" id="75Plus">75+</button>-->
					<a href="#" role="button" class="btn btn-link" id="75Plus">75+</a>
				</li>
			</ul>
        </div>
        <!-- Post Content Column -->
        <!--<div class="col-lg-8">-->
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <!-- Title -->
          <h1 class="mt-4">Diabetes by Age group - Line Chart</h1>

          <hr>

          <!-- Date/Time -->
          <p>Posted on January 1, 2017 at 12:00 PM</p>

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
$(document).ready(function(){
   $.ajax({
        method: 'post',
        url: 'src/diabetes_age_per_line.php',
        data: {
            age: 'Total_Adults'
        },
        dataType: "json",
        cache: false,
        success: function(data) {
			bindMultiLineChart(data);
        },
        error: function() {
			console.log("ajax-error");
        }
    });

    $("#Total_Adults").on('click', function() {
		//alert("clicked");
		$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/diabetes_age_per_line.php',
			data: {
				age: 'Total_Adults'
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindMultiLineChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
		return false;
	});
	
    $("#18-44").on('click', function() {
		//alert("clicked");
		$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/diabetes_age_per_line.php',
			data: {
				age: '18-44'
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindMultiLineChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
		return false;
	});

	$("#45-64").on('click', function() {
		//alert("clicked");
		$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/diabetes_age_per_line.php',
			data: {
				age: '45-64'
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindMultiLineChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
		return false;
	});

	$("#65-74").on('click', function() {
		//alert("clicked");
		$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/diabetes_age_per_line.php',
			data: {
				age: '65-74'
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindMultiLineChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
		return false;
	});

	$("#75Plus").on('click', function() {
		//alert("clicked");
		$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/diabetes_age_per_line.php',
			data: {
				age: '75+'
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindMultiLineChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
		return false;
	});
	return false;
});

function bindMultiLineChart(data)
{
	//Dimensions and padding
			var fullwidth = 700;
			var fullheight = 450;
			var margin = {top: 20, right: 100, bottom: 40, left:100};

			var width = fullwidth - margin.left - margin.right;
			var height = fullheight - margin.top - margin.bottom;

			//Set up date formatting and years
			var dateFormat = d3.time.format("%Y");

			//Set up scales
			var xScale = d3.time.scale()
								.range([ 0, width ]);

			var yScale = d3.scale.linear()
								.range([ 0, height ]);

			//Configure axis generators
			var xAxis = d3.svg.axis()
							.scale(xScale)
							.orient("bottom")
							.ticks(5)
							.tickFormat(function(d) {
								return dateFormat(d);
							})
							.outerTickSize([0])
							.innerTickSize([0]);

			var yAxis = d3.svg.axis()
							.scale(yScale)
							.orient("left")
							.outerTickSize([0]);

			//Configure line generator
			// each line dataset must have a d.year and a d.amount for this to work.
			var line = d3.svg.line()
				.defined(function(d) { return d.percent !== "0"; })
				.x(function(d) {
					return xScale(dateFormat.parse(d.year));
				})
				.y(function(d) {
					return yScale(+d.percent);
				});

			//Create the empty SVG image
			var svg = d3.select("#svg_div")
						.append("svg")
						.attr("width", fullwidth)
						.attr("height", fullheight)
						.append("g")
						.attr("transform", "translate(" + margin.left + "," + margin.top + ")");


			var tooltip = d3.select("#svg_div")
      	                    .append("div")
      	                    .attr("class", "tooltip");
			

			//Load data
            //d3.json("src/diabetes-age-adults-per.php", function(error, data) {
				//Data is loaded in, but we need to restructure it.
				//structured like this:
				/*
					[
						{
							state: "AL",
							percent: [
										{ year: 2011, amount: 9.5 },
										{ year: 2012, amount: 8.7 },
										{ year: 2013, amount: 9.2 },
										…
									   ]
						},
				*/

				// or you could get this by doing:
				var years = d3.keys(data[0]).slice(0, 9-4); //

				//Create a new, empty array to hold our restructured dataset
				var dataset = [];

				//Loop once for each row in data
				data.forEach(function (d, i) {

					var statePercent = [];

					//Loop through all the years - and get the percentage for this data element
					years.forEach(function (y) {

						// If value is not empty
						if (d[y]) {
							//Add a new object to the new emissions data array - for year, amount
							statePercent.push({
								state: d.abbr, // this isn't being used for the line but won't hurt
								year: y,
								percent: d[y]  // this is the value for, for example, d["2004"]
 							});
						}

					});

					//Create new object with this country's name and empty array
					// d is the current data row... from data.forEach above.
					dataset.push( {
						state: d.abbr,
						diabetes_per: statePercent  // we just built this!
						} );

				});

				//Uncomment to log the original data to the console
				// console.log(data);

				//Uncomment to log the newly restructured dataset to the console
				//console.log(dataset);


				//Set scale domains - max and mine of the years
				xScale.domain(
					d3.extent(years, function(d) {
						return dateFormat.parse(d);
					}));

				// max of emissions to 0 (reversed, remember)
				yScale.domain([
					d3.max(dataset, function(d) {
						return d3.max(d.diabetes_per, function(d) {
							return +d.percent;
						});
					}),
					0
				]);


				//Make a group for each country
				var groups = svg.selectAll("g")
					.data(dataset)
					.enter()
					.append("g");

				//Within each group, create a new line/path,
				//binding just the emissions data to each one
				groups.selectAll("path")
					.data(function(d) { // because there's a group with data already...
						return [ d.diabetes_per ]; // it has to be an array for the line function
					})
					.enter()
					.append("path")
					.attr("class", function (d) {
						if (d[0]['state'] === "USA") {
							return "highlight";
						} else {
							return "line";
						}
					})					
					//.attr("class", "line")
					.classed("unfocused", true) // they are not focused till mouseover
					.attr("id", function(d) {
						// we are attaching an id to the line using the countryname, replacing
						// the spaces with underscores so it's a valid id.
						// this will be useful when we do the mouseover and want to highlight a line too.
						if (d[0] && d[0].length != 0) {
							// this if-test makes sure there is an array and it's not empty.
							return d[0].state.replace(/ |,|\./g, '_');
						}
					})
					.attr("d", line);
			
			// Prep the tooltip bits, initial display is hidden
	var tip = d3.tip().attr('class', 'd3-tip')
                  .offset([-10, 0])
                  .html(function(d) {
						return "<strong>"+d.state+":</strong> <span style='color:red'>" + d.percent + "</span>";
					});
	svg.call(tip);
	//state.selectAll("rect")
	      //.on('mouseover', tip.show)
          //.on('mouseout', tip.hide);
			
			// Tooltip dots
			var circles = groups.selectAll("circle")
								.data(function(d) { // because there's a group with data already...
											return d.diabetes_per; // NOT an array here.
								})
								.enter()
								.append("circle");

				circles.attr("cx", function(d) {
						return xScale(dateFormat.parse(d.year));
					})
					.attr("cy", function(d) {
						return yScale(d.percent);
					})
					.attr("r", 3)
					.attr("id", function(d) {
						return d.state.replace(/ |,|\./g, '_');
					})
					.style("opacity", 0); // this is optional - if you want visible dots or not!

				// Adding a subtle animation to increase the dot size when over it!
				circles
					.on("mouseover", mouseoverFunc)
					.on("mousemove", mousemoveFunc)
					.on("mouseout",	mouseoutFunc);
				
			//circles
				// .on('mouseover', tip.show)
				 //.on('mouseout', tip.hide);
				//Axes
				svg.append("g")
					.attr("class", "x axis")
					.attr("transform", "translate(0," + height + ")")
					.call(xAxis);

				svg.append("g")
					.attr("class", "y axis")
					.call(yAxis);
					
				// Label below x axis
				svg.append("text")
					.attr("class", "xlabel")
					.attr("transform", "translate(" + width/2 + " ," +
        				height + ")")
					.style("text-anchor", "middle")
					.attr("dy", "35")
					.text("Years");
					
				 // Label for the y axis
				svg.append("text")
					.attr("class", "ylabel")
					.attr("transform", "rotate(-90)")
					.attr("y", 0 - margin.left)
					.attr("x",0 - (height / 2))
					.attr("dy", "1em")
					.style("text-anchor", "middle")
					.text("Percentage"); 

				function mouseoverFunc(d) {
					// this will highlight both a dot and its line.
					var lineid = d3.select(this).attr("id");

					d3.select(this)
						.transition()
						.style("opacity", 1)
						.attr("r", 4);

					d3.select("path#" + lineid).classed("focused", true).classed("unfocused", false);
					//tip.show
					circles.on('mouseover', tip.show)

					tooltip
						.style("display", null) // this removes the display none setting from it
						//.style("visibility",visible)
						.html("<p>State: " + d.state +
									"<br>Year: " + d.year +
								  "<br>Percent: " + d.percent + "</p>");
				}
					

				function mousemoveFunc(d) {
circles.on('mouseover', tip.show)
					tooltip
						.style("top", (d3.event.pageY - 10) + "px" )
						.style("left", (d3.event.pageX + 10) + "px");
				}

				function mouseoutFunc(d) {
									 circles.on('mouseout', tip.hide);
					d3.select(this)
						.transition()
						.style("opacity", 0)
						.attr("r", 3);

					d3.selectAll("path.line").classed("unfocused", true).classed("focused", false);
					//tip.hide;
					tooltip.style("display", "none");  // this sets it to invisible!
			  }
 
			//}); // end of data csv
			
}
</script>
		<!-- Footer -->
        <?php include('visualizehealth-footer.inc') ?>
	</body>
</html>