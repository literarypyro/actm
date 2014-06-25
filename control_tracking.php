<?php
session_start();
?>
<?php
$db=new mysqli("localhost","root","","finance");
?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<script language='javascript' src='ajax.js'></script>
<script language='javascript'>
var control_id="";

var cash_advance=0;

var sjt_allocate=0;
var sjd_allocate=0;
var svt_allocate=0;
var svd_allocate=0;

var sjt_allocate_loose=0;
var sjd_allocate_loose=0;
var svt_allocate_loose=0;
var svd_allocate_loose=0;


function removeLogbook(control,log_id){
	var check=confirm("Remove this Shift/Logbook from the Tracking?");
	if(check){
		control_id=control;
		makeajax("processing.php?removeLogbook="+log_id+"&control="+control,"reloadPage");
		
	}
	
}
function reloadPage(ajaxHTML){
	
	self.location="control_tracking.php?control_track="+control_id;
}

function trackLog(station,control){
	var check=confirm("Track this Shift/Logbook in the Control Slip?");
	if(check){
		control_id=control;
		var month=document.getElementById('month').value;
		var day=document.getElementById('day').value;
		var year=document.getElementById('year').value;
		
		var shift=document.getElementById('shift').value;
		
		var revenue=document.getElementById('revenue').value;
		
		var track_date=year+"-"+month+"-"+day;
		
		makeajax("processing.php?control="+control_id+"&track_date="+track_date+"&shift="+shift+"&station="+station+"&revenue="+revenue,"verifyAdd");

		
	}
}

function verifyAdd(ajaxHTML){
	if(ajaxHTML=="none"){
		alert("Logbook doesn't exist!");
	
	}
	else if(ajaxHTML=="existing"){
		alert("Logbook already tracked");
	}
	else {
		self.location="control_tracking.php?control_track="+control_id;
	}
	
}

function updateControlSlip(){

	window.opener.document.getElementById('sjt_allocation_b').value=sjt_allocate;
	window.opener.document.getElementById('sjd_allocation_b').value=sjd_allocate;
	window.opener.document.getElementById('svt_allocation_b').value=svt_allocate;
	window.opener.document.getElementById('svd_allocation_b').value=svd_allocate;

	window.opener.document.getElementById('sjt_allocation_b_loose').value=sjt_allocate_loose;
	window.opener.document.getElementById('sjd_allocation_b_loose').value=sjd_allocate_loose;
	window.opener.document.getElementById('svt_allocation_b_loose').value=svt_allocate_loose;
	window.opener.document.getElementById('svd_allocation_b_loose').value=svd_allocate_loose;
	
	window.opener.document.getElementById('addition_1').value=cash_advance;
	
	
	window.opener.computeTotal('sjt');
	window.opener.computeTotal('sjd');
	window.opener.computeTotal('svt');
	window.opener.computeTotal('svd');
	
	window.opener.computeSubTotal('_allocation_b');
	window.opener.computeSubTotal('_allocation_b_loose');
	
	window.opener.computeRemittance();
	
	
}



