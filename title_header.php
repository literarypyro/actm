<title>Automated Cash and Ticket Management System</title>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from logbook where id='".$log_id."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$logDate=date("F d, Y",strtotime($row['date']));
$logShift=$row['shift'];


$logUser=$row['cash_assistant'];

$logDayWeek=date("l",strtotime($row['date']));
$logST=$row['station'];


$stationSQL="select * from station where id='".$row['station']."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();

$logStation=$stationRow['station_name'];


$shiftSQL="select * from shift where shift_id='S".$logShift."'";
$shiftRS=$db->query($shiftSQL);
$shiftRow=$shiftRS->fetch_assoc();
$shiftName=$shiftRow['shift_name'];


$sql="select * from login where username='".$logUser."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
$row=$rs->fetch_assoc();

$logName=strtoupper($row['lastName']).", ".$row['firstName'];

}
else {
	$logName="No one has logged to this shift yet.";
}

$sql="select * from login where username='".$_SESSION['username']."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
$row=$rs->fetch_assoc();

$session_user=strtoupper($row['lastName']).", ".$row['firstName'];

}
?>
<div id="top2">
	<div class='wrapper'>

	</div>
	<div class="wrapper">
		
		<div class='logo'>
			<ul>
				<li><h4><a style='color:white;'  title="#" class=""><?php echo $logName; ?></a></h4></li>
			</ul>
		</div>
		<div class='center' align=center>
			<ul>
				<li><h2><a style='color:white;'  title="#" class="">Taft</a></h2></li>
			</ul>
		</div>

        <!-- Right top nav -->
        <div class="topNav">
            <ul class="">
                <li><h4><a style='color:white;' title="#" class=""><?php echo $logDate; ?></a></h4></li>
            </ul>
		</div>	
    </div>
	<br>
	<div style='height:20px;'></div>
	
	<div class="wrapper">

		<div class='logo2'>
			<ul>
				<li><h4><a style='color:white;'  title="#" class=""><?php echo $shiftName; ?></a></h4></li>
			</ul>
		</div>
		<!-- Right top nav -->
        <div class="topNav2">
            <ul class="">
                <li><h4><a style='color:white;'  title="#" class=""><?php echo $logDayWeek; ?></a></h4></li>
            </ul>
        </div>
        
    </div>

</div>
<div style='height:90px;'></div>
