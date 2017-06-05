

<!DOCTYPE html>

<html>
<head>
<title>Task1</title>
</head>
<meta charset="utf-8">
<!-- load the d3.js library -->    
<script src="http://d3js.org/d3.v3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.2/d3.js"></script>
<style> /* set the CSS */

body {
  font: 11px sans-serif;
}

.axis path,
.axis line {
  fill: none;
  stroke: #000;

}

.node1,.node2 {
  stroke: black;
}
.legend {
		font-size: 16px;         
		font-weight: bold;         
		text-anchor: start;
	
		}

.tooltip {
font-weight: bold;	
  position: absolute;
  width: 100px;
  height: 28px;
  pointer-events: none;
}
path { 
  fill: none;
}
 
.axis path,
.axis line {
	fill: none;
	stroke: black;
	stroke-width: 2;
	
}
ul.b {
border: 5px solid green;
    list-style-type: square;
	font-family: "Times New Roman", Times,  red serifs;
	font-size: 20px;
	color:green;

}
ul.r{
	font-family: "Times New Roman", Times,  red serifs;
	font-size: 15px;
	color:red;
}

</style>
<body>
<h1> <center>Bio Informatics Project</h1>

<ul class="b">
  <li>Click anywhere on the page to see all the paths</li>
  <li>Click on any of the legend circles to see the paths with respected weights</li>
  <li>You can also choose a file by just browsing the computer (with a csv format) </li>
  </ul>
  <ul class="r">
   NOTE: Add a .csv file, please don't add any other file
</ul>
<?php
  $uploaddir = './uploads/';
  $uploadfile = $uploaddir . $_FILES['file']['name'];

  if(isset($_POST['submit'])) {
    foreach(glob($uploaddir.'*.*') as $v){
          unlink($v);
    }
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		print "File Uploaded :";
       print_r($uploadfile);
    } 
  }
  $uploadfile = './uploads/'.scandir('./uploads')[2];
?>
<form method="post" action="./bioinfo.php" class="form-inline" enctype="multipart/form-data">
          <label class="file">
            <input type="file" id="file" name="file" class="custom-file-input">
            <span class="custom-file-control"></span>
          </label>
          <button type="submit" name="submit" class="btn btn-primary">Upload</button>
     
		</form>
