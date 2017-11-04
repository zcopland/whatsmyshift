<?php
session_start();
include('config.php');

$type = $_POST['type'];
$companyID = $_SESSION['companyID'];

if ($type == 'new') {
	$startdate = $_POST['startdate'].'+'.$_POST['zone'];
	$title = $_POST['title'];
	date_default_timezone_set('America/New_York');
	$date = date("Y-m-d");
	$insert = mysqli_query($con,"INSERT INTO calendar_{$companyID}(`title`, `startdate`, `enddate`, `allDay`, `dateStamp`) VALUES('{$title}','{$startdate}','{$startdate}','true', '{$date}')"); //change to true to toggle all day default events
	$lastid = mysqli_insert_id($con);
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if ($type == 'changetitle') {
	$eventid = $_POST['eventid'];
	$title = $_POST['title'];
	$update = mysqli_query($con,"UPDATE calendar_{$companyID} SET title='{$title}' where id='{$eventid}'");
	if ($update) {
    	echo json_encode(array('status'=>'success'));
	} else {
    	echo json_encode(array('status'=>'failed'));
	}
}

if ($type == 'resetdate') {
	$title = $_POST['title'];
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
	$eventid = $_POST['eventid'];
	$update = mysqli_query($con,"UPDATE calendar_{$companyID} SET title='{$title}', startdate = '{$startdate}', enddate = '{$enddate}' where id='{$eventid}'");
	if ($update) {
    	echo json_encode(array('status'=>'success'));
	} else {
    	echo json_encode(array('status'=>'failed'));
	}
}

if ($type == 'remove') {
	$eventid = $_POST['eventid'];
	$delete = mysqli_query($con,"DELETE FROM calendar_{$companyID} where id='{$eventid}'");
	if ($delete) {
    	echo json_encode(array('status'=>'success'));
	} else {
    	echo json_encode(array('status'=>'failed'));
	}
}

if ($type == 'fetch') {
	$events = array();
	$query = mysqli_query($con, "SELECT * FROM calendar_{$companyID}");
	while ($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
	$e = array();
    $e['id'] = $fetch['id'];
    $e['title'] = $fetch['title'];
    $e['start'] = $fetch['startdate'];
    $e['end'] = $fetch['enddate'];

    $allday = ($fetch['allDay'] == "true") ? true : false;
    $e['allDay'] = $allday;

    array_push($events, $e);
	}
	echo json_encode($events);
}


?>