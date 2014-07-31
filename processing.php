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

if(isset($_GET['transaction_id'])){
	$db=new mysqli("localhost","root","","finance");

	if($_GET['type']=="ctf"){
		$form_action="edit";
		$sql="select * from transaction where id='".$_GET['transaction_id']."'";
	//	echo $sql;
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$data['type']=$_GET['type'];
		
		$data['tID']=$_GET['transaction_id'];
		$data['transactType']=$row['transaction_type'];
		$data['transactionID']=$row['transaction_id'];


		$csql="select * from control_slip where id='".$row['control_id']."'";
		
		$crs=$db->query($csql);
		$crow=$rs->fetch_assoc();







		
		$sql2="select * from cash_transfer where transaction_id='".$row['transaction_id']."'";
		$rs2=$db->query($sql2);
		$row2=$rs2->fetch_assoc();
		
		$data['control_id']=$row2['control_id'];
		$data['reference_id']=$row2['reference_id'];
		$data['cash_transfer_id']=$row2['id'];
		$data['totalpost']=$row2['total']+$row2['net_revenue'];
		$data['revolvingpost']=$row2['total'];
		$data['depositpost']=$row2['net_revenue'];
		$data['totalWordpost']=$row2['total_in_words'];

//		$ticket_seller=$crow['ticket_seller'];
//		$unit=$crow['unit'];

		$data['ticketsellerpost']=$crow['ticket_seller'];
		$data['control_post']=$row2['control_id'];
		$data['transactDate']=$row2['time'];
		$data['receive_date']=date("m/d/Y",strtotime($row2['time']));
		$data['receive_time']=date("H:i:s",strtotime($row2['time']));
		$data['station']=$row2['station'];
		$data['destination_ca']=$row2['destination_ca'];
		$data['unit']=$row2['unit'];
		$data['cash_assist']=$row2['cash_assistant'];	
		$data['cash_transfer_id']=$row2['id'];
		$cash_transfer_id=$row2['id'];

		$denomSQL="select * from denomination where cash_transfer_id='".$cash_transfer_id."'";

		$denomRS=$db->query($denomSQL);
		$denomNM=$denomRS->num_rows;
		for($i=0;$i<$denomNM;$i++){
			$denomRow=$denomRS->fetch_assoc();
			$data['currency'][$i]['value']=$denomRow['quantity'];
			$data['currency'][$i]['id']=$denomRow['denomination'];

			if($denomRow['denomination']<1){
				$data['currency'][$i]['label']=($denomRow['denomination']*100)."cdenom";
			}
			else {
				$data['currency'][$i]['label']=$denomRow['denomination']."denom";
			}
		}
			
		$data['currency']['denom_count']=$denomNM;	
		echo json_encode($data);
	}
	else if($_GET['type']=='pnb'){
		$form_action="edit";

		$sql="select * from transaction where id='".$_GET['transaction_id']."'";	
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
	
		$data['type']=$_GET['type'];
		
		$data['tID']=$_GET['transaction_id'];
		$data['transactType']=$row['transaction_type'];
		$data['transactionID']=$row['transaction_id'];
	
		$sql2="select * from pnb_deposit where transaction_id='".$row['transaction_id']."'";

		$rs2=$db->query($sql2);
		$row2=$rs2->fetch_assoc();


		$data['transactDate']=$row2['time'];
		$data['receive_date']=date("m/d/Y",strtotime($row2['time']));
		$data['receive_time']=date("H:i:s",strtotime($row2['time']));
		$data['depositType']=$row2['type'];
		$data['cash_assist']=$row2['cash_assistant'];	
		$data['totalpost']=$row2['amount'];
		$data['reference_id']=$row2['reference_id'];
		$data['deposit_id']=$row2['id'];
		$cash_transfer_id=$row2['id'];

		$denomSQL="select * from denomination where cash_transfer_id='pnb_".$cash_transfer_id."'";

		$denomRS=$db->query($denomSQL);
		$denomNM=$denomRS->num_rows;
		for($i=0;$i<$denomNM;$i++){
			$denomRow=$denomRS->fetch_assoc();
			$data['currency'][$i]['value']=$denomRow['quantity'];
			$data['currency'][$i]['id']=$denomRow['denomination'];

			if($denomRow['denomination']<1){
				$data['currency'][$i]['label']=($denomRow['denomination']*100)."cdenom";
			}
			else {
				$data['currency'][$i]['label']=$denomRow['denomination']."denom";
			}
		}
			
		$data['currency']['denom_count']=$denomNM;	
		
	

		echo json_encode($data);
		
	
	}
	
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
	

	$type="";	
	$cash_remittance=($cash_total+$partial_remittance)*1;
	
	$discrepancy_amount=0;
	
	
	if($control_remittance==$cash_remittance){
		echo "none";
		
	}
	else {
		if($control_remittance>$cash_remittance){
			echo "shortage;";
			$discrepancy_amount=$control_remittance-$cash_remittance;
			$type="shortage";
		}
		else if($control_remittance<$cash_remittance){
			echo "overage;";
			$discrepancy_amount=$cash_remittance-$control_remittance;
			$type="overage";
		}
	
	
	}
	

		if($type=="overage"){
			$overage_amount=$amount;
			
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
					$unreg['sjt']=$short_ticket['sjt']['amount'];
					$unreg['sjd']=$short_ticket['sjd']['amount'];
					$unreg['svt']=$short_ticket['svt']['amount'];
					$unreg['svd']=$short_ticket['svd']['amount'];
					
					
					$unreg_sql="insert into unreg_sale(sjt,svt,sjd,svd,control_id) values ('".$unreg_sjt."','".$unreg_sjd."','".$unreg_svt."','".$unreg_svd."','".$control_id."')";	
					$unreg_rs=$db->query($unreg_sql);			
					
					echo ($overage-$shortage_total).";";
					
					echo "unreg";
				}
				else {
					$unpaid_sql="update control_cash set unpaid_shortage='".$shortage_total."' where control_id='".$control_id."'";
					$unpaid_rs=$db->query($unpaid_sql);
					
					echo $discrepancy_amount.";";
					echo "unpaid";	
				}
			}
		}
		else if($type=="shortage"){
			$unpaid_sql="update control_cash set unpaid_shortage='".($discrepancy_amount)."' where control_id='".$control_id."'";
			$unpaid_rs=$db->query($unpaid_sql);
			echo $discrepancy_amount.";";
			echo "unpaid";	
			
			
	
		}
	
}	