<script>
$( document ).ready(function() {

// Set the dimensions of the canvas / graph
var margin = {top: 20, right: 400, bottom: 30, left: 40},
    width = 1240,
    height = 650;

//setup x
var x = d3.scale.linear()
    .range([0, width]);

// setup y
var y = d3.scale.linear()
    .range([height, 0]);



var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");


var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");
						

// add the tooltip area to the webpage
var tooltip = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0.9);
	

// add the graph canvas to the body of the webpage
var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	var valueline=d3.svg.line()
						.x(function(d) { 
						//console.log(x(d.x));
						return x(d.x); 
							})
						.y(function(d) { 
						return y(d.y); 
							});
    
//storing the drop-down selection in the ddSelection var
//var ddSelection = document.getElementById("secondOption").value;

//feeding that to create the csv filename you want
//var csvFile = "/some server path/" + ddSelection + ".csv";
// Get the data

d3.csv("<?php echo $uploadfile;?>", function(error, data) {
    data.forEach(function(d){
        //console.log(d);
        d.x2 = +d.x2;
        d.y2 = +d.y2;
        d.x1 = +d.x1;
        d.y1 = +d.y1;
        d.wt = +d.wt;
        
    });
	
	x.domain([0, d3.max(data, function(d) { return d.x2; })]);
    y.domain([0, d3.max(data, function(d) { return d.y2; })]);
	
		



    svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .append("text")
      .attr("class", "label")
      .attr("x", width)
      .attr("y", -6)
      .style("text-anchor", "end")
      .text("X-axis");
    
      

    svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("class", "label")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Y-axis");
	  
	  // creating legend 1
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 25)
		.attr("r", 7)
		.attr("z1",-500)
		.attr("z2",0)	
		.style("fill", "black")
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[0].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[0].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"black");
});
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 30)    
		.attr("class", "legend")
		.style("fill", "black")         
		.text(function(d){ return "Negative values"})
		.attr("class", "legend");
		  
		   // creating legend 2
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 55)
		.attr("r", 7)
		.attr("z1",0)
		.attr("z2",40)	
		.style("fill", "orange")
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[1].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[1].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"orange");
});
		
		
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 60)    
		.attr("class", "legend")
		.style("fill", "orange")         
		.text(function(d){ return "0 - 40"})
		.attr("class", "legend");
		
		  

		  // creating legend 3
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 85)
		.attr("r", 7)
		.style("fill", "#59FF00")
		.attr("z1",40)
		.attr("z2",80)	
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[2].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[2].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"#59FF00");
});
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 90)    
		.attr("class", "legend")
		.style("fill", "#59FF00")         
		.text(function(d){ return "40 - 80"})
		.attr("class", "legend");
		  
		  
		  // creating legend 4 
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 115)
		.attr("r", 7)
		.attr("z1",80)
		.attr("z2",160)	
		.style("fill", "#C20024")
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[3].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[3].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"#C20024");
});
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 120)    
		.attr("class", "legend")
		.style("fill", "#C20024")         
		.text(function(d){ return "80 - 160"})
		.attr("class", "legend");
		 // creating legend 5
	svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 145)
		.attr("r", 7)
		.attr("z1",160)
		.attr("z2",320)			  
		.attr("class", "legendcircle")
		.style("fill", "pink")         
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[4].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[4].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"pink");
})
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 150)    
		.attr("class", "legend")
		.style("fill", "pink")         
		.text(function(d){ return "160 - 320"})
		.attr("class", "legend");
		
	// creating legend 6
	svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 175)
		.attr("r", 7)
		.attr("z1",320)
		.attr("z2",10000000000)			  
		.attr("class", "legendcircle")
		.style("fill", "purple")         
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[5].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[5].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"purple");
})
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 180)    
		.attr("class", "legend")
		.style("fill", "purple")         
		.text(function(d){ return "above 320"})
		.attr("class", "legend");
		

      svg.selectAll(".node2")     //for x2, y2 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node2")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x2); })
      .attr("cy", function(d) { return y(d.y2); })
      

    .style("fill", "red")
	
    .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", 0.9);
          tooltip.html( "node(" + d["x2"] + "," + d["y2"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
			   
      }) 
	
	  .on("click", function(d) {
	  	event.stopPropagation();
	  $(".line").remove();
        d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  console.log(id);
			  var str=id.wt/40,
			  node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				.attr('stroke','blue').attr("d", valueline(lineData)) .on("mouseover", function(linehigh) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
						
					  tooltip.html( "Wt : " +id.wt+"</br>"+node1+"</br>"+node2)
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) ;
			  }
				
			});
});


});



      svg.selectAll(".node1")     //for x1, y1 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node1")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x1); })
      .attr("cy", function(d) { return y(d.y1); })
      

    .style("fill", "red")
      .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", 0.9);
          tooltip.html( "Wt : " + d["wt"] + "<br>     node(" + d["x1"] + "," + d["y1"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
      })
	  .on("click", function(d) {

		event.stopPropagation();
		$(".line").remove();
		 d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  var str=id.wt/40 ,node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				.attr('stroke','blue').attr("d", valueline(lineData))
				.on("mouseover", function(hoverhighlight) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
					  tooltip.html( "Wt : " + id.wt+"</br>"+node1+"</br>"+node2 )
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) ;
			  }
				
			});
});
	
});



