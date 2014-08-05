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
$db=new mysqli("localhost","root","","finance");
?>
<?php
	if(isset($_POST['begin_log_id'])){
		$beginning_type=$_POST['beginning_type'];
		$db=new mysqli("localhost","root","","finance");
		if($beginning_type=="cash"){
			$search="select * from beginning_balance_cash where log_id='".$_POST['begin_log_id']."'";
			$searchRS=$db->query($search);
			$searchNM=$searchRS->num_rows;
			
			if($searchNM>0){
				$update="update beginning_balance_cash set revolving_fund='".$_POST['revolving']."',for_deposit='".$_POST['deposit']."' where log_id='".$_POST['begin_log_id']."'";
				$rs=$db->query($update);				
			}
			else {
				$update="insert into beginning_balance_cash(revolving_fund,for_deposit,log_id) values ('".$_POST['revolving']."','".$_POST['deposit']."','".$_POST['begin_log_id']."')";
				$rs=$db->query($update);				
			}
		}	
	}

if(isset($_POST['cs_ticket_seller'])){

	if((isset($_POST['cash_total']))&&($_POST['cash_total']>0)){

		$receive_day=date("Y-m-d",strtotime($_POST['receive_date']));
		
		$receive_time=date("Y-m-d",strtotime($receive_day." ".$_POST['receive_time']));
		
		$date=$receive_time;
		$date_id=date("Ymd",strtotime($_POST['receive_date']));

		
		$type=$_POST['type'];
		
		$total=$_POST['cash_total'];
		$totalWords=$_POST['total_in_pesos'];
		$net=$_POST['for_deposit'];
		$station_entry=$_POST['station'];

		$control_id=$_POST['cs_ticket_seller'];
			
		$control_sql="select * from control_slip where id='".$control_id."' limit 1";
		$control_rs=$db->query($control_sql);
			
		$control_row=$control_rs->fetch_assoc();
			
		$ticket_seller=$control_row['ticket_seller'];
			
		$unit=$control_row['unit'];

		
	//	$unit=$_POST['unit'];

		$destination_ca="";
		if($type=="catransfer"){
			$destination_ca=$_POST['destination_ca'];
			
		}
		
		$db=new mysqli("localhost","root","","finance");
		$reference_id=$_POST['reference_id'];
		
		
		if($_POST['form_action']=="new"){
			
			$sql="insert into transaction(date,log_id,log_type,transaction_type,reference_id) values ('".$date."','".$log_id."','cash','".$type."','".$reference_id."')";
			$rs=$db->query($sql);

			$insert_id=$db->insert_id;
			
			$transaction_id=$date_id."_".$insert_id;
			$_SESSION['transact']=$transaction_id;
			$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
			$rs=$db->query($sql);
			
			$revolving=$_POST['revolving_remittance'];
			

			
			
			
			$sql="insert into cash_transfer(log_id,time,ticket_seller,cash_assistant,type,";
			$sql.="transaction_id,total_in_words,total,net_revenue,station,reference_id,unit,destination_ca,control_id) values ";
			$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$_POST['cash_assistant']."','".$type."',";
			$sql.="'".$transaction_id."','".$totalWords."','".$revolving."','".$net."','".$station_entry."','".$reference_id."','".$unit."','".$destination_ca."','".$control_id."')";
			
			$indicator=$sql;
			$rs=$db->query($sql);
			
			
			$insert_id=$db->insert_id;
			$cash_transfer=$insert_id;
			if($type=="catransfer"){
				$sql="update cash_transfer set destination_ca='".$_POST['destination_cash_assistant']."' where id='".$cash_transfer."'";
				$rs=$db->query($sql);
			}
			
			if($type=="remittance"){
				$controlSQL="select * from control_slip where ticket_seller='".$ticket_seller."'  order by id desc";
				//and status='close'
				$controlRS=$db->query($controlSQL);
				$controlRow=$controlRS->fetch_assoc();
				
				$control_log=$controlRow['log_id'];
				
					
				$sql="select * from cash_remittance where log_id='".$control_log."' and ticket_seller='".$ticket_seller."'";	
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where id='".$row['id']."'";
					$rs2=$db->query($update);
				}
				else {
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where ticket_seller='".$ticket_seller."' and cash_remittance=''";
					$rs2=$db->query($update);
					
				}
			}
			else if($type=="shortage"){
				$update="update control_cash set unpaid_shortage=unpaid_shortage-".(($total+$net_revenue)*1)." where control_id='".$control_id."'";
				$rs2=$db->query($update);
			}

			$denom[0]["id"]="1000";
			$denom[1]["id"]="500";
			$denom[2]["id"]="200";
			$denom[3]["id"]="100";
			$denom[4]["id"]="50";
			$denom[5]["id"]="20";
			$denom[6]["id"]="10";
			$denom[7]["id"]="5";
			$denom[8]["id"]="1";
			$denom[9]["id"]=".25";
			$denom[10]["id"]=".10";
			$denom[11]["id"]=".05";
			

			$denom[0]["value"]=$_POST['1000denom'];
			$denom[1]["value"]=$_POST['500denom'];
			$denom[2]["value"]=$_POST['200denom'];
			$denom[3]["value"]=$_POST['100denom'];
			$denom[4]["value"]=$_POST['50denom'];
			$denom[5]["value"]=$_POST['20denom'];
			$denom[6]["value"]=$_POST['10denom'];
			$denom[7]["value"]=$_POST['5denom'];
			$denom[8]["value"]=$_POST['1denom'];
			$denom[9]["value"]=$_POST['25cdenom'];
			$denom[10]["value"]=$_POST['10cdenom'];
			$denom[11]["value"]=$_POST['5cdenom'];

			
			for($i=0;$i<count($denom);$i++){
				if($denom[$i]["value"]>0){
					$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
					$sqlInsert.=" values ('".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
					$sqlInsertRS=$db->query($sqlInsert);
				}
			}		
		}
		
		
		else if($_POST['form_action']=="edit"){
			
			$control_id=$_POST['cs_ticket_seller'];
			$sql="select * from transaction where id='".$_POST['ctf_transaction_id']."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			$type=$_POST['type'];
			
			$update="update transaction set transaction_type='".$type."',reference_id='".$reference_id."' where id='".$row['id']."'";
			
			$updateRS=$db->query($update);
		
			$insert_id=$row['id'];
			
			$transaction_id=$row['transaction_id'];
			$_SESSION['transact']=$transaction_id;
			
/*
			$csql="select * from control_slip where id='".$control_id."'";
			
			$crs=$db->query($csql);
			$crow=$rs->fetch_assoc();
			
			$ticket_seller=$crow['ticket_seller'];
			$unit=$crow['unit'];
	*/					
			
			
			//$ticket_seller=$_POST['ticket_seller'];	
			$revolving=$_POST['revolving_remittance'];
			$reference_id=$_POST['reference_id'];	
			
			
			$sql="update cash_transfer set ticket_seller='".$ticket_seller."',total='".$revolving."',net_revenue='".$net."',total_in_words='".$totalWords."',station='".$station_entry."',type='".$type."',unit='".$unit."', destination_ca='".$destination_ca."',reference_id='".$reference_id."',control_id='".$control_id."' where transaction_id='".$transaction_id."'";
			$rs=$db->query($sql);
	
			if($type=="catransfer"){
				$sql="update cash_transfer set destination_ca='".$_POST['destination_cash_assistant']."',cash_assistant='".$_POST['cash_assistant']."' where transaction_id='".$transaction_id."'";
//				echo $sql;
				$rs=$db->query($sql);
			}
			
			$sql="select * from cash_transfer where transaction_id='".$transaction_id."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			
			$insert_id=$row['id'];
//			$insert_id=$insert_id;
			$cash_transfer=$insert_id;
			
			if($type=="remittance"){
				$sql="select * from cash_remittance where ticket_seller='".$ticket_seller."' and log_id='".$log_id."'";	
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where id='".$row['id']."'";
					$rs2=$db->query($update);
				}
				else {
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where ticket_seller='".$ticket_seller."' and cash_remittance=''";
					$rs2=$db->query($update);
					
				}
			}
			
		
		
			$denom[0]["id"]="1000";
			$denom[1]["id"]="500";
			$denom[2]["id"]="200";
			$denom[3]["id"]="100";
			$denom[4]["id"]="50";
			$denom[5]["id"]="20";
			$denom[6]["id"]="10";
			$denom[7]["id"]="5";
			$denom[8]["id"]="1";
			$denom[9]["id"]=".25";
			$denom[10]["id"]=".10";
			$denom[11]["id"]=".05";
			

			$denom[0]["value"]=$_POST['1000denom'];
			$denom[1]["value"]=$_POST['500denom'];
			$denom[2]["value"]=$_POST['200denom'];
			$denom[3]["value"]=$_POST['100denom'];
			$denom[4]["value"]=$_POST['50denom'];
			$denom[5]["value"]=$_POST['20denom'];
			$denom[6]["value"]=$_POST['10denom'];
			$denom[7]["value"]=$_POST['5denom'];
			$denom[8]["value"]=$_POST['1denom'];
			$denom[9]["value"]=$_POST['25cdenom'];
			$denom[10]["value"]=$_POST['10cdenom'];
			$denom[11]["value"]=$_POST['5cdenom'];

				
			
			$sqlDenom="delete from denomination where cash_transfer_id='".$insert_id."'";
			$rsDenom=$db->query($sqlDenom);
			for($i=0;$i<count($denom);$i++){
				if($denom[$i]["value"]>0){
					//$sqlInsert="update denomination set quantity='".$denom[$i]['value']."' where demonination='".$denom[$i]['id']."' and cash_transfer_id='".$insert_id."'";
					$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
					$sqlInsert.=" values ('".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
					$sqlInsertRS=$db->query($sqlInsert);
					
					
				}
			}
		}
		
		$transaction_code=$transaction_id;
		$cash_code=$cash_transfer;


		if(isset($_GET['type'])){
			$type=$_GET['type'];
			$classification="cash";
			$transaction_id=$transaction_code;
			$reported="ticket seller";
			$amount=$_GET['amount'];
			$reference_id=$transaction_id;
			$t_seller=$ticket_seller;

			$update="insert into discrepancy(reference_id,classification,reported,amount,type,transaction_id,log_id,ticket_seller) values ('".$reference_id."','".$classification."','".$reported."','".$amount."','".$type."','".$transaction_id."','".$log_id."','".$t_seller."')";
			$updateRS=$db->query($update);

			
			if($type=="overage"){
				$update="update control_cash set overage='".$amount."' where control_id='".$control_id."'";
				$updateRS=$db->query($update);
			
			}
			
			
			
		}
		
		if($_GET['shortage_payment']=="Y"){
			$depositpost=$total;
			$control_post=$control_id;
			$station_id=$station_entry;
			$cash_assist=$_POST['cash_assistant'];
		}
		
	}
	header("Location:test_cash_logbook.php");
	
}
if(isset($_POST['pnb_ca'])){
	

	$receive_day=date("Y-m-d",strtotime($_POST['receive_date_2']));
		
	$receive_time=date("Y-m-d",strtotime($receive_day." ".$_POST['receive_time_2']));
		
	$date=$receive_time;
	$date_id=date("Ymd",strtotime($_POST['receive_date_2']));


	$type=$_POST['deposit_type'];

	$reference_id=$_POST['reference_id_2'];
	$type=$_POST['deposit_type'];

	$db=new mysqli("localhost","root","","finance");
	
	$total=$_POST['cash_total'];

	if($_POST['form_action']=="new"){
		$sql="insert into transaction(date,log_id,log_type,transaction_type,reference_id) values ('".$date."','".$log_id."','cash','deposit','".$reference_id."')";
		$rs=$db->query($sql);

		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;
		$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs=$db->query($sql);


		$sql="insert into pnb_deposit(log_id,time,cash_assistant,type,";
		$sql.="transaction_id,amount,reference_id) values ";
		$sql.="('".$log_id."','".$date."','".$_POST['pnb_ca']."','".$type."',";
		$sql.="'".$transaction_id."','".$total."','".$reference_id."')";
		$indicator=$sql;
		$rs=$db->query($sql);
		$insert_id=$db->insert_id;

	
		$denom[0]["id"]="1000";
		$denom[1]["id"]="500";
		$denom[2]["id"]="200";
		$denom[3]["id"]="100";
		$denom[4]["id"]="50";
		$denom[5]["id"]="20";
		$denom[6]["id"]="10";
		$denom[7]["id"]="5";
		$denom[8]["id"]="1";
		$denom[9]["id"]=".25";
		$denom[10]["id"]=".10";
		$denom[11]["id"]=".05";
		
		$denom[0]["value"]=$_POST['1000denom_2'];
		$denom[1]["value"]=$_POST['500denom_2'];
		$denom[2]["value"]=$_POST['200denom_2'];
		$denom[3]["value"]=$_POST['100denom_2'];
		$denom[4]["value"]=$_POST['50denom_2'];
		$denom[5]["value"]=$_POST['20denom_2'];
		$denom[6]["value"]=$_POST['10denom_2'];
		$denom[7]["value"]=$_POST['5denom_2'];
		$denom[8]["value"]=$_POST['1denom_2'];
		$denom[9]["value"]=$_POST['25cdenom_2'];
		$denom[10]["value"]=$_POST['10cdenom_2'];
		$denom[11]["value"]=$_POST['5cdenom_2'];
		
		for($i=0;$i<count($denom);$i++){
			if($denom[$i]["value"]>0){
				$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
				$sqlInsert.=" values ('pnb_".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
				$sqlInsertRS=$db->query($sqlInsert);
			}
		}
	
	}	
	else if($_POST['form_action']=="edit"){

		$sql="select * from transaction where id='".$_POST['pnb_transaction_id']."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();

			
		$update="update transaction set reference_id='".$reference_id."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
		
		$insert_id=$row['id'];
			
		$transaction_id=$row['transaction_id'];


		$sql="update pnb_deposit set cash_assistant='".$_POST['pnb_ca']."',time='".$date."',amount='".$total."',log_id='".$log_id."',type='".$type."',reference_id='".$reference_id."' where transaction_id='".$transaction_id."'";
		$rs=$db->query($sql);
			
		$denom[0]["id"]="1000";
		$denom[1]["id"]="500";
		$denom[2]["id"]="200";
		$denom[3]["id"]="100";
		$denom[4]["id"]="50";
		$denom[5]["id"]="20";
		$denom[6]["id"]="10";
		$denom[7]["id"]="5";
		$denom[8]["id"]="1";
		$denom[9]["id"]=".25";
		$denom[10]["id"]=".10";
		$denom[11]["id"]=".05";
			

		$denom[0]["value"]=$_POST['1000denom_2'];
		$denom[1]["value"]=$_POST['500denom_2'];
		$denom[2]["value"]=$_POST['200denom_2'];
		$denom[3]["value"]=$_POST['100denom_2'];
		$denom[4]["value"]=$_POST['50denom_2'];
		$denom[5]["value"]=$_POST['20denom_2'];
		$denom[6]["value"]=$_POST['10denom_2'];
		$denom[7]["value"]=$_POST['5denom_2'];
		$denom[8]["value"]=$_POST['1denom_2'];
		$denom[9]["value"]=$_POST['25cdenom_2'];
		$denom[10]["value"]=$_POST['10cdenom_2'];
		$denom[11]["value"]=$_POST['5cdenom_2'];

		$sqlDenom="delete from denomination where cash_transfer_id='pnb_".$insert_id."'";
		$rsDenom=$db->query($sqlDenom);
		for($i=0;$i<count($denom);$i++){
			if($denom[$i]["value"]>0){
				//$sqlInsert="update denomination set quantity='".$denom[$i]['value']."' where demonination='".$denom[$i]['id']."' and cash_transfer_id='".$insert_id."'";
				$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
				$sqlInsert.=" values ('pnb_".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
				$sqlInsertRS=$db->query($sqlInsert);
			}
		}			
				
	}	
	header("Location: test_cash_logbook.php");

	
}


