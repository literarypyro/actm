<?php
session_start();
?>
<?php
require("db_page.php");
?>

<?php
if(isset($_GET['edit_control'])){
	$_SESSION['control_id']=$_GET['edit_control'];

}
$log_id=$_SESSION['log_id'];
$db=retrieveDb();

$control_id=$_SESSION['control_id'];
?>
<?php
function calculateTicketSold($control_id,$db){
	$ticket['sjt']=0;
	$ticket['sjd']=0;
	$ticket['svt']=0;
	$ticket['svd']=0;
	
	$sql="select type,sum(initial) as initial, sum(initial_loose) as initial_loose from allocation where control_id='".$control_id."' group by type";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$ticket[$row['type']]=$row['initial']+$row['initial_loose'];
	}
	
	$sql="select type, sum(sealed) as sealed, sum(loose_good) as loose_good, sum(loose_defective) as loose_defective from control_unsold where control_id='".$control_id."' group by type";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$ticket[$row['type']]+=($row['sealed']+$row['loose_good']+$row['loose_defective']);
	}
	
	$sql="select * from control_sold where control_id='".$control_id."'";
	
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		
		$update="update control_sold set sjt='".$ticket['sjt']."',sjd='".$ticket['sjd']."',svt='".$ticket['svt']."',svd='".$ticket['svd']."' where control_id='".$control_id."'";
		$updateRS=$db->query($update);
	
	}
	else {
		$update="insert into control_sold(control_id,sjt,sjd,svt,svd) values ('".$control_id."','".$ticket['sjt']."','".$ticket['sjd']."','".$ticket['svt']."','".$ticket_svd."')";
		$updateRS=$db->query($update);
	
	}
	return $ticket;

}

