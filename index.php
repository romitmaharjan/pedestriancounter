<?php
session_start();
$locations=array();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pedestrian_counter";

$conn = new mysqli($host, $username, $password, $dbname);
$query = $conn->query("SELECT * FROM sensor S left join heatmap H on S.sensorID = H.sensorID");

while($row = $query->fetch_assoc() ){
  $name = $row['name'];
  $lat = $row['lat'];
  $lng = $row['lng'];
  $value = $row['value'];
  $sensorID = $row['sensorID'];

  $locations[] = array('name'=>$name, 'lat'=>$lat, 'lng'=>$lng, 'value'=>$value, 'sensorID'=>$sensorID);
}
//echo $locations[0]['name'].": In stock: ".$locations[0]['lat'].", sold: ".$locations[0]['lng'].".<br>";
  //      echo $locations[1]['name'].": In stock: ".$locations[1]['lat'].", sold: ".$locations[1]['lng'].".<br>";
    //    echo $locations[2]['name'].": In stock: ".$locations[2]['lat'].", sold: ".$locations[2]['lng'].".<br>";
?>

 <!DOCTYPE html>
<html>
<head>
  <title>Pedestrian Counter</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
  <script src="js/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/Chart.js"></script>
</head>
<body>
<div class="container-fluid">
  
  <div id="map"></div>

    <div id="mySidebar" class="sidebar">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
      <a href="javascript:void(0)" class="sideBarLink" id="about" onclick="openSide();sideAbout();">About</a>
      <a href="javascript:void(0)" class="sideBarLink" onclick="openSide(); sideFAQ();">Frequently Asked Questions</a>
      <a href="javascript:void(0)" class="sideBarLink" onclick="openSide(); sideObjective();">Objectives</a>
      <a href="javascript:void(0)" class="sideBarLink" onclick="openSide(); sideSystemInfo();">System Information</a>
    </div>

    <div id="main">
      <button class="openbtn" onclick="openNav()">☰</button>  
    </div>

    <script>
    function openNav() {
      document.getElementById("mySidebar").style.width = "250px";
    }

    function closeNav() {      
      document.getElementById("mySidebar").style.width = "0";
    }
    </script>
  <div class="card border-info">
        <div class="card-header" style="padding: 10px;">IoT Pedestrian Counter</div>
          <div class="card-body">      
            <div class="row" style="padding-bottom: 10px; padding-top: 10px;">          
              <span class="combinedIndex"></span>
              <div class="form-group">
              <input id="date" class="form-control" disabled="true">   
              </div>
            </div>
            <script type="text/javascript">
            $(document).ready(function(){
              $(".combinedIndex").text(
                "<?php
                $cardTotalCount =  $_SESSION['SUM(totalCount)'];
                echo $cardTotalCount;
                ?>"
                );
              $(".avgFourWeeks").text(
                "<?php
                $avgFourWeeks =  $_SESSION['combinedCount'];
                echo $avgFourWeeks;
                ?>"
                );
              $(".leftPed").text(
                "<?php
                $totalLPedestrian =  $_SESSION['totalLeftPedestrian'];
                echo $totalLPedestrian;
                ?>"
                );
              $(".rightPed").text(
                "<?php
                $totalRPedestrian =  $_SESSION['totalRightPedestrian'];
                echo $totalRPedestrian;
                ?>"
                );
              $(".leftCyc").text(
                "<?php
                $totalLCyclist =  $_SESSION['totalLeftCyclist'];
                echo $totalLCyclist;
                ?>"
                );
              $(".rightCyc").text(
                "<?php
                $totalRCyclist =  $_SESSION['totalRightCyclist'];
                echo $totalRCyclist;
                ?>"
                );
            });
             

            function updateClock(){
              var cTime = new Date();
              var cHours = cTime.getHours();
              var cMinutes = cTime.getMinutes();
              var cSeconds = cTime.getSeconds();
              var day = cTime.getDate();
              var month = cTime.getMonth() + 1;
              var year = cTime.getFullYear();

              cMinutes = (cMinutes < 10 ? "0" : "") + cMinutes;
              cSeconds = (cSeconds < 10 ? "0" : "") + cSeconds;
              day = (day < 10 ? "0" : "") + day;
              month = (month < 10 ? "0" : "") + month;

              var timeOfDay = (cHours < 12) ? "AM" : "PM";
              cHours = (cHours > 12) ? cHours - 12 : cHours;
              cHours = (cHours == 0 ) ? 12 : cHours;

              var cTimeString = cHours + ":" + cMinutes + ":" + cSeconds + " " + timeOfDay;
              var dateString = day + "/" + month + "/" + year;
              $('#time').val(cTimeString); 
              $('#date').val(dateString);
            }
            $(document).ready(function(){
              setInterval('updateClock()', 1000);
            });
            </script>
            <div class="row" style="padding-bottom: 10px;">
              <span class="fa-stack fa-lg">
              <i class="fa fa-square fa-stack-2x"></i>
              <i class="fa fa-walking fa-stack-1x fa-inverse" aria-hidden="true"></i>  
              </span>            
              <span class="fa-stack fa-lg">
              <i class="fa fa-square fa-stack-2x"></i>
              <i class="fa fa-bicycle fa-stack-1x fa-inverse"></i>
              </span>
              <div class="form-group">            
                <input id = "time" class="form-control" disabled="true">
              </div>
            </div>
            <div class="row"> 
              <!--<span class="sensorName"></span>-->
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="submit">
                Sensors
                </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'pedestrian_counter');
                    $sql = "SELECT name, sensorID from Sensor";
                    $query = $conn->query($sql);                   

                    while($row = $query->fetch_assoc()){
                      echo "
                        <a class ='dropdown-item' data-toggle ='modal' href='#exampleModal' value=".$row['sensorID'].">".$row['name']."</a>
                      ";
                    }
                    ?>
                  </div>
              </div>  
              <script>
                $(document).ready(function(){
                  $(".dropdown-item").click(function(){
                    var a = $(this).text();
                    $("#dropdownMenuButton").text(a);
                    var sensorIDFromDropdown = $(this).attr("value");
                    //alert(sensorIDFromDropdown);
                    $.ajax({
                      url: "success.php",
                      type: "POST",
                      data: { 'idFromDropdown': sensorIDFromDropdown },
                      success: function(sensorIDFromDropdown){
                        alert("Done");
                      },
                      error: function(error)
                      {
                        alert("NOT from index".error);
                      }
                          });
                  });
                  });                
              </script>
            </div>
          </div>
        </div>

        <div>
          <center><img src = "images/underdogs.jpg" class="mainLogo" id="mainLogo" height = 175 width="400"> </center>
        </div>
