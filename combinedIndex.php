<?php
session_start();
include("connect.php");

$query = sprintf('select SUM(totalCount) from (select sum(p.pedCount) as totalCount from pedestrian as p where p.date = Date(NOW())
union ALL
select sum(c.cycCount) as c1 from cyclist as c where c.date = Date(NOW())) as totalcount');
$queryAvg = sprintf('Select Round(sum(s.totalCount)/4, 0) as combinedCount from sensor s where s.date BETWEEN (NOW() - INTERVAL 4 WEEK) AND NOW()');
$qCyclistLeft = "Select sum(c.leftCycCount) as totalLeftCyclist from cyclist c where c.date=Date(NOW())";
$qCyclistRight = "Select sum(c.rightCycCount) as totalRightCyclist from cyclist c where c.date=Date(NOW())";
$qPedestrianLeft = "Select sum(p.leftPed) as totalLeftPedestrian from pedestrian p where p.date=Date(NOW())";
$qPedestrianRight = "Select sum(p.rightPed) as totalRightPedestrian from pedestrian p where p.date=Date(NOW())";

$result = $mysqli->query($query);
$resultAvg = $mysqli->query($queryAvg);
$resultCLeft = $mysqli->query($qCyclistLeft);
$resultCRight = $mysqli->query($qCyclistRight);
$resultPLeft = $mysqli->query($qPedestrianLeft);
$resultPRight = $mysqli->query($qPedestrianRight);

if($result->num_rows > 0) {
	while($row = $result->fetch_array()){
		$cardTotalCount =  $row['SUM(totalCount)'];
		echo $cardTotalCount;
	}}

if ($resultAvg->num_rows > 0) {
	while ($rowAvg = $resultAvg->fetch_array()){
		$avgFourWeeks = $rowAvg['combinedCount'];
		echo $avgFourWeeks;
	}
}

if($resultCLeft->num_rows > 0){
	while ($rowCLeft = $resultCLeft -> fetch_array()){
		$totalLCyclist = $rowCLeft['totalLeftCyclist'];
		echo $totalLCyclist;
	}
}

if($resultCRight->num_rows > 0){
	while ($rowCRight = $resultCRight -> fetch_array()){
		$totalRCyclist = $rowCRight['totalRightCyclist'];
		echo $totalRCyclist;
	}
}

if($resultPLeft->num_rows > 0){
	while ($rowPLeft = $resultPLeft -> fetch_array()){
		$totalLPedestrian = $rowPLeft['totalLeftPedestrian'];
		echo $totalLPedestrian;
	}
}

if($resultPRight->num_rows > 0){
	while ($rowPRight = $resultPRight -> fetch_array()){
		$totalRPedestrian = $rowPRight['totalRightPedestrian'];
		echo $totalRPedestrian;
	}
}

$_SESSION['SUM(totalCount)'] = $cardTotalCount;
$_SESSION['combinedCount'] = $avgFourWeeks;
$_SESSION['totalLeftCyclist'] = $totalLCyclist;
$_SESSION['totalRightCyclist'] = $totalRCyclist;
$_SESSION['totalLeftPedestrian'] = $totalLPedestrian;
$_SESSION['totalRightPedestrian'] = $totalRPedestrian;
 $result->close();

 $mysqli->close();

 

 

 ?>





