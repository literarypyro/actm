<?php
session_start();
?>
<?php
$log_id=$_SESSION['log_id'];
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_GET['control_log'])){
	$log_id=$_GET['control_log'];


}
$db=new mysqli("localhost","root","","finance");
if(isset($_POST['change_control_id'])){
	$ticket_seller=$_POST['ticket_seller_change'];
	$control_id=$_POST['change_control_id'];
	$station=$_POST['station'];
	$unit=$_POST['unit'];
	
	$update="update control_slip set ticket_seller='".$ticket_seller."', unit='".$unit."', station='".$station."' where id='".$control_id."'";

	$updateRS=$db->query($update);

$_SESSION['unit']=$unit;
$_SESSION['ticket_seller']=$ticket_seller;	
	
}
if(isset($_POST['ticket_seller_control'])){

$ticket_seller=$_POST['ticket_seller_control'];
$unit=$_POST['unit'];
$station=$_POST['station'];

$_SESSION['unit']=$unit;
$_SESSION['ticket_seller']=$ticket_seller;

$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit."' and station='".$station."' and status='open' order by id desc";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$control_id="";
	if($nm>0){
		$row=$rs->fetch_assoc();
		$control_id=$row['id'];
		$_SESSION['control_id']=$control_id;

	}

	else if($nm==0) {
		$insert="insert into control_slip(log_id,ticket_seller,unit,station,status) values ('".$log_id."','".$ticket_seller."','".$unit."','".$station."','open')";
		$rsInsert=$db->query($insert);
		$control_id=$db->insert_id;
		$_SESSION['control_id']=$control_id;
		
	}
	
	
$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm==0){
	$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
	$updateRS=$db->query($update);
}
	
	
	

}
if(isset($_GET['edit_control'])){
	$control_id=$_GET['edit_control'];
	$sql="select * from control_slip where id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
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
	
}

if(isset($_POST['adjustment_control_id_a'])){
	$control_id=$_POST['adjustment_control_id_a'];

	$db=new mysqli("localhost","root","","finance");
	
//	$fare_adjustment=$_POST['adjustment_1'];
	$sjt_adjustment=$_POST['adjustment_2'];
	$sjd_adjustment=$_POST['adjustment_3'];
	$svt_adjustment=$_POST['adjustment_4'];
	$svd_adjustment=$_POST['adjustment_5'];
	$c_adjustment=$_POST['adjustment_6'];
	$ot_adjustment=$_POST['adjustment_7'];

	$sjt_adjustment_t=$_POST['adjustment_tickets_2'];
	$sjd_adjustment_t=$_POST['adjustment_tickets_3'];
	$svt_adjustment_t=$_POST['adjustment_tickets_4'];
	$svd_adjustment_t=$_POST['adjustment_tickets_5'];
	$c_adjustment_t=$_POST['adjustment_tickets_6'];
	$ot_adjustment_t=$_POST['adjustment_tickets_7'];


	
	$sql="select * from fare_adjustment where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$sql="insert into fare_adjustment(control_id,sjt,sjd,svt,svd,c,ot) values ";
		$sql.="('".$control_id."','".$sjt_adjustment."','".$sjd_adjustment."','".$svt_adjustment."','".$svd_adjustment."','".$c_adjustment."','".$ot_adjustment."')";
		$rs=$db->query($sql);

	}	
	else {
		$sql="update fare_adjustment set c='".$c_adjustment."',ot='".$ot_adjustment."',sjt='".$sjt_adjustment."',sjd='".$sjd_adjustment."',svt='".$svt_adjustment."',svd='".$svd_adjustment."' where control_id='".$control_id."'";
		$rs=$db->query($sql);	
	
	
	}

	$sql="select * from fare_adjustment_tickets where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$sql="insert into fare_adjustment_tickets(control_id,sjt,sjd,svt,svd,c,ot) values ";
		$sql.="('".$control_id."','".$sjt_adjustment_t."','".$sjd_adjustment_t."','".$svt_adjustment_t."','".$svd_adjustment_t."','".$c_adjustment_t."','".$ot_adjustment_t."')";
		$rs=$db->query($sql);

	}	
	else {
		$sql="update fare_adjustment_tickets set c='".$c_adjustment_t."',ot='".$ot_adjustment_t."',sjt='".$sjt_adjustment_t."',sjd='".$sjd_adjustment_t."',svt='".$svt_adjustment_t."',svd='".$svd_adjustment_t."' where control_id='".$control_id."'";
		$rs=$db->query($sql);	
	}


	

	$_SESSION['control_id']=$control_id;

}
if(isset($_POST['adjustments_2_control_id'])){
	$control_id=$_POST['adjustments_2_control_id'];
	$db=new mysqli("localhost","root","","finance");

	if($_POST['total_remittance']>0){
	
//	$add_others=$_POST['addition_3'];
//	$refund=$_POST['deduction_1'];
//	$discount=$_POST['deduction_3'];

	$cash_advance=$_POST['addition_1'];
	$overage=$_POST['addition_2'];
	$unpaid_shortage=$_POST['deduction_2'];
	
	$ot_amount=$_POST['addition_3'];
	
	$refund_sj=$_POST['refund_sj'];
	$refund_sv=$_POST['refund_sv'];	
	
	$refund_sj_amount=$_POST['refund_sj_amount'];
	$refund_sv_amount=$_POST['refund_sv_amount'];	

	
	
	$unreg_sj=$_POST['unreg_sj'];
	$unreg_sv=$_POST['unreg_sv'];
	
	$discount_sj=$_POST['discount_sj'];
	$discount_sv=$_POST['discount_sv'];	

	
	$sql="select * from control_slip where id='".$control_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$ticket_seller=$row['ticket_seller'];
	
	$sql="select * from refund where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$update="insert into refund(control_id,sj,sv,sj_amount,sv_amount) values ('".$control_id."','".$refund_sj."','".$refund_sv."','".$refund_sj_amount."','".$refund_sv_amount."')";
		$updateRS=$db->query($update);

	}
	else {
		$row=$rs->fetch_assoc();
		$update="update refund set sj='".$refund_sj."',sv='".$refund_sv."',sj_amount='".$refund_sj_amount."',sv_amount='".$refund_sv_amount."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
	}	
	
	
	$sql="select * from unreg_sale where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$update="insert into unreg_sale(control_id,sj,sv) values ('".$control_id."','".$unreg_sj."','".$unreg_sv."')";
		$updateRS=$db->query($update);
	}
	else {
		$row=$rs->fetch_assoc();
		$update="update unreg_sale set sj='".$unreg_sj."',sv='".$unreg_sv."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
	}
	

	$sql="select * from discount where control_id='".$control_id."'";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$update="insert into discount(control_id,sj,sv) values ('".$control_id."','".$discount_sj."','".$discount_sv."')";
		$updateRS=$db->query($update);
	}
	else {
		$row=$rs->fetch_assoc();
		$update="update discount set sj='".$discount_sj."',sv='".$discount_sv."' where id='".$row['id']."'";
		$updateRS=$db->query($update);

	}

	$sql="select * from control_cash where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm==0){
		$sql="insert into control_cash(control_id,unpaid_shortage,overage,cash_advance) values ";
		$sql.="('".$control_id."','".$unpaid_shortage."','".$overage."','".$cash_advance."')";
		$rs=$db->query($sql);
	}	
	else {
		$sql="update control_cash set unpaid_shortage='".$unpaid_shortage."',overage='".$overage."',cash_advance='".$cash_advance."' where control_id='".$control_id."'";
		$rs=$db->query($sql);	
	}	

	$_SESSION['control_id']=$control_id;
	
	$referenceSQL="select * from control_slip where id='".$control_id."'";
	$referenceRS=$db->query($referenceSQL);
	$referenceRow=$referenceRS->fetch_assoc();
	$reference_id=$referenceRow['reference_id'];
	

	
	$sql="select * from cash_remittance where log_id='".$log_id."' and ticket_seller='".$ticket_seller."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$total_remittance=$_POST['total_remittance'];
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$update="update cash_remittance set control_remittance='".$total_remittance."' where id='".$row['id']."'";
		$rs2=$db->query($update);
		
			
	}
	else {
		/*
		$date=date("Y-m-d H:i");
		$date_id=date("Ymd",strtotime($date));
		
		$update="insert into transaction(date,log_id,log_type,transaction_type,reference_id) ";
		$update.="values ('".$date."','".$log_id."','control','remittance','".$reference_id."')";
		$rs2=$db->query($update);
					
		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;		
		
		$update="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs2=$db->query($update);		
		*/
		/*
		$sql="insert into cash_transfer(log_id,time,ticket_seller,cash_assistant,type,";
		$sql.="transaction_id,total_in_words,total,net_revenue,station,reference_id) values ";
		$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$_POST['cash_assistant']."','".$type."',";
		$sql.="'".$transaction_id."','".$totalWords."','".$revolving."','".$net."','".$station_entry."','".$reference_id."')";
		*/
	
		$update="insert into cash_remittance(log_id,ticket_seller,control_remittance) values ";
		$update.="('".$log_id."','".$ticket_seller."','".$total_remittance."')";
		$rs2=$db->query($update);

	}