<ul class="list-group" id="downloadfile">
  <strong><li class="list-group-item list-group-item-dark">Download Data</li></strong>
  <a href="January-2019.csv" class="downloadlink" download><li class="list-group-item list-group-item-light">January 2019 (.csv file)</li></a>
  <li class="list-group-item list-group-item-light">Febuary 2019 (.csv file)</li>
  <li class="list-group-item list-group-item-light">March 2019 (.csv file)</li>
  <li class="list-group-item list-group-item-light">April 2019 (.csv file)</li>
</ul>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <div><input type="date" id="date1" class="form-control" style="width: 130px;"><i class="fas fa-calendar" id="calendar"></i></div>
        <script>document.querySelector("#date1").valueAsDate = new Date();</script>
        <div class="dropdown" style="margin-top: 5px; bottom: 0px;">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sensors
                </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'pedestrian_counter');
                    $sql = "SELECT name, sensorID from Sensor";
                    $query = $conn->query($sql);

                    while($row = $query->fetch_assoc()){
                      echo "
                        <a class ='dropdown-item1' id = 'dropdown-item-modal' href='#'value=".$row['sensorID'].">".$row['name']."</a>
                      ";
                    }
                    ?>
                  </div>
                  <script>
                $(document).ready(function(){
                  $(".dropdown-item1").click(function(){
                    var b = $(this).text();
                    $("#dropdownMenuButton1").text(b);
                     var sensorIDFromDropdown1 = $(this).attr("value");
                     //alert(sensorIDFromDropdown1);
                     $.ajax({
                      url: "success.php",
                      type: "POST",
                      data: { 'idFromDropdown1': sensorIDFromDropdown1 },
                      success: function(sensorIDFromDropdown1){
                        alert("Done");
                      },
                      error: function(error)
                      {
                        alert("NOT from index".error);
                      }
                          });
                  });
                  });               
              </script>
              </div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
          <div class="row">
      <p style="margin-bottom:0px;">
      <h3 id="final"><br>
  <?php
  //$emp = $_SESSION['id'];
  //print_r($emp);
  /*$markerName= "<span name=\"sensorIDRetrived\" class=\"senddata\" style=\"visibility: none;\"></span>";
  preg_match_all('/<span .*?>(.*?)<\/span>/', $markerName, $matches);
  //print_r($matches);
  $lastData = $matches[0][0];
  print_r($lastData);

  //$lastData =  strval($markerName);  */
  if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    //echo "$id";
    unset($_SESSION['id']);
  
  }
  $conn = new mysqli('localhost', 'root', '', 'pedestrian_counter');    
    
    $query = $conn->query("SELECT name from Sensor where sensorID ='$sensorID'");

    while($row = $query->fetch_assoc()){
      echo "<p>".$row['name']."</p>";
    }
  
  ?>
    </h3>pedestrains and cyclists were counted at this hour.</p>
    <p style="right: 10px; position: absolute; margin-bottom:10px;">Pedestrian Left Count: <span class="leftPed"></span></p><br>
      <p style="margin-top: 20px; right:10px; position: absolute; margin-bottom:0px;">Pedestrain Right Count: <span class="rightPed"></span><br></p>
      <p style="margin-top: 40px;right: 10px; position: absolute; margin-bottom:10px;">Cyclist Left Count: <span class="leftCyc"></span></p><br>
      <p style="margin-top: 60px; right:10px; position: absolute; margin-bottom:0px;">Cyclist Right Count: <span class="rightCyc"></span></p>
    </div>
    <canvas id="mainChart" height="200">
      <script type="text/javascript" src="line.js"></script>
    </canvas>
    <p>
    4 Weeks Average: <span class="avgFourWeeks"></span></p>
  </div>
