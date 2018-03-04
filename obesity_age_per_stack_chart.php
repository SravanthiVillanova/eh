<!--<!DOCTYPE html>-->
<!-- Modification of an example by Scott Murray from Knight D3 course -->
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Stackable Bar Chart with Multiple Lines</title>
	
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
<style>
body {
  font: 10px sans-serif;
}

.axis path,
.axis line {
  fill: none;
  stroke: black;
  stroke-width: 2px;
  //stroke: #000;
  //shape-rendering: crispEdges;
}

.bar {
  fill: lightblue;
}

.x.axis path {
  //display: none;
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
					<a href="#" role="button" class="btn btn-link Yrbtn active">2016</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Yrbtn">2015</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Yrbtn">2014</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Yrbtn">2013</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Yrbtn">2012</a>
				</li>
				<li>
					<a href="#" role="button" class="btn btn-link Yrbtn">2011</a>
				</li>
			</ul>
			<label><input type="checkbox"> Sort values</label>
        </div>
        <!-- Post Content Column -->
        <!--<div class="col-lg-8">-->
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<!-- Title -->
			<h1 class="mt-4">Diabetes by age group - Stack Bar Chart</h1>

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
		ajaxCall(y)
	});

function ajaxCall(yr) {
	$("#svg_div").html('');
		$.ajax({
			method: 'post',
			url: 'src/obesity_age_per_stack_chart.php',
			data: {
				year: yr
			},
			dataType: "json",
			cache: false,
			success: function(data) {
				bindStackChart(data);
			},
			error: function() {
				console.log("ajax-error");
			}
		});
}

$(document).ready(function(){
	ajaxCall('2016');
	return false;
});