?>



<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->
<script type="text/javascript" src="js/jquery-min.js"></script>

<script type="text/javascript" src="js/plugins/forms/ui.spinner.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.mousewheel.js"></script>
 
<script type="text/javascript" src="js/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.pie.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/plugins/tables/jquery.sortable.js"></script>
<script type="text/javascript" src="js/plugins/tables/jquery.resizable.js"></script>

<script type="text/javascript" src="js/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.uniform.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.autotab.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.chosen.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.ibutton.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.validationEngine.js"></script>

<script type="text/javascript" src="js/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="js/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="js/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="js/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="js/plugins/wizards/jquery.form.wizard.js"></script>
<script type="text/javascript" src="js/plugins/wizards/jquery.validate.js"></script>
<script type="text/javascript" src="js/plugins/wizards/jquery.form.js"></script>

<script type="text/javascript" src="js/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.fancybox.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.fileTree.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.sourcerer.js"></script>

<script type="text/javascript" src="js/plugins/others/jquery.fullcalendar.js"></script>
<script type="text/javascript" src="js/plugins/others/jquery.elfinder.js"></script>

<script type="text/javascript" src="js/plugins/ui/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="js/files/bootstrap.js"></script>
<script type="text/javascript" src="js/files/functions.js"></script>
<script type="text/javascript" src="js/files/additional_function.js"></script>                            
<script language='javascript'>
function deleteRecord(transaction,type){
	var check=confirm("Delete the Transaction?");
	
	if(check){
		window.open("delete_transaction.php?tID="+transaction+"&type="+type,"_blank");
	}
}