//	$update="update control_slip set status='close' where id='".$control_id."'";
//	$updateRS=$db->query($update);		
		$date=date("Y-m-d H:i");
	//	$ticket_seller=$_SESSION['ticket_seller'];

		$sql="select * from remittance where control_id='".$control_id."' and ticket_seller='".$ticket_seller."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$update="update remittance set log_id='".$log_id."' where id='".$row['id']."'";
			$updateRS=$db->query($update);		
		}
		else {
			$update="insert into remittance(log_id,control_id,ticket_seller,date) values ";
			$update.=" ('".$log_id."','".$control_id."','".$ticket_seller."','".$date."')";
			$updateRS=$db->query($update);		
		}



	echo "Remittance has been made.  It is now advisable to close the Control Slip.";
	}
	
}	
if(isset($_POST['ticket_control_id'])){
	$control_id=$_POST['ticket_control_id'];

	$_SESSION['control_id']=$control_id;
	
	$log_id=$_SESSION['log_id'];
	



	$db=new mysqli("localhost","root","","finance");

	$sjt_amount=$_POST['sjt_amount'];
	$sjd_amount=$_POST['sjd_amount'];
	$svt_amount=$_POST['svt_amount'];
	$svd_amount=$_POST['svd_amount'];
	
	$sjt_total=$_POST['sjt_total'];
	$sjd_total=$_POST['sjd_total'];
	$svt_total=$_POST['svt_total'];
	$svd_total=$_POST['svd_total'];
	
	
	$controlSQL="select * from control_slip where id='".$control_id."'";
	$controlRS=$db->query($controlSQL);
	$controlRow=$controlRS->fetch_assoc();
	//$log_id=$controlRow['log_id'];


	
	if($_POST['type_transact']=="ticket_amount"){
		$sql="select * from control_sales_amount where control_id='".$control_id."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		if($nm==0){
			$sql="insert into control_sales_amount(control_id,sjt,sjd,svt,svd) values ";
			$sql.="('".$control_id."','".$sjt_amount."','".$sjd_amount."','".$svt_amount."','".$svd_amount."')";
			$rs=$db->query($sql);
		}	
		else {
			$sql="update control_sales_amount set sjt='".$sjt_amount."',sjd='".$sjd_amount."',svt='".$svt_amount."',svd='".$svd_amount."' where control_id='".$control_id."'";
			$rs=$db->query($sql);	
		}

		$discount_sj=number_format($sjd_amount*.20,2);
		$discount_sv=number_format($svd_amount*.20,2);	


		
		$sql="select * from discount where control_id='".$control_id."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm==0){
			$update="insert into discount(control_id,sj,sv) values ('".$control_id."','".$discount_sj."','".$discount_sv."')";
			$updateRS=$db->query($update);
		}
		else {
			$row=$rs->fetch_assoc();
			$update="update discount set sj='".$discount_sj."',sv='".$discount_sv."' where id='".$row['id']."'";
			$updateRS=$db->query($update);

		}



	}
	else {
		$sql="select * from control_sold where control_id='".$control_id."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		if($nm==0){
			$sql="insert into control_sold(control_id,sjt,sjd,svt,svd) values ";
			$sql.="('".$control_id."','".$sjt_total."','".$sjd_total."','".$svt_total."','".$svd_total."')";

			$rs=$db->query($sql);
		}	
		else {
			$sql="update control_sold set sjt='".$sjt_total."',sjd='".$sjd_total."',svt='".$svt_total."',svd='".$svd_total."' where control_id='".$control_id."'";
			
			$rs=$db->query($sql);	
		}
	
	
	
	}
	
	
	
	$tickets[0]="sjt";
	$tickets[1]="sjd";
	$tickets[2]="svt";
	$tickets[3]="svd";	
	
	
	$initial_type=$_POST['type_transact'];
	
	if($initial_type=="allocation"){
		$sql="select * from allocation where control_id='".$control_id."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$transaction_no=$row['id'];
			$transaction_id=$row['transaction_id'];			
		
		}
		else {
			$date=date("Y-m-d H:i");
			$date_id=date("Ymd");
			
			$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','".$initial_type."')";
			
			$rsInsert=$db->query($transactionInsert);
						
			$insert_id=$db->insert_id;
					
			$transaction_id=$date_id."_".$insert_id;
			$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
			$rs=$db->query($sql);					


			$transaction_no=$insert_id;		
		
		}
		
		for($i=0;$i<count($tickets);$i++){		
			if(($_POST[$tickets[$i]."_allocation_a"]=="")&&($_POST[$tickets[$i]."_allocation_b"]=="")){

			}
			else {
				$initial=$_POST[$tickets[$i]."_allocation_a"];
				$additional=$_POST[$tickets[$i]."_allocation_b"];
				$initial_loose=$_POST[$tickets[$i]."_allocation_a_loose"];
				$additional_loose=$_POST[$tickets[$i]."_allocation_b_loose"];
				
				
				$sql="select * from allocation where control_id='".$control_id."' and type='".$tickets[$i]."'";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				
				if($nm==0){
					$sql="insert into allocation(control_id,type,initial,additional,initial_loose,additional_loose,transaction_id) values ";
					$sql.="('".$control_id."','".$tickets[$i]."','".$initial."','".$additional."','".$initial_loose."','".$additional_loose."','".$transaction_id."')";
					$rs=$db->query($sql);

				}
				else {
					$sql="update allocation set initial='".$initial."',initial_loose='".$initial_loose."' where control_id='".$control_id."' and type='".$tickets[$i]."'";
					
					$rs=$db->query($sql);	

				}		
			}	
		}		
	}
	else if($initial_type=="remittance"){	
		$sql="select * from control_unsold where control_id='".$control_id."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$transaction_no=$row['id'];
			$transaction_id=$row['transaction_id'];			
		
		}
		else {
			$date=date("Y-m-d H:i");
			$date_id=date("Ymd");
			
			$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','".$initial_type."')";

			$rsInsert=$db->query($transactionInsert);
						
			$insert_id=$db->insert_id;
					
			$transaction_id=$date_id."_".$insert_id;
			$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
			$rs=$db->query($sql);					


			$transaction_no=$insert_id;		
		
		}
		for($i=0;$i<count($tickets);$i++){			
			if(($_POST[$tickets[$i]."_unsold_a"]=="")&&($_POST[$tickets[$i]."_unsold_b"]=="")&&($_POST[$tickets[$i]."_unsold_c"]=="")){

			}
			else {
			
				$sealed=$_POST[$tickets[$i]."_unsold_a"];
				$loose_good=$_POST[$tickets[$i]."_unsold_b"];
				$loose_defective=$_POST[$tickets[$i]."_unsold_c"];

				
				$sql="select * from control_unsold where control_id='".$control_id."' and type='".$tickets[$i]."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				
				if($nm==0){
					$sql="insert into control_unsold(control_id,type,sealed,loose_good,loose_defective,transaction_id) values ";
					$sql.="('".$control_id."','".$tickets[$i]."','".$sealed."','".$loose_good."','".$loose_defective."','".$transaction_id."')";
					$rs=$db->query($sql);

				}
				else {
					$sql="update control_unsold set sealed='".$sealed."', loose_good='".$loose_good."', loose_defective='".$loose_defective."' where control_id='".$control_id."' and type='".$tickets[$i]."'";
					$rs=$db->query($sql);	
				}
			
			}		
		}

	$sql="select * from control_slip where id='".$control_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$ticket_seller=$row['ticket_seller'];				
		
		
		$date=date("Y-m-d H:i");
		//$ticket_seller=$_SESSION['ticket_seller'];

		$sql="select * from remittance where control_id='".$control_id."' and ticket_seller='".$ticket_seller."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$update="update remittance set log_id='".$log_id."' where id='".$row['id']."'";
			$updateRS=$db->query($update);		
		}
		else {
			$update="insert into remittance(log_id,control_id,ticket_seller,date) values ";
			$update.=" ('".$log_id."','".$control_id."','".$ticket_seller."','".$date."')";
			$updateRS=$db->query($update);		
		}

		
	}
	else if($initial_type=="reference"){
		$sql="update control_slip set reference_id='".$_POST['reference_id']."' where id='".$_POST['reference_control']."'";
		$rs=$db->query($sql);	
	
	}
		
	
}

