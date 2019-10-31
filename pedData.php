 <?php

header('Content-Type: application/json');
include("connect.php");

$query = sprintf('select p.hour, p.pedCount, c.cycCount FROM pedestrian as p, cyclist as c
Where p.date = Date(NOW()) and c.date = Date(NOW()) and p.hour=c.hour
group by p.hour, c.hour');

$result = $mysqli->query($query);

$data = array();
foreach ($result as $row) {
 	$data[] = $row;
 }

 $result->close();

 $mysqli->close();

 print json_encode($data); 

 ?>