if(isset($_GET['summary_sales'])){
	
	$dsrDate=$_SESSION['log_date'];
	$station=$_SESSION['station'];
	$stationStamp=$station;
	
	$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

	$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";

	//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	$sjt_sales=0;
	$sjd_sales=0;
	$svt_sales=0;
	$svd_sales=0;

	$fare_adjustment=0;
	$ot_amount=0;
	$unreg_sale=0;

	$discount=0;
	$refund=0;

	$grandTotal=0;
	$deductionsTotal=0;
	$netSales=0;

	for($i=0;$i<$nm;$i++){


		$row=$rs->fetch_assoc();
		$log_id=$row['id'];

		$sql2="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$sjt_sales+=$row2['sjt']*1;		
			$svt_sales+=$row2['svt']*1;
			$sjd_sales+=$row2['sjd']*1;
			$svd_sales+=$row2['svd']*1;
				
		}

	//	$sql2="select sum(sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
		$sql2="select (sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$fare_adjustment+=$row2['fare_adjustment'];
		}	
		
	//	$sql2="select sum(sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
		$sql2="select (sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$unreg_sale+=$row2['unreg_sale'];
		}		


	//	$sql2="select sum(ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' group by type";
		$sql2="select (ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' group by type";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$ot_amount+=$row2['ot'];
		}	
		
	//	$sql2="select sum(sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
		$sql2="select (sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$discount+=$row2['discount'];
		}		
		$sql2="select (sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
		
	//	$sql2="select sum(sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$refund+=$row2['refund'];
		}	
		
	}

	$grandTotal+=$sjt_sales;
	$grandTotal+=$sjd_sales;
	$grandTotal+=$svt_sales;
	$grandTotal+=$svd_sales;

	$grandTotal+=$fare_adjustment;
	$grandTotal+=$ot_amount;
	$grandTotal+=$unreg_sale;

	$unreg_deduction=$unreg_sale;

	$deductionsTotal+=$discount;
	$deductionsTotal+=$refund;

	$netSales=$grandTotal-$deductionsTotal;
	
	$data["sj_sales"]=$sjt_sales;
	$data["sjd_sales"]=$sjd_sales;
	$data["sv_sales"]=$svt_sales;
	$data["svd_sales"]=$svd_sales;
	$data["fare_sales"]=$fare_adjustment;
	$data["ot_sales"]=$ot_amount;
	$data["unreg_sales"]=$unreg_sale;
	$data["gross_sales"]=$grandTotal;
	$data["refund_sales"]=$refund;
	$data["disc_sales"]=$discount;
	$data["net_sales"]=$netSales;
	
	
	echo json_encode($data);

}