function ticketDiscrepancy($control_id,$db){
	$ticket['sjt']=0;
	$ticket['sjd']=0;
	$ticket['svt']=0;
	$ticket['svd']=0;
	
	$sql="select type,sum(initial) as initial, sum(initial_loose) as initial_loose from allocation where control_id='".$control_id."' group by type";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$ticket[$row['type']]=$row['initial']+$row['initial_loose'];
	}
	
	$sql="select * from additional_allocation where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;


		$row=$rs->fetch_assoc();
		$ticket['sjt']+=$row['sjt']+$row['sjt_loose'];
		$ticket['sjd']+=$row['sjd']+$row['sjd_loose'];
		$ticket['svt']+=$row['svt']+$row['svt_loose'];
		$ticket['svd']+=$row['svd']+$row['svd_loose'];


	
	
	
	
	
	$sql="select type, sum(sealed) as sealed, sum(loose_good) as loose_good, sum(loose_defective) as loose_defective from control_unsold where control_id='".$control_id."' group by type";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$ticket[$row['type']]-=($row['sealed']+$row['loose_good']+$row['loose_defective']);
	}
	
	$t_label[0]='sjt';
	$t_label[1]='sjd';
	$t_label[2]='svt';
	$t_label[3]='svd';
	
	
	
	
		
	$sql="select * from control_sold where control_id='".$control_id."'";
	
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
	
		$ticket_sold['sjt']=$row['sjt'];
		$ticket_sold['sjd']=$row['sjd'];
		$ticket_sold['svt']=$row['svt'];
		$ticket_sold['svd']=$row['svd'];
	
	}	
	
	for($n=0;$n<count($t_label);$n++){
		if($ticket[$t_label[$n]]>$ticket_sold[$t_label[$n]]){
			$discrepancy_label[$t_label[$n]]="overage";
			$discrepancy_price[$t_label[$n]]=$ticket[$t_label[$n]]-$ticket_sold[$t_label[$n]];

		}
		else if($ticket[$t_label[$n]]<$ticket_sold[$t_label[$n]]){
			$discrepancy_label[$t_label[$n]]="shortage";
			$discrepancy_price[$t_label[$n]]=$ticket_sold[$t_label[$n]]-$ticket[$t_label[$n]];
			
		}
		else {
			$discrepancy_label[$t_label[$n]]="none";
			$discrepancy_price[$t_label[$n]]=0;
		}
			
		if($discrepancy_label[$t_label[$n]]=="none"){
		}
		else {
		
			$sql="select * from discrepancy_ticket where control_id='".$control_id."' and ticket_type='".$t_label[$n]."'";
			
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$update="update discrepancy_ticket set amount='".$discrepancy_price[$t_label[$n]]."',type='".$discrepancy_label[$t_label[$n]]."',transaction_id='".$control_id."' where id='".$row['id']."'";

				$updateRS=$db->query($update);
					
			}
			else {
				$update="insert into discrepancy_ticket(amount,type,ticket_type,control_id,transaction_id)";
				$update.=" values ('".$discrepancy_price[$t_label[$n]]."','".$discrepancy_label[$t_label[$n]]."','".$t_label[$n]."','".$control_id."','".$control_id."')";
				$updateRS=$db->query($update);	
			}	
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

		$db=retrieveDb();
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

			if($type=="remittance"){
				$controlSQL="select * from control_slip where ticket_seller='".$ticket_seller."'  order by id desc";
				//and status='close'
				$controlRS=$db->query($controlSQL);
				$controlRow=$controlRS->fetch_assoc();
				
				$control_log=$controlRow['log_id'];
				
					
				$sql="select * from cash_remittance where control_id='".$control_id."'";	
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where id='".$row['id']."'";
					$rs2=$db->query($update);
				}
				else {
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where control_id='".$control_id."' and cash_remittance=''";
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
			
			$sql="select * from transaction where id='".$_POST['ctf_transaction_id']."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			$type=$_POST['type'];
			
			$update="update transaction set transaction_type='".$type."',reference_id='".$reference_id."' where id='".$row['id']."'";
			
			$updateRS=$db->query($update);
		
			$insert_id=$row['id'];
			
			$transaction_id=$row['transaction_id'];
			$_SESSION['transact']=$transaction_id;
			
			$revolving=$_POST['revolving_remittance'];
			$reference_id=$_POST['reference_id'];	
			
			
			$sql="update cash_transfer set ticket_seller='".$ticket_seller."',total='".$revolving."',net_revenue='".$net."',total_in_words='".$totalWords."',station='".$station_entry."',type='".$type."',unit='".$unit."', destination_ca='".$destination_ca."',reference_id='".$reference_id."',control_id='".$control_id."' where transaction_id='".$transaction_id."'";
			$rs=$db->query($sql);
	
			if($type=="catransfer"){
				$sql="update cash_transfer set destination_ca='".$_POST['destination_cash_assistant']."',cash_assistant='".$_POST['cash_assistant']."' where transaction_id='".$transaction_id."'";
				$rs=$db->query($sql);
			}
			
			$sql="select * from cash_transfer where transaction_id='".$transaction_id."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			
			$insert_id=$row['id'];
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
			
			
			
			$update="insert into control_cash(control_id) values ('".$control_id."')";
			$updateRS=$db->query($update);
			
			if($type=="overage"){
				
				$update="update control_cash set overage='".$amount."' where control_id='".$control_id."'";
				$updateRS=$db->query($update);

				$shortage_ticket="select sum(price) as ticket_shortage from discrepancy_ticket where control_id='".$control_id."' and type='shortage' group by ticket_type";
				$shortage_rs=$db->query($shortage_ticket);
				$shortage_nm=$shortage_rs->num_rows;
					
				$shortage_total=0;	
				if($shortage_nm>0){
					for($p=0;$p<$shortage_nm;$p++){
						$shortage_row=$shortage_rs->fetch_assoc();
						$short_ticket[$shortage_row['ticket_type']]['amount']=$shortage_row['ticket_shortage'];
						$shortage_total+=$shortage_row['ticket_shortage'];
					
					}
					if($overage>$shortage_total){
					/*
						$unreg['sjt']=$short_ticket['sjt']['amount'];
						$unreg['sjd']=$short_ticket['sjd']['amount'];
						$unreg['svt']=$short_ticket['svt']['amount'];
						$unreg['svd']=$short_ticket['svd']['amount'];
						
						
						$unreg_sql="insert into unreg_sale(sjt,svt,sjd,svd,control_id) values ('".$unreg_sjt."','".$unreg_sjd."','".$unreg_svt."','".$unreg_svd."','".$control_id."')";	
						$unreg_rs=$db->query($unreg_sql);			
					*/	

					}
				}
			}
			else if($type=="shortage"){	
				$unpaid_sql="update control_cash set unpaid_shortage='".($amount)."' where control_id='".$control_id."'";
				$unpaid_rs=$db->query($unpaid_sql);			
			}	
			
		}
		if($_GET['shortage_payment']=="Y"){
			$depositpost=$total;
			$control_post=$control_id;
			$station_id=$station_entry;
			$cash_assist=$_POST['cash_assistant'];
			
			$sql="select * from cash_transfer where type='shortage' and control_id='".$control_id."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			$shortage_total=$_POST['shortage_total'];
			$net_revenue=$_POST['shortage_total'];
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				
				$update="update cash_transfer set net_revenue='".$net_revenue."' where id='".$row['id']."'";
				$updateRS=$db->query($update);
				
				$cash_transfer_id=$row['id'];
			}
			else {
			
				$sql="insert into transaction(date,log_id,log_type,transaction_type,reference_id) values ('".$date."','".$log_id."','cash','".$type."','".$reference_id."')";
				$rs=$db->query($sql);

				$insert_id=$db->insert_id;
				
				$transaction_id=$date_id."_".$insert_id;
				$_SESSION['transact']=$transaction_id;
				$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
				$rs=$db->query($sql);

				$sql="insert into cash_transfer(log_id,time,ticket_seller,cash_assistant,type,";
				$sql.="transaction_id,net_revenue,station,reference_id,unit,control_id) values ";
				$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$_POST['cash_assistant']."','shortage',";
				$sql.="'".$transaction_id."','".$net_revenue."','".$station_entry."','".$control_id."','".$unit."','".$control_id."')";
				
				$rs=$db->query($sql);
				$insert_id=$db->insert_id;
				$cash_transfer_id=$insert_id;

				
			
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
			

			$denom[0]["value"]=$_POST['1000denom_3'];
			$denom[1]["value"]=$_POST['500denom_3'];
			$denom[2]["value"]=$_POST['200denom_3'];
			$denom[3]["value"]=$_POST['100denom_3'];
			$denom[4]["value"]=$_POST['50denom_3'];
			$denom[5]["value"]=$_POST['20denom_3'];
			$denom[6]["value"]=$_POST['10denom_3'];
			$denom[7]["value"]=$_POST['5denom_3'];
			$denom[8]["value"]=$_POST['1denom_3'];
			$denom[9]["value"]=$_POST['25cdenom_3'];
			$denom[10]["value"]=$_POST['10cdenom_3'];
			$denom[11]["value"]=$_POST['5cdenom_3'];

				
			
			$sqlDenom="delete from denomination where cash_transfer_id='".$cash_transfer_id."'";
			$rsDenom=$db->query($sqlDenom);
			for($i=0;$i<count($denom);$i++){
				if($denom[$i]["value"]>0){
					//$sqlInsert="update denomination set quantity='".$denom[$i]['value']."' where demonination='".$denom[$i]['id']."' and cash_transfer_id='".$insert_id."'";
					$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
					$sqlInsert.=" values ('".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
					$sqlInsertRS=$db->query($sqlInsert);			
				}
			}

			$update="update control_cash set unpaid_shortage=unpaid_shortage-".$net_revenue." where control_id='".$control_id."'";
			$rs2=$db->query($update);
		}
		echo "<script langage='javascript'>window.opener.location.reload();</script>";
		$_GET['edit_control']=$_SESSION['control_id'];
		
	}
	
}
if(isset($_POST['to_ticket_seller'])){

	$receive_day=date("Y-m-d",strtotime($_POST['receive_date']));
		
	$receive_time=date("Y-m-d",strtotime($receive_day." ".$_POST['receive_time']));
		
	$date=$receive_time;
	$date_id=date("Ymd",strtotime($_POST['receive_date']));

	$type="allocation";
	
	$sjt=$_POST['sjt'];
	$sjd=$_POST['sjd'];
	$svt=$_POST['svt'];
	$svd=$_POST['svd'];
	
	$sjt_loose=$_POST['sjt_loose'];
	$sjd_loose=$_POST['sjd_loose'];
	$svt_loose=$_POST['svt_loose'];
	$svd_loose=$_POST['svd_loose'];
	$station=$_POST['station'];

	
	$control_id=$_POST['to_ticket_seller'];
	
	$control_sql="select * from control_slip where id='".$control_id."' limit 1";
	$control_rs=$db->query($control_sql);
		
	$control_row=$control_rs->fetch_assoc();
		
	$ticket_seller=$control_row['ticket_seller'];
		
	$unit=$control_row['unit'];
	
	
	
	$cash_assistant=$_POST['cash_assistant'];
	$reference_id=$_POST['reference_id'];
	$ticket_type=$_POST['classification'];

	if($ticket_type=="ticket_seller"){
		$transaction_type="ticket";

	}
	else if($ticket_type=="catransfer"){
		$transaction_type="ticket_catransfer";
	}
	else if($ticket_type=="finance"){
		$transaction_type="finance";
		
	}
	else if($ticket_type=="annex"){

		$transaction_type="annex";
	}
	$unit_type=$_POST['unit_type'];
	$classification=$_POST['classification'];
	
	$db=retrieveDb();
	
//	$sql="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','".$transaction_type."','".$type."')";
	
	if($_POST['form_action']=="new"){
		
		$sql="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','".$transaction_type."','allocation')";

		$rs=$db->query($sql);

		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;
		$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs=$db->query($sql);
		
		$sql="insert into ticket_order(log_id,time,ticket_seller,cash_assistant,type,";
		$sql.="transaction_id,sjt,sjd,svt,svd,sjt_loose,sjd_loose,svt_loose,svd_loose,unit,classification,reference_id,station,control_id) values ";
		$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$cash_assistant."','".$type."',";
		$sql.="'".$transaction_id."','".$sjt."','".$sjd."','".$svt."','".$svd."','".$sjt_loose."',";
		$sql.="'".$sjd_loose."','".$svt_loose."','".$svd_loose."','".$unit_type."','".$classification."','".$reference_id."','".$station."','".$control_id."')";

		$rs=$db->query($sql);
		$insert_id=$db->insert_id;
		$ticket_id=$insert_id;
		if($transaction_type=="ticket"){
			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit_type."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;		
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				$control_id=$row['id'];
				
				$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				if($nm==0){
					$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
					$updateRS=$db->query($update);
				}			
			}		
		}
	}
	else if($_POST['form_action']=="edit"){
		$form_action="edit";
		
		$sql="select * from transaction where id='".$_POST['ticket_transaction_id']."'";

		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$transaction_id=$row['transaction_id'];	
		$insert_id=$row['id'];
		$reference_id=$_POST['reference_id'];		
		$ticket_type=$_POST['classification'];		
		
		if($ticket_type=="ticket_seller"){
			$transaction_type="ticket";

		}
		else if($ticket_type=="catransfer"){
			$transaction_type="ticket_catransfer";
		}
		else if($ticket_type=="finance"){
			$transaction_type="finance";
			
		}
		else if($ticket_type=="annex"){

			$transaction_type="annex";
		}		
		$sql2="update transaction set log_type='".$transaction_type."' where id='".$_POST['ticket_transaction_id']."'";
		$rs2=$db->query($sql2);		
		
		
		$sql2="update ticket_order set reference_id='".$reference_id."',ticket_seller='".$ticket_seller."',station='".$station."',sjt='".$sjt."',svt='".$svt."',sjd='".$sjd."',svd='".$svd."',sjt_loose='".$sjt_loose."',sjd_loose='".$sjd_loose."',svt_loose='".$svt_loose."',svd_loose='".$svd_loose."',control_id='".$control_id."' where transaction_id='".$row['transaction_id']."'";
		$rs2=$db->query($sql2);


		if($transaction_type=="ticket"){
			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit_type."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;		
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				$control_id=$row['id'];
				
				$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				if($nm==0){
					$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
					$updateRS=$db->query($update);
				}			
				
				
			}		
		}
		
	}

	
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
<script type="text/javascript" src="js/files/control_slip_function.js"></script>
<script type='text/javascript'>
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

function editTransact(transact_id,transact_type,control_id){
 	$('#control_spinner').show();


	$.getJSON("processing.php?transaction_id="+transact_id+"&type="+transact_type+"&edit_control="+control_id, function(data) {


		if(data.type=='ctf'){
			for(i=0;i<data.currency['denom_count'];i++){
				$('#ctf_denom #'+data.currency[i]['label']).val(data.currency[i]['value']);
				$('#ctf_denom .'+data.currency[i]['label']).val(data.currency[i]['value']*data.currency[i]['id']);
			
			}
			calculateTotal();
			
			$('.form_action').val('edit');
			$('#control_spinner').hide();
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
    });
 }
 
 
 
 
 </script>




<?php

?>
<?php require("test_cslip_header.php"); ?>
<?php require("test_control_slip_panel.php"); ?>
<?php require("test_control_slip_adjustments.php"); ?>
<?php require("test_control_slip_net.php"); ?>
<?php require("test_forms.php"); ?>
<?php require("test_forms2.php"); ?>