function ClickReset() {	
	$(".line").remove();
	data.forEach(function(d){
  
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
      function dataline(wt) {
           if(wt<0)
               return 'black';
		   else if(wt>0 && wt<40)
			   return 'orange';
		   else if(wt>40 && wt<80)
			   return 'green';
		   else if(wt>80 && wt<160)
			   return '#C20024';
		    else if(wt>160 && wt<320)
			   return 'pink';
		   else
			   return 'purple';
		   
           
       }
	   /* function strokewidth(wt) {
           if(wt<0)
               return '5';
		   else
			   return '2';
		   
           
       }
*/
        svg.append("path")
		.attr("class", "line")
		.attr('stroke-width', 1)
		.attr('stroke',dataline(d.wt))
            .attr("d", valueline(lineData));
	});
}//ClickReset
d3.select("body").on("click", ClickReset);



});
});

//functiomnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
function bhavani(val1,val2,col){
	
	 $("svg").remove();
	
	var margin = {top: 20, right: 400, bottom: 30, left: 40},
    width = 1240,
    height = 650;

//setup x
var x = d3.scale.linear()
    .range([0, width]);

// setup y
var y = d3.scale.linear()
    .range([height, 0]);



var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");


var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");
						

// add the tooltip area to the webpage
var tooltip = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0.9);
	

// add the graph canvas to the body of the webpage
var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	var valueline=d3.svg.line()
						.x(function(d) { 
						//console.log(x(d.x));
						return x(d.x); 
							})
						.y(function(d) { 
						return y(d.y); 
							});
    var dataset = [];
//storing the drop-down selection in the ddSelection var
//var ddSelection = document.getElementById("secondOption").value;

//feeding that to create the csv filename you want
//var csvFile = "/some server path/" + ddSelection + ".csv";
// Get the data

d3.csv("<?php echo $uploadfile;?>", function(error, data) {
    data.forEach(function(d){
        //console.log(d);
        d.x2 = +d.x2;
        d.y2 = +d.y2;
        d.x1 = +d.x1;
        d.y1 = +d.y1;
        d.wt = +d.wt;
		if(d.wt > val1 && d.wt <val2){
	dataset.push(d);
	}
        
        
    });
	
	x.domain([0, d3.max(data, function(d) { return d.x2; })]);
    y.domain([0, d3.max(data, function(d) { return d.y2; })]);
	
	
    svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .append("text")
      .attr("class", "label")
      .attr("x", width)
      .attr("y", -6)
      .style("text-anchor", "end")
      .text("X-axis");
    
      

    svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("class", "label")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Y-axis");
	  
	 	  // creating legend 1
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 25)
		.attr("r", 7)
		.attr("z1",-500)
		.attr("z2",0)	
		.style("fill", "black")
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[0].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[0].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"black");
});
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 30)    
		.attr("class", "legend")
		.style("fill", "black")         
		.text(function(d){ return "Negative values"})
		.attr("class", "legend");
		  
		   // creating legend 2
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 55)
		.attr("r", 7)
		.attr("z1",0)
		.attr("z2",40)	
		.style("fill", "orange")
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[1].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[1].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"orange");
});
		
		
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 60)    
		.attr("class", "legend")
		.style("fill", "orange")         
		.text(function(d){ return "0 - 40"})
		.attr("class", "legend");
		
		  

		  // creating legend 3
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 85)
		.attr("r", 7)
		.style("fill", "#59FF00")
		.attr("z1",40)
		.attr("z2",80)	
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[2].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[2].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"#59FF00");
});
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 90)    
		.attr("class", "legend")
		.style("fill", "#59FF00")         
		.text(function(d){ return "40 - 80"})
		.attr("class", "legend");
		  
		  
		  // creating legend 4 
	  svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 115)
		.attr("r", 7)
		.attr("z1",80)
		.attr("z2",160)	
		.style("fill", "#C20024")
		.attr("class", "legendcircle")
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[3].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[3].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"#C20024");
});
		
		
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 120)    
		.attr("class", "legend")
		.style("fill", "#C20024")         
		.text(function(d){ return "80 - 160"})
		.attr("class", "legend");
		 // creating legend 5
	svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 145)
		.attr("r", 7)
		.attr("z1",160)
		.attr("z2",320)			  
		.attr("class", "legendcircle")
		.style("fill", "pink")         
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[4].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[4].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"pink");
})
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 150)    
		.attr("class", "legend")
		.style("fill", "pink")         
		.text(function(d){ return "160 - 320"})
		.attr("class", "legend");
		
	// creating legend 6
	svg.append("circle")
		.attr("cx", 1300)
		.attr("cy", 175)
		.attr("r", 7)
		.attr("z1",320)
		.attr("z2",10000000000)			  
		.attr("class", "legendcircle")
		.style("fill", "purple")         
		.on("click", function(){
		val1=document.getElementsByClassName('legendcircle')[5].getAttribute('z1')
		val2=document.getElementsByClassName('legendcircle')[5].getAttribute('z2')
		console.log(val1,val2);
		
		bhavani(val1,val2,"purple");
})
	svg.append("text")
		.attr("x", 1330)             
		.attr("y", 180)    
		.attr("class", "legend")
		.style("fill", "purple")         
		.text(function(d){ return "Above 320"})
		.attr("class", "legend");

      svg.selectAll(".node2")     //for x2, y2 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node2")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x2); })
      .attr("cy", function(d) { return y(d.y2); })
      

    .style("fill", "red")
	
    .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", 0.9);
          tooltip.html( "node(" + d["x2"] + "," + d["y2"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
			   
      }) 
	
	  .on("click", function(d) {
	  	event.stopPropagation();
	  $(".line").remove();
        d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  console.log(id);
			  var str=id.wt/40,
			  node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				.attr('stroke','blue').attr("d", valueline(lineData)) .on("mouseover", function(linehigh) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
						
					  tooltip.html( "Wt : " +id.wt+"</br>"+node1+"</br>"+node2)
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) ;
			  }
				
			});
});


});