</div>
</div>
</div>
</div>
<script>
  $(document).ready(function(){  
    $("#downloadbutton").click(function(){
      $("#downloadfile").show();
    });
  });  
</script>
<script>
$(document).mouseup(function(e){
    var container = $("#downloadfile");
    if(!container.is(e.target) && container.has(e.target).length === 0){
        container.fadeOut(500);
    }
});
</script>
  
<script>
function myMap() {
  //var locations = [
    //['Chum Street', , 1],
    //['Alder Street', -36.783680, 144.240900, 2],
    //['Allingham Street', -36.778849, 144.255131],
    //['Breen Street', -36.771419, 144.267474]
  //];
  var locations = [
    <?php for($i=0;$i<sizeof($locations);$i++){ $j = $i + 1;?>
      [
        <?php echo $locations[$i]['lat'];?>,
        <?php echo $locations[$i]['lng'];?>,
        <?php echo $locations[$i]['value'];?>,
        '<?php echo $locations[$i]['name'];?>',
        '<?php echo $locations[$i]['sensorID'];?>',
        0
      ]<?php if($j!=sizeof($locations))echo ","; }?>
  ];
var location = {lat:-36.770120, lng: 144.259505};
var map = new google.maps.Map(document.getElementById("map"), {minZoom: 12, maxZoom: 17, zoom: 14, center: location, 
  restriction: {
    latLngBounds: {
      east: 144.897420,
      north: -36.325620,
      south: -37.086440,
      west: 144.013670
    },
    strictBounds: true
  },
	mapTypeControl: false, fullscreenControl: false, streetViewControl: false});

var newCenter;
var styles = [
  {
    featureType:"poi",
    elementType:"labels",
    stylers:[
      {visibility:"off"}
    ]
  },
  {
    featureType:"transit",
    elementType:"labels",
    stylers:[
      {visibility:"off"}
    ]
  },
];

map.setOptions({styles:styles});


function calculateNewCenter(){
  newCenter = map.getCenter();
}

google.maps.event.addDomListener(map, 'idle', function(){
  calculateNewCenter();
});

google.maps.event.addDomListener(window, 'resize', function(){
  map.setCenter(newCenter);
});

var infoWindow = new google.maps.InfoWindow();
var marker, i;

for(i=0; i < locations.length; i++){
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][0], locations[i][1]),
      id: locations[i][4],
    map: map, 
    title: locations[i][3],
  });
    

  /*google.maps.event.addListener(marker, 'click', (function(marker, i){
    return function(){
      infoWindow.setContent(locations[i][3]);
      infoWindow.open(map, marker);
    }
  })(marker, i));*/

google.maps.event.addListener(marker, 'click', function(){
  var getID = this;
  $('#exampleModal').modal('show');
  var sensorIDM=getID.id;
  $.ajax({
    dataType: 'text',
    contentType: 'application/x-www-form-urlencoded',
    url: "success.php",
    type: "POST",
    data: { 'id': sensorIDM },
    success: function(sensorIDM){
      console.log(sensorIDM);
    },
    error: function(error)
    {
      alert("NOT".error);
    }
        });
});