function editTransact(transact_id,transact_type){
 	$('#'+transact_id+"_spinner").show();
	$.getJSON("processing.php?transaction_id="+transact_id+"&type="+transact_type, function(data) {


		if(data.type=='ctf'){
			for(i=0;i<data.currency['denom_count'];i++){
				$('#ctf_denom #'+data.currency[i]['label']).val(data.currency[i]['value']);
				$('#ctf_denom .'+data.currency[i]['label']).val(data.currency[i]['value']*data.currency[i]['id']);
			
			}
			calculateTotal();
			
			$('.form_action').val('edit');
			$('#'+data.tID+"_spinner").hide();
			$('#ctf_transaction_id').val(data.tID);

			$('#cash_assist').val(data.cash_assistant);	
			$('#cs_ticket_seller').val(data.control_id);	
			$('#station').val(data.station);	
			$('#reference_id').val(data.reference_id);
			$('#receive_date').val(data.receive_date);
			$('#receive_time').val(data.receive_time);
			$('#type').val(data.transactType);
			$('#desination_ca').val(data.destination_ca);
			$('#control_id').val(data.control_id);
			
			//getCashAdvance($('#control_id').val());	
			$('#cash_transfer_modal').show();
			$('#cash_transfer_modal').dialog('open');
			
		}	
		else if(data.type=='pnb'){
			for(i=0;i<data.currency['denom_count'];i++){
				$('#pnb_denom #'+data.currency[i]['label']+'_2').val(data.currency[i]['value']);
				$('#pnb_denom .'+data.currency[i]['label']).val(data.currency[i]['value']*data.currency[i]['id']);
			
			}			
			calculateTotal2();
			
			$('.form_action2').val('edit');
			$('#'+data.tID+"_spinner").hide();
			
			$('#pnb_transaction_id').val(data.tID);
			$('#pnb_ca').val(data.cash_assistant);	
			$('#deposit_type').val(data.depositType);
			
			$('#reference_id_2').val(data.reference_id);
			$('#receive_date_2').val(data.receive_date);
			$('#receive_time_2').val(data.receive_time);
			
			$('#pnb_modal').show();
			$('#pnb_modal').dialog('open');
		}
    });
 }
 function getCashAdvance(control_id){
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
			
			if((document.getElementById('cash_total').value)>=(document.getElementById('revolving_remittance').value)){
				document.getElementById('revolving_remittance').value=caHTML;
				document.getElementById('for_deposit').value=Math.round((document.getElementById('cash_total').value*1-$('#revolving_remittance').val())*100)/100;
			}
			else {
				document.getElementById('revolving_remittance').value=document.getElementById('cash_total').value;
				document.getElementById('for_deposit').value=0;
			}
			
		}
	} 
	
	xmlHttp.open("GET","processing.php?getCashAdvance="+control_id,true);
	xmlHttp.send();	
}	
function checkRemittance(transaction){

	if(transaction.value=="partial_remittance"){
	
		var control_id=document.getElementById('cs_ticket_seller').value;
		getCashAdvance(control_id);
	}
}

 
</script>