$statusSlip="select * from control_slip where id='".$control_id."'";

$statusRS=$db->query($statusSlip);
$statusNM=$statusRS->num_rows;
if($statusNM>0){
	$statusRow=$statusRS->fetch_assoc();

	if($statusRow['status']=="close"){
		$statusMessage="The Control Slip is closed";

	}
	else {
		$statusMessage="The Control Slip is open";

	}
}
else {
	$statusMessage="The Control Slip is open";

}
if(isset($_POST['status_control'])){
	$db=new mysqli("localhost","root","","finance");
	$control_id=$_POST['status_control'];
	$sql="update control_slip set status='".$_POST['status']."' where id='".$control_id."'";
	$rs=$db->query($sql);
	if($_POST['status']=="close"){
		$statusMessage="The Control Slip is closed";

	}
	else {
		$statusMessage="The Control Slip is open";

	}
}
?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<script language=javascript>
function computeSequence(type,column,e,nextField){
	computeTotal(type);
	computeSubTotal(column);
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	

}

function computeTotal(type){
	var allocationTotal;
	var excessTotal;

	allocationTotal=document.getElementById(type+'_allocation_a').value*1+document.getElementById(type+'_allocation_a_loose').value*1+document.getElementById(type+'_allocation_b').value*1+document.getElementById(type+'_allocation_b_loose').value*1;
	excessTotal=document.getElementById(type+'_unsold_a').value*1+document.getElementById(type+'_unsold_b').value*1+document.getElementById(type+'_unsold_c').value*1;

	document.getElementById(type+'_total').value=allocationTotal*1-excessTotal*1;


}
function computeDiscount(type,amount){
	var ticketType=type;
	var ticketAmount=amount;
	
	
	if(type=='sjt'){
	var ticketA=document.getElementById("sjd_amount").value;
	document.getElementById('discount_sj').value=Math.round(ticketA*.20,2);
	}
	else if(type=='svt'){
	var ticketA=document.getElementById("svd_amount").value;
	document.getElementById('discount_sv').value=ticketA*.20;
	}
}

function searchTicketSeller(tName){
	var xmlHttp;
	var caHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			caHTML=xmlHttp.responseText;


			if(caHTML=="None available"){
				//document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var caTerms=caHTML.split("==>");
				
				var count=(caTerms.length)*1-1;

				var optionsGrid="";

			
				optionsGrid+="<select name='ticket_seller_control' id='ticket_seller_control'>";				
				

				for(var n=0;n<count;n++){
					var parts=caTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
					
				}
				optionsGrid+="</select>";
					
				document.getElementById('cafill').innerHTML=optionsGrid;
				//document.getElementById('programpageNumber').value="";
			}

		}
	} 
	

	xmlHttp.open("GET","process search.php?searchTS="+tName,true);
	xmlHttp.send();	
}
function computeSubTotal(column){
	var suffix="";
	suffix=column;
	var total=0;
	total+=document.getElementById('sjt'+suffix).value*1;
	total+=document.getElementById('sjd'+suffix).value*1;
	total+=document.getElementById('svt'+suffix).value*1;
	total+=document.getElementById('svd'+suffix).value*1;

	document.getElementById('total'+suffix).value=total*1;
	
	
	suffix="_total";
	
	total=0;
	total+=document.getElementById('sjt'+suffix).value*1;
	total+=document.getElementById('sjd'+suffix).value*1;
	total+=document.getElementById('svt'+suffix).value*1;
	total+=document.getElementById('svd'+suffix).value*1;

	document.getElementById('sold'+suffix).value=total*1;
		
	
}


function computeAmount(e,nextField){
	var totalAmount;
	
	computeDiscount("sjt",document.getElementById('sjt_amount').value*1);
	computeDiscount("svt",document.getElementById('svt_amount').value*1);
	
	
	totalAmount=document.getElementById('sjt_amount').value*1+document.getElementById('sjd_amount').value*1+document.getElementById('svt_amount').value*1+document.getElementById('svd_amount').value*1;
	document.getElementById('total_amount').value=totalAmount;
	document.getElementById('total_amount_display').value=document.getElementById('total_amount').value;
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
	}		
	computeCashRevenue();
}

function computeCashRevenue(){
	var totalRevenue=document.getElementById('total_amount').value*1;
	var subtotalRevenue=0;
	for(i=2;i<8;i++){
		subtotalRevenue+=(document.getElementById('adjustment_'+i).value*1);
		totalRevenue=totalRevenue+(document.getElementById('adjustment_'+i).value*1);
	}
	document.getElementById('cash_sub_total').value=subtotalRevenue;

	document.getElementById('cash_revenue_1').value=totalRevenue;
	document.getElementById('cash_revenue_2').value=totalRevenue;
	computeRemittance();
}
function computeTicketRevenue(){

	var subtotalAdjustment=0;
	for(i=2;i<8;i++){
		subtotalAdjustment+=(document.getElementById('adjustment_tickets_'+i).value*1);
	}
	document.getElementById('tickets_sub_total').value=subtotalAdjustment;
	
}