if(isset($_GET['summary_cash'])){
	$dsrDate=$_SESSION['log_date'];
	$station=$_SESSION['station'];
	$stationStamp=$station;
	
	$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

	$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";

	//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	$sjt_sales=0;
	$sjd_sales=0;
	$svt_sales=0;
	$svd_sales=0;

	$fare_adjustment=0;
	$ot_amount=0;
	$unreg_sale=0;

	$discount=0;
	$refund=0;

	$grandTotal=0;
	$deductionsTotal=0;
	$netSales=0;

	/*
	$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
	$rsAlt=$db->query($sqlAlt);
	$nmAlt=$rsAlt->num_rows;
	if($nmAlt>0){
		$nm++;
	}
	*/

	for($i=0;$i<$nm;$i++){


		$row=$rs->fetch_assoc();
		$log_id=$row['id'];

		$sql2="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."'";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$sjt_sales+=$row2['sjt']*1;		
			$svt_sales+=$row2['svt']*1;
			$sjd_sales+=$row2['sjd']*1;
			$svd_sales+=$row2['svd']*1;
				
		}

	//	$sql2="select sum(sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where  remit_log='".$log_id."'";
		$sql2="select (sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where  remit_log='".$log_id."'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$fare_adjustment+=$row2['fare_adjustment'];
		}	
		
	//	$sql2="select sum(sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."'";
		$sql2="select (sjt+sjd+svt+svd) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$unreg_sale+=$row2['unreg_sale'];
		}		


	//	$sql2="select sum(ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' group by type";
		$sql2="select (ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' group by type";


		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$ot_amount+=$row2['ot'];
		}	
		
	//	$sql2="select sum(sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."'";
		$sql2="select (sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."'";



		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$discount+=$row2['discount'];
		}		
		
	//	$sql2="select sum(sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."'";
		$sql2="select (sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$refund+=$row2['refund'];
		}	
		
	}

	$grandTotal+=$sjt_sales;
	$grandTotal+=$sjd_sales;
	$grandTotal+=$svt_sales;
	$grandTotal+=$svd_sales;

	$grandTotal+=$fare_adjustment;
	$grandTotal+=$ot_amount;
	$grandTotal+=$unreg_sale;

	$unreg_deduction=$unreg_sale;

	$deductionsTotal+=$discount;
	$deductionsTotal+=$refund;

	$netSales=$grandTotal-$deductionsTotal;

	$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));
	$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
	//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	/*
	$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
	$rsAlt=$db->query($sqlAlt);
	$nmAlt=$rsAlt->num_rows;

	if($nmAlt>0){
		$nm++;

	}
	*/

	$cash_beginning=0;
	$revolving_fund=0;
	$for_deposit=0;
	$subtotal=0;
	$pnb_deposit_c=0;
	$pnb_deposit_p=0;
	$subtotal_2=0;
	$overage=0;
	$unpaid_shortage=0;
	$cash_ending=0;

	for($i=0;$i<$nm;$i++){
		if($i==0){

			$row=$rs->fetch_assoc();
			$log_id=$row['id'];
			
			$sql2="select * from beginning_balance_cash where log_id='".$log_id."'";
			//echo $sql2;
			$rs2=$db->query($sql2);
			$row2=$rs2->fetch_assoc();
		//	$cash_beginning=$row2['revolving_fund']+$row2['for_deposit'];
			$cash_beginning=$row2['for_deposit'];
			$revolving_fund=$row2['revolving_fund'];		
		}
		else {
			$row=$rs->fetch_assoc();
			$log_id=$row['id'];

		}
		/*
		$sql2="select sum(control_remittance) as net_sales from cash_remittance where log_id='".$log_id."'";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;			
		if($nm2>0){
			$row2=$rs2->fetch_assoc();
			$for_deposit+=$row2['net_sales'];
		}
	*/
		
		$for_deposit=$netSales;
		
		//	$sql2="select sum(total) as revolving_fund from cash_transfer where log_id='".$log_id."' and type='remittance'";
		
		$sql2="select * from beginning_balance_cash where log_id='".$log_id."'";
		//echo $sql2;
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;			
		if($nm2>0){
			$row2=$rs2->fetch_assoc();
			//$revolving_fund+=$row2['revolving_fund'];
	//		$for_deposit+=$row2['for_deposit'];
			
			
		}

		
		$sql2="select sum(amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='current'";
	//	$sql2="select (amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='current'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;			
		if($nm2>0){
			$row2=$rs2->fetch_assoc();
			$pnb_deposit_c+=$row2['deposit'];
		}	
		
		$sql2="select sum(amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='previous'";
	//	$sql2="select (amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='previous'";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;			
		if($nm2>0){
			$row2=$rs2->fetch_assoc();
			$pnb_deposit_p+=$row2['deposit'];
		}	
		
	//	$sql2="select sum(if(type='overage',amount,0)) as overage,sum(if(type='shortage',amount,0)) as shortage	from discrepancy where log_id='".$log_id."'";
		$sql2="select (if(type='overage',amount,0)) as overage,(if(type='shortage',amount,0)) as shortage	from discrepancy where log_id='".$log_id."'";
		//	$sql2="select sum(unpaid_shortage) as unpaid_shortage, sum(overage) as overage from control_cash where control_id in (SELECT control_id FROM remittance where log_id='".$log_id."')";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;	
		
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$overage+=$row2['overage'];
			
			$unpaid_shortage+=$row2['shortage'];
			
		}

		
		$discrepancySQL="SELECT * FROM transaction inner join cash_transfer on transaction.transaction_id=cash_transfer.transaction_id where transaction_type='shortage' and transaction.log_id='".$log_id."'";
	//	echo $discrepancySQL;
		$discrepancyRS=$db->query($discrepancySQL);

		$discrepancyNM=$discrepancyRS->num_rows;
		
		$paid_shortage=0;

		if($discrepancyNM>0){
			for($aa=0;$aa<$discrepancyNM;$aa++){
			$discrepancyRow=$discrepancyRS->fetch_assoc();
			$paid_shortage+=$discrepancyRow['net_revenue']+$discrepancyRow['total'];
			//$unpaid_shortage-=$paid_shortage;
			}
		
		}	
		$unpaid_shortage-=$paid_shortage;	
	}
		$subtotal=$for_deposit+$revolving_fund+$cash_beginning;	
		$deposit_total=$pnb_deposit_c+$pnb_deposit_p;
		$subtotal_2=$subtotal-$deposit_total;
		//$overage-=$unreg_deduction;
		$overage=$overage;
		$cash_ending=$subtotal_2+$overage-$unpaid_shortage;
		$data2["cash_beginning"]=$cash_beginning;
		$data2["revolving_fund"]=$revolving_fund;
		$data2["for_deposit"]=$for_deposit;
		$data2["subtotal"]=$subtotal;
		$data2["pnb_deposit_c"]=$pnb_deposit_c;
		$data2["pnb_deposit_p"]=$pnb_deposit_p;
		$data2["subtotal_2"]=$subtotal_2;
		$data2["overage"]=$overage;
		$data2["unpaid_shortage"]=$unpaid_shortage;
		$data2["cash_ending"]=$cash_ending;
		
		echo json_encode($data2);
	}
	
	
	
	if(isset($_GET['summary_tickets'])){
		$dsrDate=$_SESSION['log_date'];
		$station=$_SESSION['station'];
		$stationStamp=$station;

		$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));
		$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
		//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		/*
		$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
		$rsAlt=$db->query($sqlAlt);
		$nmAlt=$rsAlt->num_rows;

		if($nmAlt>0){
			$nm++;

		}
		*/
		$sjt_beginning_balance=0;
		$sjd_beginning_balance=0;
		$svt_beginning_balance=0;
		$svd_beginning_balance=0;

		$sjt_initial_amount=0;
		$sjd_initial_amount=0;
		$svt_initial_amount=0;
		$svd_initial_amount=0;

		$sjt_additional_amount=0;	
		$sjd_additional_amount=0;	
		$svt_additional_amount=0;	
		$svd_additional_amount=0;	

		$sjt_subtotal=0;	
		$sjd_subtotal=0;	
		$svt_subtotal=0;	
		$svd_subtotal=0;	

		$sjt_deductions=0;	
		$sjd_deductions=0;	
		$svt_deductions=0;	
		$svd_deductions=0;	

		$sjt_sold=0;	
		$sjd_sold=0;	
		$svt_sold=0;	
		$svd_sold=0;

		$sjt_loose=0;	
		$sjd_loose=0;	
		$svt_loose=0;	
		$svd_loose=0;	

		$sjt_defective=0;	
		$sjd_defective=0;	
		$svt_defective=0;	
		$svd_defective=0;	


		$sjt_overage=0;
		$sjd_overage=0;
		$svt_overage=0;
		$svd_overage=0;

		$sjt_shortage=0;
		$sjd_shortage=0;
		$svt_shortage=0;
		$svd_shortage=0;

		$sjt_discrep=0;
		$sjd_discrep=0;
		$svt_discrep=0;
		$svd_discrep=0;

		$sjt_label="";
		$sjd_label="";
		$svt_label="";
		$svd_label="";

		$sjt_physically_defective=0;
		$sjd_physically_defective=0;
		$svt_physically_defective=0;
		$svd_physically_defective=0;

		for($i=0;$i<$nm;$i++){
			if($i==0){

				$row=$rs->fetch_assoc();
				$log_id=$row['id'];
				
				$sql2="select * from beginning_balance_sjt where log_id='".$log_id."'";

				$rs2=$db->query($sql2);
				$row2=$rs2->fetch_assoc();
				
				$sjt_beginning_balance=$row2['sjt']+$row2['sjt_loose'];
				$sjd_beginning_balance=$row2['sjd']+$row2['sjd_loose'];
				

				
				$sql2="select * from beginning_balance_svt where log_id='".$log_id."'";
				$rs2=$db->query($sql2);
				$row2=$rs2->fetch_assoc();

				$svt_beginning_balance=$row2['svt']+$row2['svt_loose'];
				$svd_beginning_balance=$row2['svd']+$row2['svd_loose'];
				
					
			
			}
			else {

				$row=$rs->fetch_assoc();
				$log_id=$row['id'];
			}
			
			$sql2="select * from transaction inner join ticket_order on transaction.transaction_id=ticket_order.transaction_id where transaction.log_id='".$log_id."' and log_type='finance'";
			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;	
			if($nm2>0){
				for($k=0;$k<$nm2;$k++){
					$row2=$rs2->fetch_assoc();
					$sjt_initial_amount+=$row2['sjt']+$row2['sjt_loose'];
					$sjd_initial_amount+=$row2['sjd']+$row2['sjd_loose'];
					$svt_initial_amount+=$row2['svt']+$row2['svt_loose'];
					$svd_initial_amount+=$row2['svd']+$row2['svd_loose'];
			
				}
			}

			$sql2="select * from transaction inner join ticket_order on transaction.transaction_id=ticket_order.transaction_id where transaction.log_id='".$log_id."' and log_type='annex'";

			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;	
			if($nm2>0){
				for($k=0;$k<$nm2;$k++){
					$row2=$rs2->fetch_assoc();

					$sjt_additional_amount+=$row2['sjt']+$row2['sjt_loose'];	
					$sjd_additional_amount+=$row2['sjd']+$row2['sjd_loose'];	
					$svt_additional_amount+=$row2['svt']+$row2['svt_loose'];	
					$svd_additional_amount+=$row2['svd']+$row2['svd_loose'];	
				
				
				}
			}	
			/*
			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;	
			for($k=0;$k<$nm2;$k++){
				$row2=$rs2->fetch_assoc();
				if($row2['type']=="sjt"){
					$sjt_initial_amount+=$row2['initial'];

					$sjt_additional_amount+=$row2['additional'];	

					
				
				}
				else if($row2['type']=="sjd"){
					$sjd_initial_amount+=$row2['initial'];		
					$sjd_additional_amount+=$row2['additional'];	

				}
				else if($row2['type']=="svt"){
					$svt_initial_amount+=$row2['initial'];		
					$svt_additional_amount+=$row2['additional'];	

				}
				else if($row2['type']=="svd"){
					$svd_initial_amount+=$row2['initial'];		
					$svd_additional_amount+=$row2['additional'];	

				}
				
					
			}

		*/	
			$sjt_subtotal=$sjt_beginning_balance+$sjt_initial_amount+$sjt_additional_amount;	
			$sjd_subtotal=$sjd_beginning_balance+$sjd_initial_amount+$sjd_additional_amount;	
			$svt_subtotal=$svt_beginning_balance+$svt_initial_amount+$svt_additional_amount;	
			$svd_subtotal=$svd_beginning_balance+$svd_initial_amount+$svd_additional_amount;	

			//$sql2="select sum(sjt) as sjt,sum(sjd) as sjd,sum(svt) as svt, sum(svd) as svd from control_sold inner join remittance on control_sold.control_id=remittance.control_id where log_id='".$log_id."'";
			$sql2="select * from control_sold inner join remittance on control_sold.control_id=remittance.control_id where log_id='".$log_id."'";

			
			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;
			
			if($nm2>0){
				for($k=0;$k<$nm2;$k++){

				$row2=$rs2->fetch_assoc();
				$sjt_sold+=$row2['sjt'];
				$sjd_sold+=$row2['sjd'];
				$svt_sold+=$row2['svt'];
				$svd_sold+=$row2['svd'];
				}
			}

			$sql2="select * from control_unsold inner join control_remittance on control_unsold.control_id=control_remittance.control_id where log_id='".$log_id."'";

			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;	
			for($k=0;$k<$nm2;$k++){

				$row2=$rs2->fetch_assoc();
				if($row2['type']=="sjt"){
					$sjt_loose+=$row2['loose_good'];

					$sjt_defective+=$row2['loose_defective'];	

					
				
				}
				else if($row2['type']=="sjd"){
					$sjd_loose+=$row2['loose_good'];

					$sjd_defective+=$row2['loose_defective'];	

				}
				else if($row2['type']=="svt"){
					$svt_loose+=$row2['loose_good'];

					$svt_defective+=$row2['loose_defective'];	

				}
				else if($row2['type']=="svd"){
					$svd_loose+=$row2['loose_good'];

					$svd_defective+=$row2['loose_defective'];	

				}
				
					
			}
			$sjt_deduction=$sjt_defective+$sjt_sold;	
			$sjd_deduction=$sjd_defective+$sjd_sold;	
			$svt_deduction=$svt_defective+$svt_sold;	
			$svd_deduction=$svd_defective+$svd_sold;		

		//	$sql2="select sum(amount) as ticket_sum,ticket_type,type from discrepancy_ticket where transaction_id in (select control_slip.id from control_slip inner join remittance on control_slip.id=remittance.control_id where remittance.log_id='".$log_id."') group by ticket_type";
			
		//	$sql2="select sum(amount) as ticket_sum,ticket_type,type from discrepancy_ticket inner join control_remittance on transaction_id=control_id where remit_log='".$log_id."' group by ticket_type";
			
			$sql2="select (amount) as ticket_sum,ticket_type,type from discrepancy_ticket inner join control_remittance on transaction_id=control_id where remit_log='".$log_id."' group by ticket_type";


			
		//	echo $sql2;
			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;		
			
			for($k=0;$k<$nm2;$k++){
				$row2=$rs2->fetch_assoc();
				if($row2['ticket_type']=="sjt"){
					if($row2['type']=="shortage"){
						$sjt_shortage+=$row2['ticket_sum'];
					}
					else if($row2['type']=="overage"){
						$sjt_overage+=$row2['ticket_sum'];
					
					}

					
				
				}
				else if($row2['ticket_type']=="sjd"){
					if($row2['type']=="shortage"){
						$sjd_shortage+=$row2['ticket_sum'];
					
					}
					else if($row2['type']=="overage"){
						$sjd_overage+=$row2['ticket_sum'];
					
					}

				}
				else if($row2['ticket_type']=="svt"){
					if($row2['type']=="shortage"){
						$svt_shortage+=$row2['ticket_sum'];
					
					}
					else if($row2['type']=="overage"){
						$svt_overage+=$row2['ticket_sum'];
					
					}

				}
				else if($row2['ticket_type']=="svd"){
					if($row2['type']=="shortage"){
						$svd_shortage+=$row2['ticket_sum'];
					
					}
					else if($row2['type']=="overage"){
						$svd_overage+=$row2['ticket_sum'];
					
					}

				}
				
					
			}


			
			
			$sjt_discrep=$sjt_overage-$sjt_shortage;
			
			$sjd_discrep=$sjd_overage-$sjd_shortage;
			$svt_discrep=$svt_overage-$svt_shortage;
			$svd_discrep=$svd_overage-$svd_shortage;

			if($sjt_discrep<0){
				$sjt_label="(".($sjt_discrep*-1).")";
			}
			else {
				$sjt_label=$sjt_discrep;
			}
			if($sjd_discrep<0){
				$sjd_label="(".($sjd_discrep*-1).")";
			}
			else {
				$sjd_label=$sjd_discrep;
			}
			if($svt_discrep<0){
				$svt_label="(".($svt_discrep*-1).")";
			}
			else {
				$svt_label=$svt_discrep;
			}
			if($svd_discrep<0){
				$svd_label="(".($svd_discrep*-1).")";
			}
			else {
				$svd_label=$svd_discrep;
			}

			$sql2="select * from physically_defective where log_id='".$log_id."'";
			
			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;	
			if($nm2>0){
				$row2=$rs2->fetch_assoc();
				$sjt_physically_defective+=$row2['sjt'];
				$sjd_physically_defective+=$row2['sjd'];
				$svt_physically_defective+=$row2['svt'];
				$svd_physically_defective+=$row2['svd'];
				

			}
			

			
		}	
		$sjt_grand_total=$sjt_subtotal-$sjt_physically_defective-$sjt_deduction+$sjt_discrep;
		$sjd_grand_total=$sjd_subtotal-$sjd_physically_defective-$sjd_deduction+$sjd_discrep;
		$svt_grand_total=$svt_subtotal-$svt_physically_defective-$svt_deduction+$svt_discrep;
		$svd_grand_total=$svd_subtotal-$svd_physically_defective-$svd_deduction+$svd_discrep;
		$svd_grand_total=$svd_subtotal-$svd_physically_defective-$svd_deduction+$svd_discrep;
			


		$data3["sjt_beginning_balance"]=$sjt_beginning_balance;
		$data3["sjd_beginning_balance"]=$sjd_beginning_balance;
		$data3["svt_beginning_balance"]=$svt_beginning_balance;
		$data3["svd_beginning_balance"]=$svd_beginning_balance;

		$data3["sjt_initial_amount"]=$sjt_initial_amount;
		$data3["sjd_initial_amount"]=$sjd_initial_amount;
		$data3["svt_initial_amount"]=$svt_initial_amount;
		$data3["svd_initial_amount"]=$svd_initial_amount;

		$data3["sjt_additional_amount"]=$sjt_additional_amount;	
		$data3["sjd_additional_amount"]=$sjd_additional_amount;	
		$data3["svt_additional_amount"]=$svt_additional_amount;	
		$data3["svd_additional_amount"]=$svd_additional_amount;	

		$data3["sjt_subtotal"]=$sjt_subtotal;	
		$data3["sjd_subtotal"]=$sjd_subtotal;	
		$data3["svt_subtotal"]=$svt_subtotal;	
		$data3["svd_subtotal"]=$svd_subtotal;	

		$data3["sjt_deductions"]=$sjt_deductions;	
		$data3["sjd_deductions"]=$sjd_deductions;	
		$data3["svt_deductions"]=$svt_deductions;	
		$data3["svd_deductions"]=$svd_deductions;	

		$data3["sjt_sold"]=$sjt_sold;	
		$data3["sjd_sold"]=$sjd_sold;	
		$data3["svt_sold"]=$svt_sold;	
		$data3["svd_sold"]=$svd_sold;

		$data3["sjt_loose"]=$sjt_loose;	
		$data3["sjd_loose"]=$sjd_loose;	
		$data3["svt_loose"]=$svt_loose;	
		$data3["svd_loose"]=$svd_loose;	

		$data3["sjt_defective"]=$sjt_defective;	
		$data3["sjd_defective"]=$sjd_defective;	
		$data3["svt_defective"]=$svt_defective;	
		$data3["svd_defective"]=$svd_defective;	


		$data3["sjt_overage"]=$sjt_overage;
		$data3["sjd_overage"]=$sjd_overage;
		$data3["svt_overage"]=$svt_overage;
		$data3["svd_overage"]=$svd_overage;

		$data3["sjt_shortage"]=$sjt_shortage;
		$data3["sjd_shortage"]=$sjd_shortage;
		$data3["svt_shortage"]=$svt_shortage;
		$data3["svd_shortage"]=$svd_shortage;

		$data3["sjt_discrep"]=$sjt_discrep;
		$data3["sjd_discrep"]=$sjd_discrep;
		$data3["svt_discrep"]=$svt_discrep;
		$data3["svd_discrep"]=$svd_discrep;

		$data3["sjt_label"]=$sjt_label;
		$data3["sjd_label"]=$sjd_label;
		$data3["svt_label"]=$svt_label;
		$data3["svd_label"]=$svd_label;

		$data3["sjt_physically_defective"]=$sjt_physically_defective;
		$data3["sjd_physically_defective"]=$sjd_physically_defective;
		$data3["svt_physically_defective"]=$svt_physically_defective;
		$data3["svd_physically_defective"]=$svd_physically_defective;

		$data3["sjt_grand_total"]=$sjt_grand_total;
		$data3["sjd_grand_total"]=$sjd_grand_total;
		$data3["svt_grand_total"]=$svt_grand_total;
		$data3["svd_grand_total"]=$svd_grand_total;
		$data3["svd_grand_total"]=$svd_grand_total;
		

		echo json_encode($data3);
		
	}
	
?>