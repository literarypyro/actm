<?php
session_start();
?>
<?php
$db=new mysqli("localhost","root","","finance");
?>
<?php
if(isset($_GET['deleteRemittance'])){
	$update="delete from remittance where id='".$_GET['deleteRemittance']."'";
	$updateRS=$db->query($update);
	
	echo "Data deleted.";
}
if(isset($_GET['removeLogbook'])){
	$log_id=$_GET['removeLogbook'];
	$control_id=$_GET['control'];
	
	$update="delete from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
	$updateRS=$db->query($update);
	
}

if(isset($_GET['track_date'])){
	$track_date=date("Y-m-d",strtotime($_GET['track_date']));
	$station=$_GET['station'];
	$revenue=$_GET['revenue'];
	$shift=$_GET['shift'];
	
	$control_id=$_GET['control'];
	
	$search="select * from logbook where date='".$track_date."' and station='".$station."' and revenue='".$revenue."' and shift='".$shift."' limit 1";
	$searchRS=$db->query($search);
	
	$searchNM=$searchRS->num_rows;
	
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		
		$count="select * from control_tracking where control_id='".$control_id."' and log_id='".$searchRow['id']."' limit 1";
		$countRS=$db->query($count);
		$countNM=$countRS->num_rows;
		
		if($countNM>0){
			echo "existing";
			
		}
		else {
			$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$searchRow['id']."')";
			$updateRS=$db->query($update);
			
			echo "added";
		
		}
	
	
	}
	else {
		echo "none";
	
	}
	
	
}
if(isset($_GET['getCashAdvance'])){
	$control_id=$_GET['getCashAdvance'];
	
	$sql="select sum(total) as total from cash_transfer where control_id='".$control_id."' and type in ('allocation')";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cash_advance=$row['total'];
	}
	echo $cash_advance;
}

if(isset($_GET['calculateDiscrepancy'])){
	$control_id=$_GET['calculateDiscrepancy'];
	$cash_total=$_GET['cash_total'];	
	
	$sql="select * from remittance where control_id='".$control_id."' limit 1";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$row=$rs->fetch_assoc();
	$control_remittance=$row['amount'];

	$partial_remittance=0;
	
	$sql="select sum(total+net_revenue) as partial_remittance from cash_transfer where control_id='".$control_id."' and type='partial_remittance'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$partial_remittance=$row['partial_remittance'];
	}
	
	$cash_remittance=($cash_total+$partial_remittance)*1;
	
	if($control_remittance==$cash_remittance){
		echo "none";
	}
	else {
		if($control_remittance>$cash_remittance){
			echo "shortage;";
			echo $control_remittance-$cash_remittance;
		
		}
		else if($control_remittance<$cash_remittance){
			echo "overage;";
			echo $cash_remittance-$control_remittance;
		}
	
	
	}
	
	
	
	
}	
?>