function computeRemittance(){
	
	var totalRemittance=document.getElementById('cash_revenue_1').value*1;
	var totalAdditions=0;
	var totalDeductions=0;
	for(i=1;i<3;i++){
		totalAdditions+=document.getElementById('addition_'+i).value*1;

	
	}
	totalAdditions+=document.getElementById('unreg_sj').value*1;
	totalAdditions+=document.getElementById('unreg_sv').value*1;

	
	
	totalDeductions+=document.getElementById('deduction_2').value*1;

	totalDeductions+=document.getElementById('refund_sj_amount').value*1;
	totalDeductions+=document.getElementById('refund_sv_amount').value*1;

	totalDeductions+=document.getElementById('discount_sj').value*1;
	totalDeductions+=document.getElementById('discount_sv').value*1;
	

	totalRemittance=(totalRemittance+totalAdditions)-totalDeductions;
	document.getElementById('total_remittance').value=totalRemittance;
}
function submitForm(){
	document.forms['control_form'].submit();
	window.opener.location.reload();
}
function focusHeader(header_option){
	document.getElementById('type_transact').value=header_option;
	
	highlightHeader();


}
function highlightHeader(){
	var option=document.getElementById('type_transact').value;

	if(option=='allocation'){
	
		document.getElementById('allocation_header').className="highlight";
	

		document.getElementById('unsold_header').className="header";
		

		document.getElementById('amount_header').className="header";
		
		
		
		document.getElementById('reference_header').className="header";
		
		
	}
	else if(option=='remittance'){
		document.getElementById('allocation_header').className="header";
		document.getElementById('unsold_header').className="highlight";
		document.getElementById('amount_header').className="header";
		document.getElementById('reference_header').className="header";


	}
	else if(option=='ticket_amount'){
		document.getElementById('allocation_header').className="header";
		document.getElementById('unsold_header').className="header";
		document.getElementById('amount_header').className="highlight";
		document.getElementById('reference_header').className="header";

	
	}
	else if(option=='reference'){
		document.getElementById('allocation_header').className="header";
		document.getElementById('unsold_header').className="header";
		document.getElementById('amount_header').className="header";
		document.getElementById('reference_header').className="highlight";

	}
	
	


//	var totalExit=0;
//	document.getElementById('row_'+rowNo).style.backgroundColor="red";
//	document.getElementById('row_'+rowNo).style.color="white";
//	for(i=0;i<=23;i++){
//		totalExit+=document.getElementById('h'+i+'_exit').value*1;
//	}
//	document.getElementById('total_exit').value=totalExit;

}
function submitStatus(){
	var status=document.getElementById('status').value;
	if(status=="close"){
		var check=confirm("Close the Control Slip? Please verify that all your data is correct.");
		
		if(check){
			document.forms['status_form'].submit();
			window.opener.location.reload();
		}
	}
	else {
		document.forms['status_form'].submit();
	
	}
}

function remitControlSlip(cSlip){
	var check=confirm("Do you still want to open the CTF to encode the Cash Remittance?");
	if(check){
		window.open("cash_transfer.php?cID="+cSlip,"transfer","height=800, width=500, scrollbars=yes");
	}
	document.forms['remittance_form'].submit();
}
</script>
<?php
$sql="select * from cash_remittance where log_id='".$log_id."' and ticket_seller='".$ticket_seller."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
}
else {

	$sql2="insert into cash_remittance(log_id,ticket_seller) values ('".$log_id."','".$ticket_seller."')";
	$rs2=$db->query($sql2);
}

?>
<?php
$unit=$_SESSION['unit'];
$ticket_seller=$_SESSION['ticket_seller'];
$control_id=$_SESSION['control_id'];

$stationSQL="select * from control_slip inner join station on station.id=control_slip.station where control_slip.id='".$control_id."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();
$stationName=$stationRow['station_name'];

$ticketSellerName=$stationRow['ticket_seller'];

$sql="select * from ticket_seller where id='".$ticket_seller."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

echo "<font color=red>".$statusMessage."</font><br>";
echo "<form id='status_form' name='status_form' action='control_slip.php' method='post'>";
echo "<table  class='controlTable3' width=100%>";
echo "<tr>";
echo "<td><b>".strtoupper($row['first_name']." ".$row['last_name'])." - ".$unit." (".$stationName.")</b></td>";
echo "<td align=right>Control Slip Status:";

echo "<select name='status' id='status'>";
echo "<option value='open'>Open</option>";
echo "<option value='close'>Close</option>";
echo "</select>";
echo "<input type=hidden name='status_control' id='status_control' value='".$control_id."' />";
echo "<input type=button value='Submit' onclick='submitStatus()' />";

echo "</td>";

echo "</tr>";
echo "</table>";

echo "</form>";
?>
<form id='control_form' name='control_form' action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post' >
<table width=100% class='controlTable2'>
<tr>
<td width=40%>
Present Transaction: 
<select name='type_transact' id='type_transact' onchange='highlightHeader()' >
<option value='reference'>Reference ID</option>
<option <?php if($_POST['type_transact']=="reference"){ echo "selected"; } ?> value='allocation'>Allocation</option>
<option <?php if($_POST['type_transact']=="allocation"){ echo "selected"; } ?> value='remittance'>Remittance</option>
<option <?php if($_POST['type_transact']=="remittance"){ echo "selected"; } ?> value='ticket_amount'>Ticket Amount</option>

</select>
</td>
<?php
$control_id=$_SESSION['control_id'];

$sql="select * from control_slip where id='".$control_id."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

?>
<td><span <?php if(isset($_POST['type_transact'])){ } else { echo "class='highlight'"; } ?> id='reference_header'  name='reference_header'><b> Reference Id (Control Slip No.)</b> </span> <input type='text' name='reference_id' id='reference_id'  onfocus='focusHeader("reference")'  value='<?php echo $row['reference_id']; ?>' /><input type=hidden name='reference_control' id='reference_control' value='<?php echo $control_id; ?>' /></td>
</tr>
</table>

<table width=100% class='controlTable'>
<tr class='header'>
<th rowspan=3>&nbsp;</th>
<th colspan=4 <?php if($_POST['type_transact']=="reference"){ echo  "class='highlight'"; } else { echo "class='header'"; } ?> id='allocation_header' name='allocation_header'>Allocation</th>
<th colspan=3 <?php if($_POST['type_transact']=="allocation"){ echo  "class='highlight'"; } else { echo "class='header'"; } ?> id='unsold_header' name='unsold_header'>Unsold/Excess</th>
<th rowspan=3>Sold</th>
<th rowspan=3 <?php if($_POST['type_transact']=="remittance"){ echo  "class='highlight'"; } else { echo "class='header'"; } ?> id='amount_header' name='amount_header'>Amount</th>
</tr>
<tr class='subheader'>
<th colspan=2>Initial</th>
<th colspan=2>Additional</th>
<th class='category' rowspan=2>Sealed</th>
<th colspan=2>Loose (pcs.)</th>
</tr>
<tr class='category'>
<th>Pieces</th>
<th>Loose</th>
<th>Pieces</th>
<th>Loose</th>

<th>Good</th>
<th>Defective</th>
</tr>
<?php

$total_allocation_a=0;
$total_allocation_b=0;
$total_allocation_a_loose=0;
$total_allocation_b_loose=0;


$total_unsold_a=0;
$total_unsold_b=0;
$total_unsold_c=0;


$total_sold=0;
$total_amount=0;


$control_sql="select * from control_slip where id='".$control_id."'";
$control_rs=$db->query($control_sql);
$control_row=$control_rs->fetch_assoc();

$control_log=$control_row['log_id'];
$station=$control_row['station'];

$sql="select * from allocation where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;

$allocationNM=$nm;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
	$allocation[$row['type']]["initial"]=$row['initial'];
	$total_allocation_a+=$allocation[$row['type']]["initial"];
	
	$allocation[$row['type']]["initial_loose"]=$row['initial_loose'];
	$total_allocation_a_loose+=$allocation[$row['type']]["initial_loose"];

	$allocation[$row['type']]["additional"]=$row['additional'];
	$allocation[$row['type']]["additional_loose"]=$row['additional_loose'];
	$allocation[$row['type']]["total"]=$row['initial']*1+$row['additional']*1+$row['initial_loose']*1+$row['additional_loose']*1;
	$total_allocation_b+=$allocation[$row['type']]["additional"];

	$total_allocation_b_loose+=$allocation[$row['type']]["additional_loose"];
}