function bindStackChart(data) {
var margin = {top: 20, right: 20, bottom: 30, left: 40},
    width = 900 - margin.left - margin.right,
    height = 600 - margin.top - margin.bottom;

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .rangeRound([height, 0]);

var color = d3.scale.category20c();
	//var color = d3.scale.ordinal()
    //.range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");
    //.tickFormat(d3.format(".2s"));

var svg = d3.select("#svg_div").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var active_link = "0"; //to control legend selections and hover
var legendClicked; //to control legend selections
var legendClassArray = []; //store legend classes to select bars in plotSingle()
var legendClassArray_orig = []; //orig (with spaces)
var sortDescending; //if true, bars are sorted by height in descending order
var restoreXFlag = false; //restore order of bars back to original


//disable sort checkbox
d3.select("label")             
  .select("input")
  .property("disabled", true)
  .property("checked", false); 

//d3.csv("state_data.csv", function(error, data) {
//d3.json("src/test_stackchart.php", function(error,data) {
  //if (error) throw error;

  color.domain(d3.keys(data[0]).filter(function(key) { return key !== "State"; }));

  data.forEach(function(d) {
    var mystate = d.State; //add to stock code
    var y0 = 0;
    //d.ages = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });
    d.ages = color.domain().map(function(name) {
      //return { mystate:mystate, name: name, y0: y0, y1: y0 += +d[name]}; });
      return { 
        mystate:mystate, 
        name: name, 
        y0: y0, 
        y1: y0 += +d[name], 
        value: d[name],
        y_corrected: 0
      }; 
      });
    d.total = d.ages[d.ages.length - 1].y1;    

  });

  //Sort totals in descending order
  data.sort(function(a, b) { return b.total - a.total; });  

  x.domain(data.map(function(d) { return d.State; }));
  y.domain([0, d3.max(data, function(d) { return d.total; })]);

	svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
	  .selectAll("text")  
      .style("text-anchor", "end")
      .attr("dx", "-.8em")
      .attr("dy", ".15em")
      .attr("transform", "rotate(-65)" );
	 
	// Label below x axis
	svg.append("text")
		.attr("class", "xlabel")
		.attr("transform", "translate(" + width/2 + " ," + height + ")")
		.style("text-anchor", "middle")
		.attr("dx", "435")
		.attr("style", "fill: black; writing-mode: tb; font-weight: bold; glyph-orientation-vertical: 0")
		.text("States");
					
	//Label for y axis
	svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
	  .append("text")
      .attr("y", -15)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
	  .style({ 'font-weight': 'bold'})
      .text("Percent");

  var state = svg.selectAll(".state")
      .data(data)
    .enter().append("g")
      .attr("class", "g")
      .attr("transform", function(d) { return "translate(" + "0" + ",0)"; });
      //.attr("transform", function(d) {return "translate(" + x(d.State) + ",0)"; })
	  	  //.attr("transform", "translate(" + x + "," + y + ")");

   height_diff = 0;  //height discrepancy when calculating h based on data vs y(d.y0) - y(d.y1)
   state.selectAll("rect")
      .data(function(d) {
        return d.ages; 
      })
    .enter().append("rect")
      .attr("width", x.rangeBand())
      .attr("y", function(d) {
        height_diff = height_diff + y(d.y0) - y(d.y1) - (y(0) - y(d.value));

        y_corrected = y(d.y1) + height_diff;
        d.y_corrected = y_corrected //store in d for later use in restorePlot()

        if (d.name === "75Plus") height_diff = 0; //reset for next d.mystate
          
        return y_corrected;    
        // return y(d.y1);  //orig, but not accurate  
      })
      .attr("x",function(d) { //add to stock code
          return x(d.mystate)
        })
      .attr("height", function(d) {
        //return y(d.y0) - y(d.y1); //heights calculated based on stacked values (inaccurate)
        return y(0) - y(d.value); //calculate height directly from value in csv file
      })
      .attr("class", function(d) {        
        classLabel = d.name.replace(/\s/g, ''); //remove spaces
        return "bars class" + classLabel;
      })
      .style("fill", function(d) { return color(d.name); });

	// Prep the tooltip bits, initial display is hidden
	var tip = d3.tip().attr('class', 'd3-tip')
                  .offset([-10, 0])
                  .html(function(d) {
						return "<strong>"+d.mystate+":</strong> <span style='color:red'>" + d.value + "</span>";
					});
	svg.call(tip);
	state.selectAll("rect")
	      .on('mouseover', tip.show)
          .on('mouseout', tip.hide);

  var legend = svg.selectAll(".legend")
      .data(color.domain().slice().reverse())
    .enter().append("g")
      .attr("class", function (d) {
        legendClassArray.push(d.replace(/\s/g, '')); //remove spaces
        legendClassArray_orig.push(d); //remove spaces
        return "legend";
      })
      .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });

  //reverse order to match order in which bars are stacked    
  legendClassArray = legendClassArray.reverse();
  legendClassArray_orig = legendClassArray_orig.reverse();

  legend.append("rect")
      .attr("x", width - 18)
      .attr("width", 18)
      .attr("height", 18)
      .style("fill", color)
      .attr("id", function (d, i) {
        return "id" + d.replace(/\s/g, '');
      })
      .on("mouseover",function(){        

        if (active_link === "0") d3.select(this).style("cursor", "pointer");
        else {
          if (active_link.split("class").pop() === this.id.split("id").pop()) {
            d3.select(this).style("cursor", "pointer");
          } else d3.select(this).style("cursor", "auto");
        }
      })
      .on("click",function(d){        

        if (active_link === "0") { //nothing selected, turn on this selection
          d3.select(this)           
            .style("stroke", "black")
            .style("stroke-width", 2);

            active_link = this.id.split("id").pop();
            plotSingle(this);

            //gray out the others
            for (i = 0; i < legendClassArray.length; i++) {
              if (legendClassArray[i] != active_link) {
                d3.select("#id" + legendClassArray[i])
                  .style("opacity", 0.5);
              } else sortBy = i; //save index for sorting in change()
            }

            //enable sort checkbox
            d3.select("label").select("input").property("disabled", false)
            d3.select("label").style("color", "black")
            //sort the bars if checkbox is clicked            
            d3.select("input").on("change", change);  
           
        } else { //deactivate
          if (active_link === this.id.split("id").pop()) {//active square selected; turn it OFF
            d3.select(this)           
              .style("stroke", "none");
            
            //restore remaining boxes to normal opacity
            for (i = 0; i < legendClassArray.length; i++) {              
                d3.select("#id" + legendClassArray[i])
                  .style("opacity", 1);
            }

            
            if (d3.select("label").select("input").property("checked")) {              
              restoreXFlag = true;
            }
            
            //disable sort checkbox
            d3.select("label")
              .style("color", "#D8D8D8")
              .select("input")
              .property("disabled", true)
              .property("checked", false);   


            //sort bars back to original positions if necessary
            change();            

            //y translate selected category bars back to original y posn
            restorePlot(d);

            active_link = "0"; //reset
          }

        } //end active_link check
                          
                                
      });

  legend.append("text")
      .attr("x", width - 24)
      .attr("y", 9)
      .attr("dy", ".35em")
      .style("text-anchor", "end")
      .text(function(d) { return d; });
