<?php
session_start();
?>
<?php
$transaction_id=$_GET['tID'];
$ticket_seller=$_GET['tsID'];
?>
<?php
$log_id=$_SESSION['log_id'];


?>

<?php
if(isset($_POST['transaction_id'])){
	$type=$_POST['type'];
	$classification=$_POST['classification'];
	$transaction_id=$_POST['transaction_id'];
	$reported=$_POST['reported'];
	$amount=$_POST['amount'];
	$reference_id=$_POST['reference_id'];
	$ticket_seller=$_POST['ticket_seller'];
	
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from discrepancy where transaction_id='".$transaction_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$update="update discrepancy set log_id='".$log_id."',ticket_seller='".$ticket_seller."',reference_id='".$reference_id."',classification='".$classification."',reported='".$reported."',amount='".$amount."',type='".$type."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
		
	
	}
	else {
		$update="insert into discrepancy(reference_id,classification,reported,amount,type,transaction_id,log_id,ticket_seller) values ('".$reference_id."','".$classification."','".$reported."','".$amount."','".$type."','".$transaction_id."','".$log_id."','".$ticket_seller."')";
		$updateRS=$db->query($update);
	
	}
echo "Data submitted.<br>";
}
?>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from transaction where transaction_id='".$transaction_id."'";
$rs=$db->query($sql);

$row=$rs->fetch_assoc();
if($row['log_type']=='cash'){
	$classification="cash";

}
else if($row['log_type']=="ticket"){
	$classification="ticket";

}
/*
$sql="select * from cash_remittance where transaction_id='".$transaction_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$control_remittance=$row['control_remittance'];
	$cash_remittance=$row['cash_remittance'];
	
	if($cash_remittance==$control_remittance){
	}
	else {
		if($cash_remittance>$control_remittance){
			$amount=$cash_remittance*1-$control_remittance*1;
			$overageType="overage";
		}
		else if($control_remittance>$cash_remittance){
			$amount=$control_remittance*1-$cash_remittance*1;
			$overageType="shortage";
		}
	}		
	
}
*/
?>

<?php
$sql="select * from discrepancy where transaction_id='".$transaction_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$overageType=$row['type'];
	$classification=$row['classification'];
	$amount=$row['amount'];
	$reference_id=$row['reference_id'];
	$reported=$row['reported'];
}

?>
<script language='javascript'>
function submitForm(){

	document.forms['discrepancy_form'].submit();
	window.opener.updateLogbook();
	//self.close();
}


</script>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">


<b>Transaction #: <?php echo $transaction_id; ?></b>
<br>
<form id='discrepancy_form' name='discrepancy_form' action="discrepancy.php" method="post">
<table class='controlTable2'>
<tr class='header'>
<th colspan=2>Discrepancy Report<input type=hidden name='transaction_id' id='transaction_id' value='<?php echo $transaction_id; ?>' /><input type=hidden name='ticket_seller' id='ticket_seller' value='<?php echo $ticket_seller; ?>' /></th>
</tr>
<tr class='grid'>
<th>Reference Id</th>
<td><input type=text name='reference_id' value='<?php echo $reference_id; ?>' /></td>

</tr>

<tr class='category'>
<th>Classification</th>
<td>
<select name='classification'>
	<option value='cash'  <?php if($classification=="cash"){ echo "selected"; } ?>>Cash</option>

</select>
</td>
</tr>
<tr class='grid'>
<th>Type of Discrepancy</th>
<td>
<!-- paid shortage only -->
<select name='type'>
	<option value='shortage' <?php if($overageType=="shortage"){ echo "selected"; } ?> >Shortage</option>
	<option value='overage' <?php if($overageType=="overage"){ echo "selected"; } ?>>Overage</option>
</select>
</td>
<tr class='category'>
<th>Remitted By:</th>
<td>
<select name='reported'>
	<option value='ticket seller' <?php if($reported=="ticket seller"){ echo "selected"; } ?>>Ticket Seller</option>
	<option value='cash assistant' <?php if($reported=="cash assistant"){ echo "selected"; } ?> >Cash Assistant</option>

</select>
</td>

</tr>

<tr class='grid'>
<th>Amount</th>
<td><input type=text name='amount' id='amount' value='<?php echo $amount; ?>' /></td>
</tr>

<tr>
<th colspan=2><input type=button onclick='submitForm()' value='Submit' /></th>
</tr>
</form>
</table>
<br>
<?php
if(isset($_POST['transaction_id'])){
?>
<div align=center><input type=button value='Generate Printout' onclick='window.open("generate_discrepancy.php?type=cash&transID=<?php echo $transaction_id; ?>","_blank")' /></div>
<?php
}
?>



