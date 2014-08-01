<?php
if(isset($_POST['cu_ticket_seller'])){
	$ticket_seller=$_POST['cu_ticket_seller'];
	$unit=$_POST['unit'];
	$_SESSION['ticket_seller']=$ticket_seller;
	$_SESSION['unit']=$unit;

	

	$station=$_POST['station'];
	$control_id=$_SESSION['control_id'];
	
	$update="update control_slip set ticket_seller='".$ticket_seller."', unit='".$unit."', station='".$station."' where id='".$control_id."'";
	
	$updateRS=$db->query($update);

}

?>
<?php
if(isset($_POST['reference_id'])){
	$control_id=$_SESSION['control_id'];
	$sql="update control_slip set reference_id='".$_POST['reference_id']."' where id='".$control_id."'";
	$rs=$db->query($sql);

}

if(isset($_GET['edit_control'])){
	$control_id=$_GET['edit_control'];
	$sql="select * from control_slip where id='".$control_id."'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
	$reference_id=$row['reference_id'];
	
	$unit=$row['unit'];
	$ticket_seller=$row['ticket_seller'];
	
	$_SESSION['control_id']=$control_id;
	
	$_SESSION['unit']=$unit;
	$_SESSION['ticket_seller']=$ticket_seller;	
	
	$update="update control_slip set status='open' where id='".$control_id."'";
	$updateRS=$db->query($update);
	
	$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	if($nm==0){
		$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
		$updateRS=$db->query($update);
	}
	
	$control_id=$_GET['getCashAdvance'];
	
	$sql="select sum(total) as total from cash_transfer where control_id='".$control_id."' and type in ('allocation')";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cash_advance=$row['total'];
	}
	
	
	
	
	

//	$control_id=$_SESSION['control_id'];


	
	
}
	$control_id=$_SESSION['control_id'];
	$unit=$_SESSION['unit'];
	$ticket_seller=$_SESSION['ticket_seller'];

	$stationSQL="select * from control_slip inner join station on station.id=control_slip.station where control_slip.id='".$control_id."'";
	$stationRS=$db->query($stationSQL);
	$stationRow=$stationRS->fetch_assoc();
	$stationName=$stationRow['station_name'];

	$ticketSellerName=$stationRow['ticket_seller'];

	$sql="select * from ticket_seller where id='".$ticket_seller."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();

	$control_label=strtoupper($row['first_name']." ".$row['last_name'])." - ".$unit." (".$stationName.")";

if(isset($_POST['stat_control'])){
	
	$db=new mysqli("localhost","root","","finance");
	$control_id=$_SESSION['control_id'];
	
	if(isset($_POST['control_slip_status'])){
		$status='open';
		
	}
	else {
	
		$status='closed';
	}
	
	$sql="update control_slip set status='".$status."' where id='".$control_id."'";
	$rs=$db->query($sql);
	if($status=="closed"){
		$statusMessage="closed";

	}
	else {
		$statusMessage="open";

	}
}

$statusSlip="select * from control_slip where id='".$control_id."'";

$statusRS=$db->query($statusSlip);
$statusNM=$statusRS->num_rows;
if($statusNM>0){
	$statusRow=$statusRS->fetch_assoc();
	if($statusRow['status']=="closed"){
		$statusMessage="closed";

	}
	else {
		$statusMessage="open";

	}
}
else {
	$statusMessage="open";

}
?>

    <div class="contentTop">
        <span class="pageTitle"><?php echo $control_label; ?> <a href='#' title='Change User' name='open_change' id='open_change'><span class="iconb" data-icon='&#xe03d;'></span></a></span>
        <ul class="quickStats">
            <li class="formRow open_close">
                <div class="floatR mr10"><form id='status_form' name='status_form' action='test_control_slip.php' method=post><input type="checkbox" id="control_slip_status"  name='control_slip_status' <?php if($statusMessage=="open"){ echo "checked='checked'"; }else { echo ""; } ?> ><input type='hidden' name='stat_control' value='<?php echo $control_id; ?>' /></form></div>
				
			</li>
        </ul>
        <div class="clear"></div>
    </div>
						
						
	
	   <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
				<li><a href="#" name='open_reference' id='open_reference' title="Edit/Add"><span>Reference ID: <?php echo $reference_id; ?> </span></a></li>	
            </ul>
        </div>
    </div>
 


	
						
						
						
						

						<div id="control_user_modal" name='control_user_modal' title="Change User" style='display:none;'>
							<form name='change_user_form' id='change_user_form' action='test_control_slip.php' method='post'>		
							 <div class="dialogSelect m10 searchDrop">
                                    <label>Ticket Seller</label>
                                    <select name="cu_ticket_seller" class='select' style='width:200px;' >
										<?php
										$db=new mysqli("localhost","root","","finance");
										$sql="select * from ticket_seller order by last_name";
										$rs=$db->query($sql);
										$nm=$rs->num_rows;
										?>
										<?php 
										for($i=0;$i<$nm;$i++){
											$row=$rs->fetch_assoc();
										?>
											<option value='<?php echo $row['id']; ?>' <?php if($ticketsellerpost==$row['id']){ echo "selected"; } ?>><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
										<?php
										}
										?>
                                    </select>
                                </div>
                                <div class="dialogSelect m10">
                                    <label>Unit</label>
                                    <select name="unit" >
										<option <?php if($unit=="A/D1"){ echo "selected"; } ?> value='A/D1'>AD1</option>
										<option <?php if($unit=="A/D2"){ echo "selected"; } ?> value='A/D2'>AD2</option>
										<option <?php if($unit=="TIM1"){ echo "selected"; } ?> value='TIM1'>TIM1</option>
										<option <?php if($unit=="TIM2"){ echo "selected"; } ?> value='TIM2'>TIM2</option>
										<option <?php if($unit=="TIM3"){ echo "selected"; } ?> value='TIM3'>TIM3</option>
                                    </select>
                                </div>
                                <div class="dialogSelect m10">
                                    <label>Station</label>
                                    <select name="station" style='width:200px;'>
										<?php
										$db=new mysqli("localhost","root","","finance");
										$logSQL="select * from logbook where id='".$log_id."'";

										$logRS=$db->query($logSQL);
										$logNM=$logRS->num_rows;
										if($logNM>0){
										$logRow=$logRS->fetch_assoc();
										$cash_assistant=$logRow['cash_assistant'];


										$stationSQL="select * from station where id='".$logRow['station']."'";
										$stationRS=$db->query($stationSQL);
										$stationRow=$stationRS->fetch_assoc();
										$station_name=$stationRow['station_name'];
										$station_id=$stationRow['id'];

										}
										?>
										<option value='<?php echo $station_id; ?>'><?php echo $station_name; ?></option>
										<?php
										$extensionSQL="select * from extension inner join station on extension.extension=station.id where extension.station='".$logRow['station']."'";
										$extensionRS=$db->query($extensionSQL);
										$extensionNM=$extensionRS->num_rows;
										if($extensionNM>0){
											$extensionRow=$extensionRS->fetch_assoc();
											$extensionID=$extensionRow['extension'];
											$extensionName=$extensionRow['station_name'];
										?>
										<option value='<?php echo $extensionID; ?>'><?php echo $extensionName; ?></option>
										<?php
										}
										?>
                                    </select>
                                </div>

	
								</form>
						</div>
						<div id="reference_modal" name='reference_modal' title="Change Reference ID" style='display:none;'>
							<form name='ref_form' id='ref_form' action='test_control_slip.php' method='post'>		
								<label>New Reference ID</label>
                                <input type=text name='reference_id' />
	
							</form>
						</div>
	
	
	
	
	