//}
  // restore graph after a single selection
  function restorePlot(d) {
    //restore graph after a single selection
    d3.selectAll(".bars:not(.class" + class_keep + ")")
          .transition()
          .duration(1000)
          .delay(function() {
            if (restoreXFlag) return 3000;
            else return 750;
          })
          .attr("width", x.rangeBand()) //restore bar width
          .style("opacity", 1);

    //translate bars back up to original y-posn
    d3.selectAll(".class" + class_keep)
      .attr("x", function(d) { return x(d.mystate); })
      .transition()
      .duration(1000)
      .delay(function () {
        if (restoreXFlag) return 2000; //bars have to be restored to orig posn
        else return 0;
      })
      .attr("y", function(d) {
        //return y(d.y1); //not exactly correct since not based on raw data value
        return d.y_corrected; 
      });

    //reset
    restoreXFlag = false;
    
  }

  // plot only a single legend selection
  function plotSingle(d) {
        
    class_keep = d.id.split("id").pop();
    idx = legendClassArray.indexOf(class_keep);    
       
    //erase all but selected bars by setting opacity to 0
    d3.selectAll(".bars:not(.class" + class_keep + ")")
          .transition()
          .duration(1000)
          .attr("width", 0) // use because svg has no zindex to hide bars so can't select visible bar underneath
          .style("opacity", 0);

    //lower the bars to start on x-axis  
    state.selectAll("rect").forEach(function (d, i) {        
    
      //get height and y posn of base bar and selected bar
      h_keep = d3.select(d[idx]).attr("height");
      y_keep = d3.select(d[idx]).attr("y");  

      h_base = d3.select(d[0]).attr("height");
      y_base = d3.select(d[0]).attr("y");    

      h_shift = h_keep - h_base;
      y_new = y_base - h_shift;

      //reposition selected bars
      d3.select(d[idx])
        .transition()
        .ease("bounce")
        .duration(1000)
        .delay(750)
        .attr("y", y_new);

    })
   
  }

  //adapted change() fn in http://bl.ocks.org/mbostock/3885705
  function change() {

    if (this.checked) sortDescending = true;
    else sortDescending = false;

    colName = legendClassArray_orig[sortBy];

    var x0 = x.domain(data.sort(sortDescending
        ? function(a, b) { return b[colName] - a[colName]; }
        : function(a, b) { return b.total - a.total; })
        .map(function(d,i) { return d.State; }))
        .copy();

    state.selectAll(".class" + active_link)
         .sort(function(a, b) { 
            return x0(a.mystate) - x0(b.mystate); 
          });

    var transition = svg.transition().duration(750),
        delay = function(d, i) { return i * 20; };

    //sort bars
    transition.selectAll(".class" + active_link)
      .delay(delay)
      .attr("x", function(d) {      
        return x0(d.mystate); 
      });      

    //sort x-labels accordingly    
    transition.select(".x.axis")
        .call(xAxis)
        .selectAll("g")
        .delay(delay);

   
    transition.select(".x.axis")
        .call(xAxis)
      .selectAll("g")
        .delay(delay);    
  }
}



//});

</script>
		        <!-- Footer -->
         <?php include('visualizehealth-footer.inc') ?>
	</body>
</html>