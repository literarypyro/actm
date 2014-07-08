<?php
session_start();
?>
<?php
$transaction_id=$_GET['tID'];
$ticket_seller=$_GET['tsID'];
?>
<?php
$log_id=$_SESSION['log_id'];

//echo $log_id;
//$control_id=str_replace("control","",$transaction_id);


?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<?php
if(isset($_POST['transaction_id'])){
	$type=$_POST['type'];
	$classification=$_POST['classification'];
	$transaction_id=$_POST['transaction_id'];
	$reported=$_POST['reported'];
	$amount=$_POST['amount'];
	$reference_id=$_POST['reference_id'];
	$ticket_seller=$_POST['ticket_seller'];
	
	$ticket[0]="sjt";
	$ticket[1]="sjd";
	$ticket[2]="svt";
	$ticket[3]="svd";
	
	$db=new mysqli("localhost","root","","finance");
	
	for($i=0;$i<count($ticket);$i++){
		if(($_POST[$ticket[$i].'_amount']=='')||($_POST[$ticket[$i].'_amount']==0)){
			$sql="delete from discrepancy_ticket where transaction_id='".$transaction_id."' and ticket_type='".$ticket[$i]."'";
			$rs=$db->query($sql);

		}
		else {
			$sql="select * from discrepancy_ticket where transaction_id='".$transaction_id."' and ticket_type='".$ticket[$i]."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$update="update discrepancy_ticket set amount='".$_POST[$ticket[$i]."_amount"]."',price='".$_POST[$ticket[$i]."_value"]."',type='".$_POST[$ticket[$i]."_classification"]."' where id='".$row['id']."'";
				$updateRS=$db->query($update);
			
			}
			else {
				$update="insert into discrepancy_ticket(reference_id,classification,reported,amount,type,transaction_id,log_id,ticket_seller,ticket_type,price)";
				$update.=" values ('".$reference_id."','".$classification."','".$reported."','".$_POST[$ticket[$i]."_amount"]."','".$_POST[$ticket[$i]."_classification"]."','".$transaction_id."','".$log_id."','".$ticket_seller."','".$ticket[$i]."','".$_POST[$ticket[$i]."_value"]."')";
				$updateRS=$db->query($update);	

			
			}			
		}
	}
	/*
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
	*/
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
$sql="select * from discrepancy_ticket where transaction_id='".$transaction_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		if($i==0){
			$classification=$row['classification'];
			$reference_id=$row['reference_id'];
		
		}
		
		if($row['ticket_type']=="sjt"){
			$sjt_classification=$row['type'];
			$sjt_amount=$row['amount'];
			$sjt_price=$row['price'];
			
			
		}
		else if($row['ticket_type']=="sjd"){
			$sjd_classification=$row['type'];
			$sjd_amount=$row['amount'];
			$sjd_price=$row['price'];
		
		
		}
		else if($row['ticket_type']=="svt"){
			$svt_classification=$row['type'];
			$svt_amount=$row['amount'];
		
			$svt_price=$row['price'];
		
		}
		else if($row['ticket_type']=="svd"){
			$svd_classification=$row['type'];
			$svd_amount=$row['amount'];
			$svd_price=$row['price'];

		}
	}
//	$row=$rs->fetch_assoc();
//	$overageType=$row['type'];
//	$amount=$row['amount'];
//	$reported=$row['reported'];
}

?>
<script language='javascript'>
function submitForm(){

	document.forms['discrepancy_form'].submit();
	window.opener.location.reload();

	//	window.opener.updateLogbook();
//	self.close();
}


</script>


<b>Transaction #: <?php echo $transaction_id; ?></b>
<br>
<form id='discrepancy_form' name='discrepancy_form' action="discrepancy_ticket.php" method="post">
<table class='controlTable2'>
<tr class='header'>
<th colspan=4>Discrepancy Report<input type=hidden name='transaction_id' id='transaction_id' value='<?php echo $transaction_id; ?>' /><input type=hidden name='ticket_seller' id='ticket_seller' value='<?php echo $ticket_seller; ?>' /></th>
</tr>
<tr>
<th class='subheader'>Reference Id</th>
<td class='grid' colspan=3><input type=text name='reference_id' value='<?php echo $reference_id; ?>' /></td>

</tr>

<tr>
<th class='subheader'>Classification</th>
<td class='category' colspan=3>
<select name='classification'>
	<option value='ticket' <?php if($classification=="ticket"){ echo "selected"; } ?>>Ticket</option>
</select>
</td>
</tr>
<tr>
<th class='subheader' >Reported By:</th>
<td class='grid' colspan=3>
<select name='reported'>
	<option value='ticket seller' <?php if($reported=="ticket seller"){ echo "selected"; } ?>>Ticket Seller</option>
	<option value='cash assistant' <?php if($reported=="cash assistant"){ echo "selected"; } ?> >Cash Assistant</option>

</select>
</td>

</tr>
<tr class='category'>
<th>Type</th>
<th>Classification</th>
<th>Quantity</th>
<th>Amount</th>
</tr>

<tr>
	<th class='subheader'>SJT</th>
	<th class='category'>
		<select name='sjt_classification'>
			<option value='shortage' <?php if($sjt_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
			<option value='overage' <?php if($sjt_classification=="overage"){ echo "selected"; } ?>>Overage</option>
		</select>
	</th>
	<td><input type=text name='sjt_amount' value='<?php echo $sjt_amount; ?>' /></td>
	<td><input type=text name='sjt_value' value='<?php echo $sjt_price; ?>' /></td>
	
	
	
	
</tr>
<tr>
	<th class='subheader'>SJD</th>
	<th class='grid'>
		<select name='sjd_classification'>
			<option value='shortage' <?php if($sjd_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
			<option value='overage' <?php if($sjd_classification=="overage"){ echo "selected"; } ?>>Overage</option>
		</select>
	</th>
	<td><input type=text name='sjd_amount' value='<?php echo $sjd_amount; ?>'/></td>
	<td><input type=text name='sjd_value' value='<?php echo $sjd_price; ?>' /></td>

</tr>
<tr>
	<th class='subheader'>SVT</th>
	<th class='category'>
		<select name='svt_classification'>
			<option value='shortage' <?php if($svt_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
			<option value='overage' <?php if($svt_classification=="overage"){ echo "selected"; } ?>>Overage</option>
		</select>
	</th>
	<td><input type=text  name='svt_amount' value='<?php echo $svt_amount; ?>' /></td>
	<td><input type=text name='svt_value' value='<?php echo $svt_price; ?>' /></td>
	
</tr>
<tr>
	<th class='subheader'>SVD</th>
	<th class='grid'>
		<select name='svd_classification'>
			<option value='shortage' <?php if($svd_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
			<option value='overage' <?php if($svd_classification=="overage"){ echo "selected"; } ?>>Overage</option>
		</select>
	</th>
	<td><input type=text name='svd_amount' value='<?php echo $svd_amount; ?>' /></td>
	<td><input type=text name='svd_value' value='<?php echo $svd_price; ?>' /></td>
	
</tr>

<tr>
<th colspan=4><input type=button onclick='submitForm()' value='Submit' /></th>
</tr>
</form>
</table>
<br>
<?php
if(isset($_POST['transaction_id'])){
?>
<div align=center><input type=button value='Generate Printout' onclick='window.open("generate_discrepancy.php?type=ticket&transID=<?php echo $transaction_id; ?>","_blank")' /></div>
<?php
}
?>