$sql="select * from ticket_order where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;


$total_allocation_b=0;


$total_allocation_b_loose=0;


for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$allocation['sjt']['additional']+=$row['sjt'];
	$allocation['svt']['additional']+=$row['svt'];
	$allocation['sjd']['additional']+=$row['sjd'];
	$allocation['svd']['additional']+=$row['svd'];

	$allocation['sjt']['additional_loose']+=$row['sjt_loose'];
	$allocation['svt']['additional_loose']+=$row['svt_loose'];
	$allocation['sjd']['additional_loose']+=$row['sjd_loose'];
	$allocation['svd']['additional_loose']+=$row['svd_loose'];

	$total_allocation_b+=$allocation['sjt']["additional"];
	$total_allocation_b+=$allocation['sjd']["additional"];
	$total_allocation_b+=$allocation['svt']["additional"];
	$total_allocation_b+=$allocation['svd']["additional"];

	$total_allocation_b_loose+=$allocation['sjd']["additional_loose"];
	$total_allocation_b_loose+=$allocation['sjt']["additional_loose"];
	$total_allocation_b_loose+=$allocation['svd']["additional_loose"];
	$total_allocation_b_loose+=$allocation['svt']["additional_loose"];
}



$sql="update allocation set additional='".$allocation['sjt']['additional']."',additional_loose='".$allocation['sjt']['additional_loose']."' where control_id='".$control_id."' and 'sjt'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['svt']['additional']."',additional_loose='".$allocation['svt']['additional_loose']."' where control_id='".$control_id."' and 'svt'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['sjd']['additional']."',additional_loose='".$allocation['sjd']['additional_loose']."' where control_id='".$control_id."' and 'sjd'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['svd']['additional']."',additional_loose='".$allocation['svd']['additional_loose']."' where control_id='".$control_id."' and 'svd'";
$rs=$db->query($sql);


$sql="select * from control_unsold where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$unsoldNM=$nm;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
	$unsold[$row['type']]["sealed"]=$row['sealed'];
	$unsold[$row['type']]["loose_good"]=$row['loose_good'];
	$unsold[$row['type']]["loose_defective"]=$row['loose_defective'];

	$total_unsold_a+=$unsold[$row['type']]['sealed'];
	$total_unsold_b+=$unsold[$row['type']]['loose_good'];
	$total_unsold_c+=$unsold[$row['type']]['loose_defective'];

	$unsold[$row['type']]['total']=$row['sealed']*1+$row['loose_good']*1+$row['loose_defective']*1;
}




$sql="select * from control_sold where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$row=$rs->fetch_assoc();

if($unsoldNM==0){
	$sold_tickets["sjt"]=$allocation["sjt"]["initial"]+$allocation["sjt"]["initial_loose"]+$allocation['sjt']["additional"]+$allocation['sjt']["additional_loose"];
	$sold_tickets["sjd"]=$allocation["sjd"]["initial"]+$allocation["sjd"]["initial_loose"]+$allocation['sjd']["additional"]+$allocation['sjd']["additional_loose"];
	$sold_tickets["svt"]=$allocation["svt"]["initial"]+$allocation["svt"]["initial_loose"]+$allocation['svt']["additional"]+$allocation['svt']["additional_loose"];
	$sold_tickets["svd"]=$allocation["svd"]["initial"]+$allocation["svd"]["initial_loose"]+$allocation['svd']["additional"]+$allocation['svd']["additional_loose"];
	
}
else {
	$sold_tickets["sjt"]=$row['sjt']*1;
	$sold_tickets["sjd"]=$row['sjd']*1;
	$sold_tickets["svt"]=$row['svt']*1;
	$sold_tickets["svd"]=$row['svd']*1;

}

$sql="select * from discrepancy_ticket where transaction_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$discrepancyLabel['sjt']="";
$discrepancyLabel['sjd']="";
$discrepancyLabel['svt']="";
$discrepancyLabel['svd']="";


if($nm>0){
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		if(($row['amount']*1)>0){
			if($row['type']=="shortage"){
				$discrepancyLabel[$row['ticket_type']]="<font color=red>(-".$row['amount'].")</font>";		
				$sold_tickets[$row['ticket_type']]-=$row['amount'];

			}
			else if($row['type']=="overage"){
				$discrepancyLabel[$row['ticket_type']]="<font color=green>(+".$row['amount'].")</font>";		
				$sold_tickets[$row['ticket_type']]+=$row['amount'];
			}
		}	
	}		
}			
	
$total_sold+=$sold_tickets["sjt"];
$total_sold+=$sold_tickets["sjd"];
$total_sold+=$sold_tickets["svt"];
$total_sold+=$sold_tickets["svd"];

$db=new mysqli("localhost","root","","finance");
$sql="select * from control_sales_amount where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$sjt_amount=$row['sjt'];
	$sjd_amount=$row['sjd'];
	$svt_amount=$row['svt'];
	$svd_amount=$row['svd'];
	
	$cash_revenue_1=$sjt_amount*1+$sjd_amount*1+$svt_amount*1+$svd_amount*1;

	$total_amount+=$row['sjt'];
	$total_amount+=$row['sjd'];
	$total_amount+=$row['svt'];
	$total_amount+=$row['svd'];
}
else {
	$svt_amount=$sold_tickets["svt"]*100;
	$svd_amount=$sold_tickets["svd"]*100;

	$total_amount+=$row['svt'];
	$total_amount+=$row['svd'];
	
}


?>
<tr>
<td>SJT</td>
<td><input type=text size=5 name='sjt_allocation_a' id='sjt_allocation_a' onfocus='focusHeader("allocation")' onkeyup="computeSequence('sjt','_allocation_a',event,'sjd_allocation_a')" value='<?php echo $allocation["sjt"]["initial"]; ?>' /></td>
<td><input type=text size=5 name='sjt_allocation_a_loose' id='sjt_allocation_a_loose' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('sjt','_allocation_a_loose',event,'sjd_allocation_a_loose')" value='<?php echo $allocation["sjt"]["initial_loose"]; ?>' /></td>

<td><input type=text size=5 name='sjt_allocation_b' id='sjt_allocation_b' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('sjt','_allocation_b',event,'sjd_allocation_b_loose')" value='<?php echo $allocation["sjt"]["additional"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>
<td><input type=text size=5 name='sjt_allocation_b_loose' id='sjt_allocation_b_loose' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('sjt','_allocation_b_loose',event,'sjd_allocation_b_loose')" value='<?php echo $allocation["sjt"]["additional_loose"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>


<td><input type=text size=5 name='sjt_unsold_a' id='sjt_unsold_a' onkeyup="computeSequence('sjt','_unsold_a',event,'sjd_unsold_a')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["sjt"]["sealed"]; ?>'  /></td>
<td><input type=text size=5 name='sjt_unsold_b' id='sjt_unsold_b' onkeyup="computeSequence('sjt','_unsold_b',event,'sjd_unsold_b')" onfocus='focusHeader("remittance")'    value='<?php echo $unsold["sjt"]["loose_good"]; ?>' /></td>

<td><input type=text size=5 name='sjt_unsold_c' id='sjt_unsold_c' onkeyup="computeSequence('sjt','_unsold_c',event,'sjd_unsold_c')"  onfocus='focusHeader("remittance")'   value='<?php echo $unsold["sjt"]["loose_defective"]; ?>' /></td>
<td><?php echo $discrepancyLabel['sjt']; ?><input type=text name='sjt_total' id='sjt_total' value='<?php echo $sold_tickets["sjt"];?>' /></td>
<td><input type=text name='sjt_amount' id='sjt_amount' onkeyup='computeAmount(event,"sjd_amount")' value='<?php echo $sjt_amount; ?>'  onfocus='focusHeader("ticket_amount")'   onblur='computeAmount(event,"sjd_amount");' /></td>