<?php require("title_header.php"); ?>

<div class='content' >
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-screen"></span>Logbooks</span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='cash_logbook.php'>Logbooks (Original Template)</a></span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='test_dsr_cash.php'>Detailed Sales Report</a></span>

		
        <ul class="quickStats">
            <li>
                <a href="test_cash_logbook.php" class="blueImg"><img src="images/icons/quickstats/money.png" alt="" /></a>
                <div class="floatR"><strong class="blue">Cash Logbook</strong></div>
            </li>
            <li>
                <a href="test_sjt_logbook.php" class="redImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">SJT Logbook</strong></div>
            </li>
            <li>
                <a href="test_svt_logbook.php" class="greenImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">SVT Logbook</strong></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
	<?php 
	require("test_reference_line.php");
	?>
	
	
    <div class="wrapper">
	        <div class="widget">
            <div class="whead"><h6>Cash Logbook	<?php echo $indicator; ?></h6>
                <div class="titleOpt">
					
					<a href="#" title='Add Transaction' data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                            <li><a href="#" name='open_ctf' id='open_ctf'><span class="icos-add"></span>Cash Transfer Form</a></li>
                            <li><a href="#" name='open_pnb' id='open_pnb'><span class="icos-add"></span>PNB Deposit</a></li>
					</ul>
					
                </div>
                <div class="titleOpt">
					<a href="generateCashLogbook.php" target='_blank' title='Print' ><span class="icos-printer"></span><span class="clear"></span></a>

                </div>
				
			<div class="clear"></div>
			
			</div>


            
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault table-hover">
                <thead>
                    <tr>
                        <th colspan=3 style='text-align:center;'>Particulars</th>
                        <th colspan=4 style='text-align:center;'>Cash In/Out</th>
                        <th rowspan=2  style='text-align:center;' valign=bottom>Shortage/Overage</th>
                        <th colspan=3 style='text-align:center;'>Balance</th>
                        <th rowspan=2  style='text-align:center;' valign=bottom>Remarks</th>
                    </tr>
                    <tr>
						<td>Time</td>
						<td>Name</td>
						<td>ID No.</td>
					
                        <td>Revolving Fund</td>
                        <td>For Deposit/Net Revenue</td>
                        <td>PNB Deposit</td>
                        <td>Total</td>
                        
						<td>Revolving Fund</td>
                        <td>For Deposit/Net Revenue</td>
                        <td>Total</td>
						
                    </tr>



				</thead>
                <tbody>
				<?php
				$count=0;
				$station=$_SESSION['station'];

				$db=new mysqli("localhost","root","","finance");

				$sql="select * from beginning_balance_cash where log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
				$row=$rs->fetch_assoc();
					$revolvingTotal=$row['revolving_fund'];
					$depositTotal=$row['for_deposit'];
					$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);
				}
				else {

				$alternate="SELECT * FROM beginning_balance_cash inner join logbook on beginning_balance_cash.log_id=logbook.id and station='".$station."' order by date desc,field(revenue,'close','open'),field(shift,2,1,3)";

				$rs2=$db->query($alternate);
				$row=$rs2->fetch_assoc();
					$revolvingTotal=$row['revolving_fund'];
					$depositTotal=$row['for_deposit'];
					$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);
					
					$insert="insert into beginning_balance_cash(log_id,revolving_fund,for_deposit) values ('".$log_id."','".$revolvingTotal."','".$depositTotal."')";
					$insertRS=$db->query($insert);	

				}	
				?>
				<tr>
					<td colspan=3>Beginning Balance <a href='#' style='text-decoration:none' name='open_entry' id='open_entry' ><i class='icos-pencil pull-right'></i></a></td>

					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>

					<td align=right><?php echo number_format($row['revolving_fund']*1,2); ?></td>
					<td align=right><?php echo number_format($row['for_deposit']*1,2); ?></td>
					<td align=right><?php echo number_format(($row['for_deposit']*1)+($row['revolving_fund']*1),2); ?></td>
					<td>&nbsp;</td>

				</tr>

				<?php

				$db=new mysqli("localhost","root","","finance");
				$sql="select * from transaction where log_id='".$log_id."' and log_type in ('cash') and transaction_type not in ('catransfer') order by id*1";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				for($i=0;$i<$nm;$i++){
					$cash_asst="";
					$row=$rs->fetch_assoc();

					$date=date("h:i a",strtotime($row['date']));
					$edit_id=$row['id'];
					$transaction_id=$row['transaction_id'];
					
					$type=$row['transaction_type'];
					$log_type=$row['log_type'];
					
					if($row['reference_id']==""){
					$remarks="&nbsp;";
					}
					else {
					$remarks=$row['reference_id'];
					}
					if($type=="shortage"){
						$type="remittance";
						$log_type="shortage";


					}
				/*
					else {
						echo $transaction_id."remit";
					
					}
					*/
					$suffix="";
					if($type=="deposit"){
						$cashSQL="select * from pnb_deposit where transaction_id='".$transaction_id."'";

						$cashRS=$db->query($cashSQL);
						
						$cashRow=$cashRS->fetch_assoc();	
						$deposit_type=$cashRow['type'];
						
					}
					else {
					
						if(($type=="remittance")||($type=="partial_remittance")){

							if(($log_type=="cash")||($log_type=="shortage")){
								$cashSQL="select * from cash_transfer where transaction_id='".$transaction_id."'";
								$cashRS=$db->query($cashSQL);
								
								$cashRow=$cashRS->fetch_assoc();
								
								if($cashRow['station']==$logST){
								}
								else {
									if($cashRow['station']=="annex"){
									}
									else {
									$extensionSQL="select * from station where id='".$cashRow['station']."'";
									$extensionRS=$db->query($extensionSQL);
									$extensionRow=$extensionRS->fetch_assoc();
									
									$suffix=" - ".$extensionRow['station_name'];
									}
								}
								$cashStation=$cashRow['station'];
								
								$cash_assistantSQL="select * from login where username='".$cashRow['cash_assistant']."'";
								$cash_assistantRS=$db->query($cash_assistantSQL);
								$cash_assistantRow=$cash_assistantRS->fetch_assoc();
								
								$cash_asst=$cash_assistantRow['lastName'].", ".$cash_assistantRow['firstName'];
								
								
								$ticketSellerSQL="select * from ticket_seller where id='".$cashRow['ticket_seller']."'";		

								$ticketRS=$db->query($ticketSellerSQL);
								$ticketRow=$ticketRS->fetch_assoc();
								
								$revolving=$cashRow['total'];
								$deposit=$cashRow['net_revenue'];
								$total=$revolving*1+$deposit*1;
							}

							/*
							else if($log_type=="control"){
								$control="select * from cash_remittance where control_transaction_id='".$transaction_id."'";
								$controlRS=$db->query($control);
								$controlRow=$controlRS->fetch_assoc();
								
								$ticketSellerSQL="select * from ticket_seller where id='".$controlRow['ticket_seller']."'";		

								$ticketRS=$db->query($ticketSellerSQL);
								$ticketRow=$ticketRS->fetch_assoc();
								
								$revolving=0;
								$deposit=$controlRow['control_remittance'];
								$total=$revolving*1+$deposit*1;
								
							
							}
							*/
						}
						else if($type=="allocation"){
						
							$cashSQL="select * from cash_transfer where transaction_id='".$transaction_id."'";

							$cashRS=$db->query($cashSQL);
							
							$cashRow=$cashRS->fetch_assoc();
							
								if($cashRow['station']==$logST){
								}
								else {
									if($cashRow['station']=="annex"){
									}
									else {
									$extensionSQL="select * from station where id='".$cashRow['station']."'";
									$extensionRS=$db->query($extensionSQL);
									$extensionRow=$extensionRS->fetch_assoc();
									
									$suffix=" - ".$extensionRow['station_name'];
									}
								}
							
							$cashStation=$cashRow['station'];	
							
							$ticketSellerSQL="select * from ticket_seller where id='".$cashRow['ticket_seller']."'";		

							$ticketRS=$db->query($ticketSellerSQL);
							$ticketRow=$ticketRS->fetch_assoc();
							
							$revolving=$cashRow['total'];
							$deposit=$cashRow['net_revenue'];
							$total=$revolving*1+$deposit*1;
						
						}
						
					}
				
				?>	
					<?php 
					$style="";

					
					
					$sql3="select * from cash_remittance where ticket_seller='".$ticketRow['id']."' order by id desc";
					
					$rs3=$db->query($sql3);
					$nm3=$rs3->num_rows;
					if($nm3>0){
						$row3=$rs3->fetch_assoc();
						if($row3['cash_remittance']==""){
							if($type=="deposit"){
							}
							else {
							//	$style="style='background-color:yellow;'";
							}
						}
					}
					
					?>				
				<tr <?php echo $style; ?>>
					
					<td><?php echo $date; ?></td>
					<td>
					<?php 
					if($type=="deposit")
					{
					echo "<a href='#' style='text-decoration:none'  onclick=\"editTransact('".$edit_id."','pnb')\">PNB Deposit - ".strtoupper($deposit_type)."</a>";  



					} 
					else if(($type=="remittance")||($type=="partial_remittance")){ 
						if($log_type=="cash"){
							if($cashStation=="annex"){
								if(($_SESSION['viewMode']=="view")||($_SESSION['viewMode']=="login")){
									echo "<a href='#' style='text-decoration:none'  onclick=\"editTransact('".$edit_id."','ctf')\">ANNEX</a>";  

								}
								else {
									echo "ANNEX";
								}
							
							}
							else {
								if(($_SESSION['viewMode']=="view")||($_SESSION['viewMode']=="login")){
									if($type=="remittance"){
										echo "".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."";
									}
									else {
									echo "<a href='#' style='text-decoration:none'  onclick=\"editTransact('".$edit_id."','ctf')\">".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."</a>";  
									}
								}
								else {
									echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix;	
								}
							}
						}
						else if($log_type=="shortage"){
							if(($_SESSION['viewMode']=="view")||($_SESSION['viewMode']=="login")){
								echo "<a href='#' style='text-decoration:none'  onclick=\"editTransact('".$edit_id."','ctf')\">".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix." - Payment for Shortage</a>";  

							}
							else {
								echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix." - Payment for Shortage";
							}
						}
					} 
					else if($type=="allocation"){ 
						if($cashStation=="annex"){
							if(($_SESSION['viewMode']=="view")||($_SESSION['viewMode']=="login")){
									echo "<a href='#' style='text-decoration:none'  onclick=\"editTransact('".$edit_id."','ctf')\">ANNEX</a>";  
							}
							else {
								echo "ANNEX";
							}
						}
						else {
							if(($_SESSION['viewMode']=="view")||($_SESSION['viewMode']=="login")){
								echo "<a href='#' style='text-decoration:none'  onclick=\"editTransact('".$edit_id."','ctf')\">".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."</a>";  
							}
							else {
								echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix;
							}
						}
					}	
					?> 
					<img name='<?php echo $edit_id; ?>_spinner' id='<?php echo $edit_id; ?>_spinner' src="images/elements/loaders/1s.gif" style="display:none;" alt="" />

					</td>
					<td align=center>
					<?php 
						if($type=="deposit"){
							echo "&nbsp;";
						}
						else if(($type=="remittance")||($type=="partial_remittance")){ 
							if($log_type=="cash"){
								if($cashStation=="annex"){
									echo "&nbsp;";
								}
								else {
								echo $ticketRow['id'];
								}
							}
							else {
								echo "&nbsp;";
							}
							
						}
						else { 
							if($cashStation=="annex"){
								echo "&nbsp;";
							}
							else {
							echo $ticketRow['id'];
							}
						}	
						?>
					</td>	
					<?php 
					if(($type=="remittance")||($type=="partial_remittance")){ 

					?>
						<td style='color:green;' align=right>+<?php echo number_format($revolving*1,2); ?></td>
						<td style='color:green;' align=right>+<?php echo number_format($deposit*1,2); ?></td>
						<td>-</td>
						<td style='color:green;' align=right>+<?php echo number_format($total*1,2); ?></td>
					
					
					<?php
					
						$overageSQL="select * from discrepancy where transaction_id='".$transaction_id."'";
						$overageRS=$db->query($overageSQL);
						$overageNM=$overageRS->num_rows;
						if($overageNM>0){
							$overageRow=$overageRS->fetch_assoc();
							if($overageRow['type']=="shortage"){
								$overageLabel=number_format($overageRow['amount'],2);
							
							}
							else if($overageRow['type']=="overage"){
								$overageLabel="(".number_format($overageRow['amount'],2).")";
							
							}
						}
						else {
							$overageLabel=0;
						}
					?>
						<td align=right><?php echo $overageLabel; ?></td>

					<?php
					}
					else if($type=="allocation"){
					?>
						<td  style='color:red;' align=right>-<?php echo number_format($revolving*1,2); ?></td>
						<td>-</td>
						<td>-</td>
						<td  style='color:red;' align=right>-<?php echo number_format($revolving*1,2); ?></td>
						<td>-</td>	
					


						
					<?php
					}
					else if($type=="deposit"){
					?>	
						<td>-</td>
						<td>-</td>
						<td  style='color:red;' align=right>-<?php echo number_format($cashRow['amount']*1,2); ?></td>
						<td  style='color:red;' align=right>-<?php echo number_format($cashRow['amount']*1,2); ?></td>
						<td>-</td>	
					
					
					
					<?php	
					}
				?>
				<?php 
				
				if($type=="allocation"){
					$revolvingTotal=$revolvingTotal-$revolving;
					$revolving_style="style='color:red;'";
					$deposit_style="";
					$total_style="style='color:red;'";
				}
				else if(($type=="remittance")||($type=="partial_remittance")){ 
					$revolvingTotal=$revolvingTotal+$revolving;
					
					$depositTotal=$depositTotal+$deposit;
					
					$revolving_style="style='color:green;'";
					$deposit_style="style='color:green;'";					
					$total_style="style='color:green;'";

				}
				
				if($type=="deposit"){
					$depositTotal=$depositTotal-($cashRow['amount']*1);

					$revolving_style="";
					$deposit_style="style='color:red;'";
					$total_style="style='color:red;'";

				}
				$displayTotal=($revolvingTotal*1)+($depositTotal*1);
				/*
				if($overageSwitch=="overage"){
					$displayTotal-=$overage;
				}
				else if($overageSwitch=="shortage"){
					$displayTotal+=$overage;
				}
				*/
				
				?>
				<td <?php echo $revolving_style; ?> align=right><?php echo number_format($revolvingTotal*1,2); ?></td>
				<td <?php echo $deposit_style; ?>  align=right><?php echo number_format($depositTotal*1,2); ?></td>
				<td <?php echo $total_style; ?>  align=right><?php echo number_format($displayTotal*1,2); ?></td>
				<td align=right><?php echo $remarks; ?> <a href='#' class='delete'  onclick='deleteRecord("<?php echo $transaction_id; ?>","cash")' >X</a></td>

				</tr>				
				<?php
				
				
				
				}	
				?>	
				

				<?php
				$sql="select * from transaction where transaction_type='catransfer' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$cTransferSQL="select * from cash_transfer where transaction_id='".$row['transaction_id']."'";
					$cTransferRS=$db->query($cTransferSQL);
					$cTR=$cTransferRS->fetch_assoc();
					$transaction_id=$row['transaction_id'];	
					$revolvingTransfer=$cTR['total'];
					$depositTransfer=$cTR['net_revenue'];
					$totalTransfer=$revolvingTransfer+$depositTransfer;
					
					$revolvingTotal-=$revolvingTransfer;
					$depositTotal-=$depositTransfer;
					$displayTotal-=$totalTransfer;
					$remarks=$cTR['reference_id'];
					$edit_id=$row['id'];
					
				?>			
				<tr>
					<td>&nbsp;</td>
					<td><?php echo $_POST['type']; ?>
					<?php
					if($_SESSION['viewMode']=="login"){
					?>
					<a href='#' style='text-decoration:none' onclick='window.open("cash_transfer.php?tID=<?php echo $edit_id; ?>","transfer","height=800, width=500, scrollbars=yes")'>
					<?php
					}
					?>
					Turnover to CA
					<?php
					if($_SESSION['viewMode']=="login"){
					?>
					</a>
					<?php
					}
					?>	
					</td>
					<td>&nbsp;</td>
					

					
					<td align=right><?php echo number_format($revolvingTransfer,2); ?></td>
					<td align=right><?php echo number_format($depositTransfer,2); ?></td>
					<td>&nbsp;</td>

					<td align=right><?php echo number_format($totalTransfer,2); ?></td>
					<td>-</td>

					<td align=center>
					<?php 
					if($revolvingTotal==0){ echo "---"; } else { 
						if($revolvingTotal<0){
							echo "(".number_format(($revolvingTotal*-1),2).")";
						
						}
						else {
							echo number_format($revolvingTotal,2); 
						}
					} 
					?></td>
					<td align=center>
					<?php 
					if($depositTotal==0){ echo "---"; } else { 
						if($depositTotal<0){
							echo "(".number_format(($depositTotal*-1),2).")";
						
						}
						else {
							echo number_format($depositTotal,2); 
						}
					} 
					?></td>
					<td align=center><?php if($displayTotal==0){ echo "---"; } 
					else { 
						if($displayTotal<0){
							echo "(".number_format(($displayTotal*-1),2).")";
						
						}
						else {
							echo number_format($displayTotal,2); 
						}
					} 
					?></td>

					<td align=right><?php echo $remarks; ?> <a class='delete' href='#' onclick='deleteRecord("<?php echo $transaction_id; ?>","cash")' >X</a></td>	
				</tr>
				<?php
				}
				?> 				
			</tbody>

            </table>
        </div>