</script>
<?php
if($_GET['control_track']){
	$station_id=$_SESSION['station'];	
	$control_id=$_GET['control_track'];

	$controlSQL="select * from control_slip where id='".$control_id."' limit 1";
	$controlRS=$db->query($controlSQL);
	
	$controlRow=$controlRS->fetch_assoc();
	
	$control_unit=$controlRow['unit'];
	$control_station=$controlRow['station'];
	
	$ticket_seller=$controlRow['ticket_seller'];


	$station_sql="select * from station";
	$station_rs=$db->query($station_sql);
	$station_nm=$station_rs->num_rows;
	
	for($i=0;$i<$station_nm;$i++){
		$station_row=$station_rs->fetch_assoc();
	
		$station["Station_".$station_row['id']]=$station_row['station_name'];
	
	}
	
	
	$trackingSQL="select * from control_tracking where control_id='".$control_id."'";
	$trackingRS=$db->query($trackingSQL);
	$trackingNM=$trackingRS->num_rows;

	echo "<h2>Control Tracking</h2>";
	echo "<br><br>";
	
	$cash_advance=0;
	
	$allocation['sjt']=0;
	$allocation['sjd']=0;
	$allocation['svt']=0;
	$allocation['svd']=0;

	$allocation_loose['sjt']=0;
	$allocation_loose['sjd']=0;
	$allocation_loose['svt']=0;
	$allocation_loose['svd']=0;


	
	
	for($i=0;$i<$trackingNM;$i++){
		$trackingRow=$trackingRS->fetch_assoc();
		
		$logSQL="select * from logbook where id='".$trackingRow['log_id']."' limit 1";
		$logRS=$db->query($logSQL);
		
		$logRow=$logRS->fetch_assoc();
		
		$shift=$logRow['shift'];
		$log_date=date("F d, Y",strtotime($logRow['date']));
		$revenue=$logRow['revenue'];
		
		if($shift=="3"){ $shift.=" (".$revenue.")"; }
		
		echo "<table width=100%>";
		echo "<tr>";
		echo "<td>Date: ";
		echo $log_date;
		echo "</td>";
		echo "<td>Shift: ";
		echo $shift;
		echo " <a href='#' style='text-decoration:none' onclick=\"removeLogbook('".$control_id."','".$logRow['id']."')\" >X</a>";
		
		
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width=30% valign=top>";
			echo "<table border=1px width=100% class='controlTable'>";
			echo "<tr class='header'>";
			echo "<th colspan=4>Cash Transactions (Allocations)</th>";
			echo "</tr>";	

			echo "<tr class='subheader'>";
			echo "<th>Time</th>";
			echo "<th>Unit</th>";
			echo "<th>For Deposit</th>";

			echo "<th>&nbsp;</th>";
			echo "</tr>";
			
//$sql="select sum(total) as total from cash_transfer where log_id in (select log_id from control_tracking where control_id='".$control_id."') and ticket_seller='".$ticket_seller."' and unit='".$unit."' and type in ('allocation')";


			$sql="select * from cash_transfer where log_id='".$trackingRow['log_id']."' and ticket_seller='".$ticket_seller."' and type in ('allocation')";


			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				for($k=0;$k<$nm;$k++){
				
				
				
				$row=$rs->fetch_assoc();
			//$cash_advance=$row['total'];
			//$cash_revenue_3+=$cash_advance;
				$cash_transfer_time=date("Hi",strtotime($row['time']));		
				
				$cash_advance+=$row['total']*1;
				
				
				echo "<tr";
				
				if($k%2==0){
					echo " class='grid' ";

				}
				else {
					echo " class='category' ";

				}

				echo ">";
				echo "<td align=center>".$cash_transfer_time."</td>";		
				echo "<td align=center>".$row['unit']."</td>";
				echo "<td align=center>".$row['total']."</td>";

				if(($row['unit']==$control_unit)&&($row['station']==$control_station)){
					echo "<td>&nbsp;</td>";
				}
				else {
					echo "<td>NOT INCLUDED</td>";
				
				}
				echo "</tr>";
				}
			}
				
				
			
			
			echo "</table>";
		echo "</td>";
		echo "<td width=70% valign=top>";
			echo "<table border=1px width=100% class='controlTable'>";
			echo "<tr class='header'>";
			echo "<th colspan=12>Ticket Transactions (Additional Allocations)</th>";
			echo "</tr>";	
			echo "<tr class='subheader'>";
			echo "<th rowspan=2>Time</th>";
			echo "<th rowspan=2>Unit</th>";
			echo "<th rowspan=2>Station</th>";
			echo "<th colspan=2>SJT</th>";
			echo "<th colspan=2>DSJT</th>";
			echo "<th colspan=2>SVT</th>";
			echo "<th colspan=2>DSVT</th>";
			echo "<th>&nbsp;</th>";
			echo "</tr>";	
			echo "<tr class='category'>";
			for($m=0;$m<4;$m++){
				echo "<th>Pieces</th>";
				echo "<th>Loose</th>";
			
			}
			echo "<th>&nbsp;</th>";

			echo "</tr>";

			//	$sql="select * from ticket_order inner join transaction on ticket_order.transaction_id=transaction.transaction_id where ticket_order.log_id='".$trackingRow['log_id']."' and ticket_order.ticket_seller='".$ticket_seller."' and unit='".$unit."' and log_type='ticket' and station='".$station."' and transaction_type='allocation'";
			$sql="select * from ticket_order inner join transaction on ticket_order.transaction_id=transaction.transaction_id where ticket_order.log_id='".$trackingRow['log_id']."' and ticket_order.ticket_seller='".$ticket_seller."' and log_type='ticket' and transaction_type='allocation'";
		
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			for($k=0;$k<$nm;$k++){	
				$row=$rs->fetch_assoc();
				$ticket_order_time=date("Hi",strtotime($row['time']));		
				
				$allocation['sjt']+=$row['sjt']*1;
				$allocation['sjd']+=$row['sjd']*1;
				$allocation['svt']+=$row['svt']*1;
				$allocation['svd']+=$row['svd']*1;


				$allocation_loose['sjt']+=$row['sjt_loose']*1;
				$allocation_loose['sjd']+=$row['sjd_loose']*1;
				$allocation_loose['svt']+=$row['svt_loose']*1;
				$allocation_loose['svd']+=$row['svd_loose']*1;


				echo "<tr";
				
				if($k%2==0){
					echo " class='grid' ";

				}
				else {
					echo " class='category' ";

				}

				echo ">";
				echo "<td>".$ticket_order_time."</td>";		
				echo "<td>".$row['unit']."</td>";
				echo "<td>".$station["Station_".$row['station']]."</td>";
				echo "<td>".$row['sjt']."</td>";
				echo "<td>".$row['sjt_loose']."</td>";

				echo "<td>".$row['sjd']."</td>";
				echo "<td>".$row['sjd_loose']."</td>";

				echo "<td>".$row['svt']."</td>";
				echo "<td>".$row['svt_loose']."</td>";

				echo "<td>".$row['svd']."</td>";
				echo "<td>".$row['svd_loose']."</td>";
				
				if(($row['unit']==$control_unit)&&($row['station']==$control_station)){
					echo "<td>&nbsp;</td>";
				}
				else {
					echo "<td>NOT INCLUDED</td>";
				
				}
				echo "</tr>";
			}
			
			
			
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		



		echo "</table>";
		echo "<br><br>";
	}
	
		echo "<table width=100%>";
		echo "<tr>";
		echo "<td width=30% valign=top>";
			echo "<table border=1px width=100% class='controlTable'>";
			echo "<tr class='header'>";
			echo "<th><b>Total Cash Advance:</b></th>";
			echo "<td>".$cash_advance."</td>";
			echo "</tr>";
			echo "</table>";

			
		echo "</td>";	
		echo "<td width=70% valign=top>";
			echo "<table border=1px width=100% class='controlTable'>";
			echo "<tr class='header'>";
			echo "<th colspan=8><b>Total Additional Ticket Allocation</b></th>";
			echo "</tr>";
			echo "<tr class='subheader'>";
			echo "<th colspan=2>SJT</th>";
			echo "<th colspan=2>DSJT</th>";
			echo "<th colspan=2>SVT</th>";
			echo "<th colspan=2>DSVT</th>";
			echo "</tr>";
			
			echo "<tr class='category'>";
			for($m=0;$m<4;$m++){
				echo "<th>Pieces</th>";
				echo "<th>Loose</th>";
			
			}
			echo "</tr>";
				
			echo "<tr class='grid'>";
			echo "<td align=center>".$allocation['sjt']."</td>";
			echo "<td align=center>".$allocation_loose['sjt']."</td>";

			echo "<td align=center>".$allocation['sjd']."</td>";
			echo "<td align=center>".$allocation_loose['sjd']."</td>";

			echo "<td align=center>".$allocation['svt']."</td>";
			echo "<td align=center>".$allocation_loose['svt']."</td>";

			echo "<td align=center>".$allocation['svd']."</td>";
			echo "<td align=center>".$allocation_loose['svd']."</td>";

			
			echo "</tr>";
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		echo "</table>";
		
		echo "<br><br>";
		echo "<table class='controlTable'>";
		echo "<tr class='header'>";
		echo "<th colspan=3>Track Logbook</th>";
		echo "</tr>";
		echo "<tr class='subheader'><th>Date</th><th>Shift</th><th>Revenue</th></tr>";
		echo "<tr class='grid'><td>";	
		echo "<select name='month' id='month'>";
		$mm=date("m");
		$yy=date("Y");
		$dd=date("d");

		$hh=date("h");

		$min=date("i");
		$aa=date("a");

		for($i=1;$i<13;$i++){
			echo "<option value='".$i."'";
			
			if($i==$mm){ echo " selected "; }
			
			echo ">";
			echo date("F",strtotime(date("Y")."-".$i."-01"));
			echo "</option>";
		}
		echo "</select>";
		echo "<select name='day' id='day'>";
		for($i=1;$i<=31;$i++){
			echo "<option value='".$i."'";
			
			if($i==$dd){ echo " selected "; }

			echo ">".$i."</option>";
		}
		echo "</select>";
		echo "<select name='year' id='year'>";
		$dateRecent=date("Y")*1+16;
		for($i=1999;$i<=$dateRecent;$i++){
			echo "<option value='".$i."'";
			if($i==$yy){ echo " selected "; }

			echo ">".$i."</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		echo "<select name='shift' id='shift'>";
		echo "<option value='1'>1 - 5:00am - 1:00pm</option>";
		echo "<option value='2'>2 - 1:00pm - 9:00pm</option>";
		echo "<option value='3'>3 - 9:00pm - 5:00am</option>";
		
		echo "</select>";
		echo "</td>";
		echo "<td>";
		echo "<select name='revenue' id='revenue'>";
		echo "<option value='open'>Open Revenue (New Day)</option>";
		echo "<option value='close'>Close Revenue</option>";
		echo "</select>";
		echo "</td>";
		
		
		echo "</tr>";
		echo "<tr class='subcategory'><th colspan=3><input type='button' value='Track' onclick=\"trackLog('".$station_id."','".$control_id."')\"></th></tr>";
		echo "</table>";
		
		
		echo "<script language='javascript'>";
		echo "cash_advance='".$cash_advance."';";
		
		echo "sjt_allocate='".$allocation['sjt']."';";
		echo "sjd_allocate='".$allocation['sjd']."';";
		echo "svt_allocate='".$allocation['svt']."';";
		echo "svd_allocate='".$allocation['svd']."';";

		echo "sjt_allocate_loose='".$allocation_loose['sjt']."';";
		echo "sjd_allocate_loose='".$allocation_loose['sjd']."';";
		echo "svt_allocate_loose='".$allocation_loose['svt']."';";
		echo "svd_allocate_loose='".$allocation_loose['svd']."';";

		echo "updateControlSlip();";
		
		echo "</script>";
		
		
}
?>