</tr>


<tr>
<td>SJD</td>
<td><input type=text size=5 name='sjd_allocation_a' id='sjd_allocation_a' onkeyup="computeSequence('sjd','_allocation_a',event,'svt_allocation_a')"    onfocus='focusHeader("allocation")' value='<?php echo $allocation["sjd"]["initial"]; ?>' /></td>
<td><input type=text size=5 name='sjd_allocation_a_loose' id='sjd_allocation_a_loose' onkeyup="computeSequence('sjd','_allocation_a_loose',event,'svt_allocation_a_loose')"   onfocus='focusHeader("allocation")' value='<?php echo $allocation["sjd"]["initial_loose"]; ?>' /></td>

<td><input type=text size=5 name='sjd_allocation_b' id='sjd_allocation_b' onkeyup="computeSequence('sjd','_allocation_b',event,'svt_allocation_b')"  onfocus='focusHeader("allocation")'  value='<?php echo $allocation["sjd"]["additional"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>
<td><input type=text size=5 name='sjd_allocation_b_loose' id='sjd_allocation_b_loose' onkeyup="computeSequence('sjd','_allocation_b_loose',event,'svt_allocation_b_loose')"  onfocus='focusHeader("allocation")'  value='<?php echo $allocation["sjd"]["additional_loose"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>

<td><input type=text size=5 name='sjd_unsold_a' id='sjd_unsold_a'  onkeyup="computeSequence('sjd','_unsold_a',event,'svt_unsold_a')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["sjd"]["sealed"]; ?>' /></td>
<td><input type=text size=5 name='sjd_unsold_b' id='sjd_unsold_b' onkeyup="computeSequence('sjd','_unsold_b',event,'svt_unsold_b')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["sjd"]["loose_good"]; ?>' /></td>

<td><input type=text size=5 name='sjd_unsold_c' id='sjd_unsold_c' onkeyup="computeSequence('sjd','_unsold_c',event,'svt_unsold_c')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["sjd"]["loose_defective"]; ?>' /></td>
<td><?php echo $discrepancyLabel['sjd']; ?><input type=text name='sjd_total' id='sjd_total' value='<?php echo $sold_tickets["sjd"];?>' /></td>
<td><input type=text name='sjd_amount' id='sjd_amount' onkeyup='computeAmount(event,"svt_amount")' value='<?php echo $sjd_amount; ?>' onfocus='focusHeader("ticket_amount")'    onblur='computeAmount(event,"svt_amount");'  /></td>

</tr>



<tr>
<td>SVT</td>
<td><input type=text size=5 name='svt_allocation_a' id='svt_allocation_a' onfocus='focusHeader("allocation")' onkeyup="computeSequence('svt','_allocation_a',event,'svd_allocation_a')" value='<?php echo $allocation["svt"]["initial"]; ?>' /></td>
<td><input type=text size=5 name='svt_allocation_a_loose' id='svt_allocation_a_loose' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('svt','_allocation_a_loose',event,'svd_allocation_a_loose')" value='<?php echo $allocation["svt"]["initial_loose"]; ?>' /></td>

<td><input type=text size=5 name='svt_allocation_b' id='svt_allocation_b' onkeyup="computeSequence('svt','_allocation_b',event,'svd_allocation_b')" onfocus='focusHeader("allocation")'  value='<?php echo $allocation["svt"]["additional"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>
<td><input type=text size=5 name='svt_allocation_b_loose' id='svt_allocation_b_loose' onkeyup="computeSequence('svt','_allocation_b_loose',event,'svd_allocation_a_loose')" onfocus='focusHeader("allocation")'  value='<?php echo $allocation["svt"]["additional_loose"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>

<td><input type=text size=5 name='svt_unsold_a' id='svt_unsold_a' onkeyup="computeSequence('svt','_unsold_a',event,'svd_unsold_a')" onfocus='focusHeader("remittance")'   value='<?php echo $unsold["svt"]["sealed"]; ?>'   /></td>
<td><input type=text size=5 name='svt_unsold_b' id='svt_unsold_b' onkeyup="computeSequence('svt','_unsold_b',event,'svd_unsold_b')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["svt"]["loose_good"]; ?>'   /></td>

<td><input type=text size=5 name='svt_unsold_c' id='svt_unsold_c' onkeyup="computeSequence('svt','_unsold_c',event,'svd_unsold_c')" onfocus='focusHeader("remittance")'   value='<?php echo $unsold["svt"]["loose_defective"]; ?>'  /></td>
<td><?php echo $discrepancyLabel['svt']; ?><input type=text name='svt_total' id='svt_total' value='<?php echo $sold_tickets["svt"];?>' /></td>
<td><input type=text name='svt_amount' id='svt_amount' onkeyup='computeAmount(event,"svd_amount")'  value='<?php echo $svt_amount; ?>' onblur='computeAmount(event,"svd_amount");'  onfocus='focusHeader("ticket_amount")'  /></td>

</tr>


<tr>
<td>SVD</td>
<td><input type=text size=5 name='svd_allocation_a' id='svd_allocation_a' onkeyup="computeSequence('svd','_allocation_a',event,'sjt_allocation_a_loose')"  onfocus='focusHeader("allocation")' value='<?php echo $allocation["svd"]["initial"]; ?>' /></td>
<td><input type=text size=5 name='svd_allocation_a_loose' id='svd_allocation_a_loose' onkeyup="computeSequence('svd','_allocation_a_loose',event,'svd_allocation_a_loose')"  onfocus='focusHeader("allocation")' value='<?php echo $allocation["svd"]["initial_loose"]; ?>' /></td>

<td><input type=text size=5 name='svd_allocation_b' id='svd_allocation_b' onkeyup="computeSequence('svd','_allocation_b',event,'sjt_allocation_b')"  onfocus='focusHeader("allocation")' value='<?php echo $allocation["svd"]["additional"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>
<td><input type=text size=5 name='svd_allocation_b_loose' id='svd_allocation_b_loose' onkeyup="computeSequence('svd','_allocation_b_loose',event,'svd_allocation_b_loose')"  onfocus='focusHeader("allocation")'  value='<?php echo $allocation["svd"]["additional_loose"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>

<td><input type=text size=5 name='svd_unsold_a' id='svd_unsold_a' onkeyup="computeSequence('svd','_unsold_a',event,'sjt_unsold_b')" onfocus='focusHeader("remittance")' value='<?php echo $unsold["svd"]["sealed"]; ?>'    /></td>
<td><input type=text size=5 name='svd_unsold_b' id='svd_unsold_b' onkeyup="computeSequence('svd','_unsold_b',event,'sjt_unsold_c')" onfocus='focusHeader("remittance")' value='<?php echo $unsold["svd"]["loose_good"]; ?>'   /></td>

<td><input type=text size=5 name='svd_unsold_c' id='svd_unsold_c' onkeyup="computeSequence('svd','_unsold_c',event,'svd_unsold_c')" onfocus='focusHeader("remittance")' value='<?php echo $unsold["svd"]["loose_defective"]; ?>'   /></td>
<td><?php echo $discrepancyLabel['svd']; ?><input type=text name='svd_total' id='svd_total' value='<?php echo $sold_tickets["svd"];?>' /></td>
<td><input type=text name='svd_amount' id='svd_amount' onkeyup='computeAmount(event,"svd_amount")'  value='<?php echo $svd_amount; ?>'  onfocus='focusHeader("ticket_amount")'  onblur='computeAmount(event,"svd_amount");'  /></td>

</tr>