var getPoints = [
  {location: new google.maps.LatLng(locations[i][0], locations[i][1]), weight:locations[i][2]}
  ];

var heatmap = new google.maps.visualization.HeatmapLayer({
  data: getPoints,
  radius: 30,
  maxIntensity:4,
  map: map
});

//alert(locations[i][2]);
}

}


</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key="apikey"&libraries=visualization&callback=myMap
"></script>
<div id="myNav" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick="closeSide()">&times;</a>
  <div class="overlay-content">
    <p id="sideAbout"></p>
    <p id="sideFAQ"></p>
    <p id="sideObjective"></p>
    <p id="sideSystemInfo"></p>
  </div>
</div>

<script>
function openSide() {
  document.getElementById("myNav").style.width = "100%";
  document.getElementById("mySidebar").style.width = "0%";
}

function closeSide() {
  document.getElementById("myNav").style.width = "0%";
  $(".overlay-content p").html("");
}
</script>
<footer class="footer">
  <div class="socialmedia">    
    <a href = "https://facebook.com" target="blank"><span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x fa-inverse" style="color: #3b5999;"></i>
      <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
    </span>
  </a>
    <a href = "https://instagram.com" target="blank"><span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x fa-inverse"style="color:#e4405f;"></i>
      <i class="fab fa-instagram fa-stack-1x fa-inverse"></i>
    </span></a>
    <a href = "https://twitter.com" target="blank"><span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x fa-inverse" style="color: #55acee;"></i>
      <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
    </span></a>
  </div>
  <div class="downloadbtn">
    <a class = "indexText" tabindex = "0" href = "#" data-toggle="popover" data-trigger="focus" data-html = "true" data-content=
    "<img src ='images/above_average.png' width=35px height=35px>&nbsp;<span class='indexText'>Above Average</span> <br /><br />
      <img src ='images/average.png' width=35px height=35px>&nbsp;<span class='indexText'>Average</span><br /><br />
      <img src ='images/below_average.png' width=35px height=35px>&nbsp;<span class='indexText'>Below Average</span>" 
      data-placement = "top"><span class="fa-stack fa-lg indexFa">
      <i class="far fa-circle fa-stack-2x fa-inverse"></i>
      <i class="fas fa-info fa-stack-1x fa-inverse"></i>
    </span></a>
  <button type="button" id ="downloadbutton" class= "btn btn-outline-light">Download</button>
  </div>
</footer>
</div>
<script>
  $(document).ready(function(){
    $('[data-toggle="popover"]').popover({
      trigger:'focus'
    });
  });
