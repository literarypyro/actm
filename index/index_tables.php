<?php
$db=new mysqli("localhost","root","","finance");
?>
<?php

$transact=array("cash_transfer","ticket_order","discrepancy","discrepancy_ticket");

$cash_t=array("denomination");

$control=array("allocation","remittance","control_sales_amount","control_sold","fare_adjustment","fare_adjustment_tickets",
"discount","refund","unreg_sale","control_unsold","control_cash","control_tracking");

$trans_id= array("transaction");

$log=array("control_slip","beginning_balance_cash","beginning_balance_sjt","beginning_balance_svt");


for($k=0;$k<count($control);$k++){
	$sql="alter table ".$control[$k]." add index(control_id)";
	$rs=$db->query($sql);
}

for($k=0;$k<count($cash_t);$k++){
	$sql="alter table ".$cash_t[$k]." add index(cash_transfer_id)";
	$rs=$db->query($sql);
	
}

for($k=0;$k<count($trans_id);$k++){
	$sql="alter table ".$trans_id[$k]." add index(transaction_id)";
	$rs=$db->query($sql);
	
}

for($k=0;$k<count($transact);$k++){
	$sql="alter table ".$transact[$k]." add index(transaction_id)";
	$rs=$db->query($sql);
	
}

for($k=0;$k<count($transact);$k++){
	$sql="alter table ".$log[$k]." add index(log_id)";
	$rs=$db->query($sql);
	
}


$sql="alter table cash_transfer add index(ticket_seller)";
$rs=$db->query($sql);

$sql="alter table control_slip add index(unit)";
$rs=$db->query($sql);


$sql="alter table remittance add index(ticket_seller)";
$rs=$db->query($sql);

$sql="alter table remittance add index(log_id)";
$rs=$db->query($sql);

$sql="alter table discrepancy add index(ticket_seller)";
$rs=$db->query($sql);



$sql="alter table login add index(username)";
$rs=$db->query($sql);


$sql="alter table logbook add index(date)";
$rs=$db->query($sql);



echo "Indices added.";






?>