<tr>
<td>Total</td>
<td><input type=text size=5 name='total_allocation_a' id='total_allocation_a' value='<?php echo $total_allocation_a; ?>' /></td>
<td><input type=text size=5 name='total_allocation_a_loose' id='total_allocation_a_loose'  value='<?php echo $total_allocation_a_loose; ?>' /></td>

<td><input type=text size=5 name='total_allocation_b' id='total_allocation_b'  value='<?php echo $total_allocation_b; ?>' /></td>
<td><input type=text size=5 name='total_allocation_b_loose' id='total_allocation_b_loose'  value='<?php echo $total_allocation_b_loose; ?>' /></td>

<td><input type=text size=5 name='total_unsold_a' id='total_unsold_a'  value='<?php echo $total_unsold_a; ?>' /></td>
<td><input type=text size=5 name='total_unsold_b' id='total_unsold_b'  value='<?php echo $total_unsold_b; ?>' /></td>
<td><input type=text size=5 name='total_unsold_c' id='total_unsold_c'  value='<?php echo $total_unsold_c; ?>' /></td>
<td><input type=text name='sold_total' id='sold_total'  value='<?php echo $total_sold; ?>' /></td>
<td><input type=text name='total_amount_display' id='total_amount_display'  value='<?php echo $total_amount; ?>' /></td>
</tr>
</table>
<div align=center><input type=hidden name='ticket_control_id' value='<?php echo $control_id; ?>' />
</div>
<div align=center>
<input type=button onclick='submitForm()' value='Save Ticket Info and Sales' /></div>
</form>


<?php
$sql="select * from fare_adjustment where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	//$fare_adjustment=$row['fare_adjustment'];
	$sjt_adjustment=$row['sjt'];
	$sjd_adjustment=$row['sjd'];
	$svt_adjustment=$row['svt'];
	$svd_adjustment=$row['svd'];
	$c_adjustment=$row['c'];
	$ot_adjustment=$row['ot'];
	
	$cash_adjustments=0;
	//$cash_adjustments+=$fare_adjustment*1;
	$cash_adjustments+=$sjt_adjustment*1;
	$cash_adjustments+=$sjd_adjustment*1;
	$cash_adjustments+=$svt_adjustment*1;
	$cash_adjustments+=$svd_adjustment*1;
	$cash_adjustments+=$c_adjustment*1;
	$cash_adjustments+=$ot_adjustment*1;
	
	
	
	
}
$sql="select * from fare_adjustment_tickets where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	//$fare_adjustment=$row['fare_adjustment'];
	$sjt_adjustment_t=$row['sjt'];
	$sjd_adjustment_t=$row['sjd'];
	$svt_adjustment_t=$row['svt'];
	$svd_adjustment_t=$row['svd'];
	$c_adjustment_t=$row['c'];
	$ot_adjustment_t=$row['ot'];
	
	$tickets_adjustments=0;
	//$cash_adjustments+=$fare_adjustment*1;
	$tickets_adjustments+=$sjt_adjustment_t*1;
	$tickets_adjustments+=$sjd_adjustment_t*1;
	$tickets_adjustments+=$svt_adjustment_t*1;
	$tickets_adjustments+=$svd_adjustment_t*1;
	$tickets_adjustments+=$c_adjustment_t*1;
	$tickets_adjustments+=$ot_adjustment_t*1;	
	
}

$cash_revenue_2=$cash_revenue_1+$cash_adjustments;

?>
<table width=100%>
<tr>
<td valign=top>
<form action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post' >
<table class='controlTable' align=center>
<tr class='header'><td colspan=2><b>Total Amount</b></td><td><input type=text name='total_amount' id='total_amount' value='<?php echo $cash_revenue_1; ?>' /></td>
<tr class='subheader'><td colspan=3 align=center>Fare Adjustment (Add)</td></tr>
<tr class='category'><td>Type</td><td>Qty.</td><td>Amount</td></tr>
<tr>
<td>SJT</td>
<td><input type=text size=10 name='adjustment_tickets_2' id='adjustment_tickets_2' onkeyup='computeTicketRevenue()'   value='<?php echo $sjt_adjustment_t; ?>' /></td>
<td><input type=text name='adjustment_2' id='adjustment_2' onkeyup='computeCashRevenue()' value='<?php echo $sjt_adjustment; ?>'  /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type=text size=10 name='adjustment_tickets_3' id='adjustment_tickets_3' onkeyup='computeTicketRevenue()' value='<?php echo $sjd_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_3' id='adjustment_3' onkeyup='computeCashRevenue()' value='<?php echo $sjd_adjustment; ?>'  /></td>
</tr>
<tr>
<td>SVT</td>
<td><input type=text size=10 name='adjustment_tickets_4' id='adjustment_tickets_4' onkeyup='computeTicketRevenue()' value='<?php echo $svt_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_4' id='adjustment_4' onkeyup='computeCashRevenue()' value='<?php echo $svt_adjustment; ?>'  /></td>
</tr>
<tr>
<td>SVD</td>
<td><input type=text size=10 name='adjustment_tickets_5' id='adjustment_tickets_5' onkeyup='computeTicketRevenue()' value='<?php echo $svd_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_5' id='adjustment_5' onkeyup='computeCashRevenue()' value='<?php echo $svd_adjustment; ?>'  /></td>
</tr>
<tr>
<td>C</td>
<td><input type=text size=10 name='adjustment_tickets_6' id='adjustment_tickets_6' onkeyup='computeTicketRevenue()' value='<?php echo $c_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_6' id='adjustment_6' onkeyup='computeCashRevenue()' value='<?php echo $c_adjustment; ?>'  /></td>
</tr>
<tr>
<td>OT</td>
<td><input type=text size=10 name='adjustment_tickets_7' id='adjustment_tickets_7' onkeyup='computeTicketRevenue()' value='<?php echo $ot_adjustment_t; ?>' /></td>
<td><input type=text name='adjustment_7' id='adjustment_7' onkeyup='computeCashRevenue()' value='<?php echo $ot_adjustment; ?>'  /></td>
</tr>
<tr>
<td><b>Subtotal</b></td>
<td><input type=text size=10 name='tickets_sub_total' id='tickets_sub_total' value='<?php echo $tickets_adjustments; ?>' /></td>
<td><input type=text name='cash_sub_total' id='cash_sub_total'  value='<?php echo $cash_adjustments; ?>' /></td></tr>
</tr>
<tr>
<td colspan=2><b>Total</b></td>

<td><input type=text name='cash_revenue_1' id='cash_revenue_1' value='<?php echo $cash_revenue_2; ?>' /></td>
</tr>
<tr class='none'>
<td colspan=3 align=center><input type=hidden name='adjustment_control_id_a' value='<?php echo $control_id; ?>' /><input type=submit value='Save Adjustments' /></td>
</tr>
</table>
</form>
<br>
<?php
$sql="select * from discrepancy_ticket where transaction_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$discrepancy[$row['type']][$row['ticket_type']]=$row['amount'];
	}
}


?>
<table class='controlTable2' width=80%>
<tr class='header'><th colspan=3>Ticket Discrepancy</th></tr>
<tr class='subheader'><th>Ticket</th><th>Overage</th><th>Shortage</th></tr>
<tr class='grid'><th>SJT</th><td><?php echo $discrepancy['overage']['sjt']; ?></td><td><?php echo $discrepancy['shortage']['sjt']; ?></td></tr>
<tr class='category'><th>SJD</th><td><?php echo $discrepancy['overage']['sjd']; ?></td><td><?php echo $discrepancy['shortage']['sjd']; ?></td></tr>
<tr class='grid'><th>SVT</th><td><?php echo $discrepancy['overage']['svt']; ?></td><td><?php echo $discrepancy['shortage']['svt']; ?></td></tr>
<tr class='category'><th>SVD</th><td><?php echo $discrepancy['overage']['svd']; ?></td><td><?php echo $discrepancy['shortage']['svd']; ?></td></tr>
<tr><th colspan=3><input type=button value='Add Discrepancy' onclick='window.open("discrepancy_ticket.php?tID=<?php echo $control_id; ?>&tsID=<?php echo $ticketSellerName; ?>","discrepancy","height=350, width=500")' /></th></tr>
</table>