dataset.forEach(function(id){
				
				
		//	  if(id.x2==d.x2 && id.y2==d.y2)
			//  {
			  //console.log(id);
			  var str=id.wt/40,
			  node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('style','stroke: red')
				.attr('stroke-width', +str)
				.style('stroke',col).attr("d", valueline(lineData)) .on("mouseover", function(din) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
						  
					  tooltip.html( "Wt : " +id.wt+"</br>"+node1+"</br>"+node2)
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) ;
		//	  }
				
			});
		//	});



      svg.selectAll(".node1")     //for x1, y1 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node1")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x1); })
      .attr("cy", function(d) { return y(d.y1); })
      

    .style("fill", "red")
      .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", 0.9);
          tooltip.html( "Wt : " + d["wt"] + "<br>     node(" + d["x1"] + "," + d["y1"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
      })
	  .on("click", function(d) {

		event.stopPropagation();
		$(".line").remove();
		 d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  var str=id.wt/20 ,node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				.attr('stroke','blue').attr("d", valueline(lineData))
				.on("mouseover", function(hoverhighlight) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
					  tooltip.html( "Wt : " + id.wt+"</br>"+node1+"</br>"+node2 )
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) ;
			  }
				
			});
});
	
});
 /*data.forEach(function(d){
  
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
       function dataline(wt) {
           if(wt<30)
               return 'red';
           else
               return 'green';
           
       }

        svg.append("path").attr("class", "line").attr('stroke-width', 0.5)
				.attr('stroke',dataline(d.wt))
            .attr("d", valueline(lineData));
	});*/

function ClickReset() {	
	$(".line").remove();
	data.forEach(function(d){
  
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
      function dataline(wt) {
            if(wt<0)
               return 'black';
		   else if(wt>0 && wt<40)
			   return 'orange';
		   else if(wt>40 && wt<80)
			   return 'green';
		   else if(wt>80 && wt<160)
			   return '#C20024';
		    else if(wt>160 && wt<320)
			   return 'pink';
		   else
			   return 'purple';
		   
           
       }
	
        svg.append("path")
		.attr("class", "line")
		.attr('stroke-width', 1)
		.attr('stroke',dataline(d.wt))
            .attr("d", valueline(lineData));
	});
}//ClickReset
d3.select("body").on("click", ClickReset);

});
	
}



</script>
</body>

</html>