<?php
 if($revolvingTotal==""){ 
 } 
 else { 
	
 
 
 
	$next_id=$_SESSION['next_log_id'];
	$sqlBalance="select * from beginning_balance_cash where log_id='".$next_id."'";
	$rsBalance=$db->query($sqlBalance);
	$nmBalance=$rsBalance->num_rows;
	if($nmBalance>0){
		$transferBalance="update beginning_balance_cash set revolving_fund='".$revolvingTotal."',for_deposit='".$depositTotal."' where log_id='".$next_id."'";
	
	}
	else {
		$transferBalance="insert into beginning_balance_cash(log_id,revolving_fund,for_deposit) values ('".$next_id."','".$revolvingTotal."','".$depositTotal."')";

	}

	$transferRS=$db->query($transferBalance);	
	
	$sqlBalance="select * from ending_balance_cash where log_id='".$log_id."'";
	$rsBalance=$db->query($sqlBalance);
	$nmBalance=$rsBalance->num_rows;
	if($nmBalance>0){
		$transferBalance="update ending_balance_cash set revolving_fund='".$revolvingTotal."',for_deposit='".$depositTotal."' where log_id='".$log_id."'";
	
	}
	else {
		$transferBalance="insert into ending_balance_cash(log_id,revolving_fund,for_deposit) values ('".$log_id."','".$revolvingTotal."','".$depositTotal."')";
	
	}

	$transferRS=$db->query($transferBalance);		
	
	
	
	
 } 
 
 ?>
		
<br>
<?php require("test_cslip_list.php"); ?>


	</div>
	
</div>	
<?php require("test_forms.php"); ?>