<br>
<form action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post' >
<table class='controlTable2' style='border:1px solid red' >
<tr class='header'><th colspan=2>Change User</th></tr>
<tr class='grid'>
	<td>Ticket Seller</td>
	<td>
<?php
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	<div id='cafill' name='cafill'>
	<select name='ticket_seller_change' id='ticket_seller_change'>
	<?php 
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
	<?php
	}
	?>
	
	</select>
	</div>
	</td>
</tr>	
<tr  class='category'>
	<td>Search Ticket Seller</td>
	<td><input type=text name='searchTS' id='searchTS' onkeyup='searchTicketSeller(this.value)' /></td>
</tr>

<tr class='grid'>
	<td >Unit</td>
	<td>
		<select name='unit' id='unit'>
		<option>A/D1</option>
		<option>A/D2</option>
		<option>TIM1</option>
		<option>TIM2</option>
		<option>TIM3</option>
		</select>
	</td>
</tr>	
<tr class='category'>
	<td>Station</td>
	<td>
		<select name='station' id='station'>

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
	</td>
</tr>	
<tr class='grid'>
<td colspan=2 align=center><input type=hidden name='change_control_id' value='<?php echo $control_id; ?>' /><input type=submit value='Change User' /></td>
</tr>
</table>
</form>

</td>
<td valign=top>

<?php
$db=new mysqli("localhost","root","","finance");
//$trackingSQL="select * from control_tracking where control_id='".$control_id."'";

$cash_revenue_3=$cash_revenue_2;

$sql="select sum(total) as total from cash_transfer where control_id='".$control_id."' and type in ('allocation')";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$cash_advance=$row['total'];
	$cash_revenue_3+=$cash_advance;
}

$sql="select * from control_cash where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$overage=$row['overage'];
//	$add_others=$row['add_others'];
//	$refund=$row['refund'];
	$unpaid_storage=$row['unpaid_storage'];
//	$discount=$row['discount'];
//	$less_others=$row['less_storage'];


	
	$cash_revenue_3+=$overage;
//	$cash_revenue_3+=$add_others;
//	$cash_revenue_3-=$refund;
	$cash_revenue_3-=$unpaid_storage;
//	$cash_revenue_3-=$discount;
//	$cash_revenue_3-=$less_others;
//	$ot_amount=$row['ot'];
//	$cash_revenue_3+=$ot_amount;
}

$sql="select * from discount where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_discount=$row['sj'];
	$sv_discount=$row['sv'];

	
	$cash_revenue_3-=$sj_discount;
	$cash_revenue_3-=$sv_discount;

}


$sql="select * from refund where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_refund=$row['sj'];
	$sv_refund=$row['sv'];

	$sj_refund_amount=$row['sj_amount'];
	$sv_refund_amount=$row['sv_amount'];

	$cash_revenue_3-=$sj_refund_amount;
	$cash_revenue_3-=$sv_refund_amount;
	
	
}

$sql="select * from unreg_sale where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_unreg=$row['sj'];
	$sv_unreg=$row['sv'];

	$cash_revenue_3+=$sj_unreg;
	$cash_revenue_3+=$sv_unreg;
}


?>
<form name='remittance_form' id='remittance_form' action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post'>
<table class='controlTable2'>
<tr class='header'><td><b>Total Cash Revenue</b></td><td><input type=text name='cash_revenue_2' id='cash_revenue_2' value='<?php echo $cash_revenue_2; ?>'   /></td></tr>

<tr class='subheader'><td colspan=2 align=center>Adjustments (Add/Less)</td></tr>
<tr class='category'><th colspan=2>Add</th></tr>
<tr class='grid'><th>Cash Advance</th><td><input type=text name='addition_1' id='addition_1' onkeyup='computeRemittance()' value='<?php echo $cash_advance; ?>' />  <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td></tr>
<tr class='grid'><th>Overage</th><td><input type=text name='addition_2' id='addition_2' onkeyup='computeRemittance()' value='<?php echo $overage; ?>'  /></td></tr>
<!--
<tr><th>OT Amount</th><td><input type=text name='addition_3' id='addition_3' onkeyup='computeRemittance()'  /></td></tr>
-->
<tr class='header'><th colspan=2>Others (Unreg. Sale)</th></tr>
<tr class='category'>
<th>SJ</th>
<th>SV</th>

<tr class='grid'>
<td><input type=text name='unreg_sj' id='unreg_sj' onkeyup='computeRemittance()' value='<?php echo $sj_unreg; ?>'   /></td>
<td><input type=text name='unreg_sv' id='unreg_sv' onkeyup='computeRemittance()' value='<?php echo $sv_unreg; ?>'   /></td></tr>
</table>
<table class='controlTable2'>
<tr class='header'><th colspan=2>Less</th></tr>
<tr class='subheader'><th colspan=2>Refund</th></tr>
<tr class='category'>
<th>Tickets - SJ</th><th>Tickets - SV</th>
</tr>
<tr class='grid'>
<td><input type=text name='refund_sj' id='refund_sj' onkeyup='computeRemittance()' value='<?php echo $sj_refund; ?>'  /></td>
<td><input type=text name='refund_sv' id='refund_sv' onkeyup='computeRemittance()' value='<?php echo $sv_refund; ?>'  /></td>
</tr>

<tr class='category'>
<th>SJ Amount</th><th>SV Amount</th>
</tr>

<tr class='grid'>
<td><input type=text name='refund_sj_amount' id='refund_sj_amount' onkeyup='computeRemittance()' value='<?php echo $sj_refund_amount; ?>'  /></td>
<td><input type=text name='refund_sv_amount' id='refund_sv_amount' onkeyup='computeRemittance()' value='<?php echo $sv_refund_amount; ?>'  /></td>
</tr>
<tr>
<td colspan=2></td>
</tr>
<tr class='grid'><th>Unpaid Shortage</th><td><input type=text name='deduction_2' id='deduction_2' onkeyup='computeRemittance()' value='<?php echo $unpaid_shortage; ?>'  /></td></tr>
<tr>
<td colspan=2></td>
</tr>

<tr class='subheader'><th colspan=2>Discount</th>

<tr class='category'><th>SJ Amount</th><th>SV Amount</th></tr>
<tr class='grid'>
<td><input type=text name='discount_sj' id='discount_sj' onkeyup='computeRemittance()' value='<?php echo $sj_discount; ?>'  /></td>
<td><input type=text name='discount_sv' id='discount_sv' onkeyup='computeRemittance()' value='<?php echo $sv_discount; ?>'  /></td></tr>
<tr>
<td colspan=2 align=center><input type=hidden name='adjustments_2_control_id' value='<?php echo $control_id; ?>' />
<!--<input type=submit value='Save Adjustments' />-->&nbsp;
</td>
</tr>
<!-- others taken out -->
<tr class='header'><td><b>Total Remittance</b></td><td><input type=text name='total_remittance' id='total_remittance' value='<?php echo $cash_revenue_3; ?>' /></td></tr>

<tr>
<td colspan=2 align=center><input type=button onclick='remitControlSlip("<?php echo $control_id; ?>")' value='Save Adjustments' /></td>
</tr>


</table>
</form>
</td>
</tr>
<tr>
<td colspan=2 align=center><input type=button value='Generate Printout' onclick='window.open("generate_control_slip.php")' /></td>
</tr>
</table>