</script>
<script>
  function sideAbout(){
    document.getElementById("sideAbout").innerHTML = "\
        <h1>About</h1>\
          Walking and Cycling is important to a city – pedestrian and cyclist activity is an indication of a city’s vibrancy and vitality. There is also a direct link between city’s economic prosperity and the safety and convenience of the pedestrian experience.\
 Team Underdogs has developed an automated pedestrian counting system to better understand pedestrian and cyclist activity within the City of Greater Bendigo. The information can be used to examine how people use different city locations at different times of day to better inform decision-making and planning.<br> \
The data is available in our visualization website. You can use this online visualization tool to:\
<ul>\
<li>View a representation of pedestrian and cyclist volume for each location on any given day and time</li>\
<li>Toggle between pedestrian and cyclist data and their direction</li>\
<li>See the impact of various factors, such as major events or extreme weather conditions, on pedestrian and cyclist activity in the city.</li>\
<li>Download the raw data for further analysis and visualization. </li>\
</ul>\
\
<h1>Disclaimer</h1>\
While all due care has been taken to ensure the data of this website is accurate and current, there may be errors or omissions in it.<br> <strong>Team Underdogs</strong> accept no responsibility for the completeness, accuracy or reliability of the data.<br><strong> Team Underdogs</strong> also accept no responsibility for any loss, damage, claim, expense, cost or liability whatsoever (including in contract, tort including negligence, pursuant to statue and otherwise) arising  in respect of or in connection with using or reliance upon the data in this website.\
    </p>"
  };

  function sideFAQ(){
    document.getElementById("sideFAQ").innerHTML = "\
    <h1>Frequently asked questions</h1>\
Frequently asked questions (FAQ) about the IoT Pedestrian Frequency Counter System and data visualization are featured below:<br><br>\
\
<h4>What is the IoT Pedestrian Frequency Counter System?</h4>\
The IoT Pedestrian Frequency Counter System is an automated pedestrian and cyclist counting system that is designed to collect and disseminate pedestrian and cyclist traffic data. \
The system comprises of doppler radar sensors to detect movements and differentiate whether it’s pedestrian or cyclist, The Things Network as central server for data collection and transmission, and software for data analysis, reporting and visualization.<br><br>\
\
<h4>Why is the system useful?</h4>\
The information obtained from high quality continuous pedestrian and cyclist monitoring is extremely useful for the City of Greater Bendigo and other organizations. The data, which represents volume of pedestrians and cyclists in an area, can be used:<ul>\
<li>To inform emergency response planning</li>\
<li>To better understand the environmental impacts and benefits of walking and cycling.</li>\
<li>To assess and address risks associated with walking and cycling.</li>\
<li>To monitor and evaluate the impact of ongoing and major events in the city</li></ul>\
The data is also useful for businesses to determine property values, security needs and staffing requirements. Businesses can also use the data to inform their marketing strategies to maximize exposure to passing pedestrian and cyclist traffic and attract potential customers.<br><br>\
\
<h4>Does the system record personal information?</h4>\
The IoT Pedestrian Frequency Counter System counts pedestrians and cyclists’ movements, not images. Therefore, no personal information is recorded.<br><br>\
\
<h4>How do I use the visualization tool?</h4>\
The website allows you to visualize pedestrian and cyclist’s volume in various locations in the city on any given day and time. \
The tool illustrates the data in a line graph, provides with a combined index of both pedestrian and cyclist data for any given day.\
Also, you can determine directional count for pedestrians and cyclists and download the data for any specific month.  \
"
  };

  function sideObjective(){
    document.getElementById("sideObjective").innerHTML = "\
    <h1>Objectives of the system</h1><br>\
The IoT Pedestrian Frequency Counter System aims to:\
<ul>\
<li>Inform decisions about urban planning and management.</li>\
<li>Identify opportunities to improve city walkability, bicycle lanes and transport.</li>\
<li>Measure the impacts of events and specific marketing campaigns on pedestrian and cyclist’s activity.</li>\
<li>Monitor retail activity in the city.</li>\
<li>Assist the business community in developing marketing strategies to maximise their exposure and identify staffing, security and resource requirements.</li></ul><br>\
<h3>How can the data be Used?</h3>\
The data can be used by the City of Greater Bendigo to:\
<ul>\
<li>Monitor pedestrian and cyclist’s activity in the city over time and determine variations throughout the day, week, month and year.</li>\
<li>Understand changes in pedestrian and cyclist’s activity, in relation to facilities provided, at various locations.</li>\
<li>Understand pedestrian and cyclist’s activity patterns at various locations throughout the city.</li>\
<li>Plan and respond to emergency situations.</li>\
<li>Understand the impact of major events and other extreme conditions on pedestrian and cyclist’s activity in the city.</li>\
<li>Inform other planning and implementation activities.</li>\
<li>Identify locations for pedestrian and cyclist’s facility improvements.</li>\
<li>Develop pedestrian and cyclist’s flow models.</li>\
<li>Assess economic and social impacts of pedestrian and cyclist’s facilities.</li>\
<li>Provide concrete information to justify spending public resources on improving walkability and cycle lanes.</li>\
</ul>\
    "
  };

  function sideSystemInfo(){
    document.getElementById("sideSystemInfo").innerHTML = "\
    <h1>System Information</h1>\
\
The system consists of HB-100 and 2 x Ultrasonic sensors combined in such a way as to count the total number of pedestrians and cyclists passing by it on an hourly basis. The information received by those sensors is transferred wirelessly to the “The Things Network” from where the information is relayed to a database and then visualised with a dynamic website.<br><br>\
\
Three aforementioned sensors are placed in a weather-resistant box on a street pole which records bi-directional pedestrians and cyclists’ movements through the zone.<br><br>\
\
The sensors record 24 hours a day which is why the device is powered by a solar-powered battery so that the device requires as less upkeep as possible to maintain the requirements of the City of Greater Bendigo Council.<br><br>\
\
The data from the device is processed and stored onsite then transferred to “The Things Network” every 6 seconds. Hence, the visualization website is populated with new data every time user refreshes the page.<br><br>\
\
The device does not use any form of imaging techniques so as to maintain the public’s privacy and uses the sensors to determine the velocity, differentiating whether it’s a pedestrian or cyclist.\
    "
  };
</script>
</body>
</html> 
