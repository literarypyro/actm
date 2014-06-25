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

if((isset($_POST['amount']))&&($_POST['amount']>0)){
	
	
	
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];
	$type=$_POST['deposit_type'];
	if($amorpm=='pm'){
		$hour+=(12*1);
		if($hour>=24){
			$hour=0;
		}
	}
	else {
		$hour=$hour;
		
	}
	$date=$year."-".$month."-".$day." ".$hour.":".$minute;
	$date_id=$year.$month.$day;
	$reference_id=$_POST['reference_id'];
	$type=$_POST['deposit_type'];
	$db=new mysqli("localhost","root","","finance");
	
	$total=$_POST['amount'];

	if($_POST['form_action']=="insert"){

		
		$sql="insert into transaction(date,log_id,log_type,transaction_type,reference_id) values ('".$date."','".$log_id."','cash','deposit','".$reference_id."')";
		$rs=$db->query($sql);

		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;
		$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs=$db->query($sql);


		$sql="insert into pnb_deposit(log_id,time,cash_assistant,type,";
		$sql.="transaction_id,amount,reference_id) values ";
		$sql.="('".$log_id."','".$date."','".$_POST['cash_assistant']."','".$type."',";
		$sql.="'".$transaction_id."','".$total."','".$reference_id."')";

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
				$sqlInsert.=" values ('pnb_".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
				$sqlInsertRS=$db->query($sqlInsert);
			}
		}
	
	}	
	else if($_POST['form_action']=="edit"){

		if(isset($_POST['trans_edit'])){			
			$sql="select * from transaction where id='".$_POST['trans_edit']."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();

			
			$update="update transaction set reference_id='".$reference_id."' where id='".$row['id']."'";
			$updateRS=$db->query($update);
		
			$insert_id=$row['id'];
			
			$transaction_id=$row['transaction_id'];


			$sql="update pnb_deposit set cash_assistant='".$_POST['cash_assistant']."',time='".$date."',amount='".$total."',log_id='".$log_id."',type='".$type."',reference_id='".$reference_id."' where transaction_id='".$transaction_id."'";
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
	}	
	
}

?>
<script language='javascript'>
function submitForm(){

	document.forms['deposit_form'].submit();
	window.opener.location.reload();
}

function amountCalculate(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	document.getElementById(textAmount).value=amount;	

	calculateTotal();
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	
}

function calculateTotal(){
	var total=0;
	for(i=1;i<13;i++){
		total+=(document.getElementById('amount'+i).value)*1;

	}
	document.getElementById('amount').value=Math.round(total*100)/100;

	
}
</script>
<?php
if(isset($_GET['tID'])){
	$db=new mysqli("localhost","root","","finance");
	$form_action="edit";
	$sql="select * from transaction where id='".$_GET['tID']."'";	
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$transactType=$row['transaction_type'];
	$transactionID=$row['transaction_id'];
	
	$sql2="select * from pnb_deposit where transaction_id='".$row['transaction_id']."'";

	$rs2=$db->query($sql2);
	$row2=$rs2->fetch_assoc();

	$reference_id=$row2['reference_id'];
	$deposit_id=$row2['id'];
	$totalpost=$row2['amount'];
	$cash_assist=$row2['cash_assistant'];	
	$transactDate=$row2['time'];
	$depositType=$row2['type'];



	
	$denomSQL="select * from denomination where cash_transfer_id='pnb_".$deposit_id."'";

	$denomRS=$db->query($denomSQL);
	$denomNM=$denomRS->num_rows;
	for($i=0;$i<$denomNM;$i++){
		$denomRow=$denomRS->fetch_assoc();
		$currency[$denomRow['denomination']]['value']=$denomRow['quantity'];
		$currency[$denomRow['denomination']]['id']=$denomRow['denomination'];
	
	}
	$_SESSION['transact']=$_GET['tID'];	
	
}
else {
	$form_action="insert";
}
?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<form action='pnb_deposit.php<?php if(isset($_GET['tID'])){ echo "?tID=".$_GET['tID']; } ?>' method='post' name='deposit_form' id='deposit_form'>
<table class='controlTable2' >
<tr class='header'>
<td>Reference Id (Deposit No.)</td>
<td colspan=2>
<input type=text name='reference_id' id='reference_id' value='<?php echo $reference_id; ?>' />
<input type=hidden name='form_action' id='form_action' value='<?php echo $form_action; ?>' /><input type=hidden name='trans_edit' id='trans_edit' value='<?php echo $_GET['tID']; ?>'>


</td>
</tr>
<tr class='grid'>
<td>Date and Time</td>
<td colspan=2>
<?php
if(isset($_GET['tID'])){
$mm=date("m",strtotime($transactDate));
$yy=date("Y",strtotime($transactDate));
$dd=date("d",strtotime($transactDate));

$hh=date("h",strtotime($transactDate));

$min=date("i",strtotime($transactDate));
$aa=date("a",strtotime($transactDate));

}
?>
<select name='month'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");

for($i=1;$i<13;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$mm){
		echo "selected";
	}
	?>
	>
	<?php
	echo date("F",strtotime(date("Y")."-".$i."-01"));
	?>
	</option>
<?php
}
?>
</select>
<select name='day'>
<?php
for($i=1;$i<=31;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$dd){
		echo "selected";
	}
	?>		
	>
	<?php
	
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='year'>
<?php
$dateRecent=date("Y")*1+16;
for($i=1999;$i<=$dateRecent;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$yy){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='hour'>
<?php
for($i=1;$i<=12;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$hh*1){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='minute'>
<?php
for($i=0;$i<=59;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$min*1){
		echo "selected";
	}
	?>		
	>
	<?php
	if($i<10){
	echo "0".$i;
	}
	else {
	echo $i;
	}
	?>	
	</option>
<?php
}
?>
</select>
<select name='amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr class='category'>
<td>Type</td>
<td colspan=2>
<select name='deposit_type'>
<option <?php if($depositType=="previous"){ echo "selected"; } ?> value='previous'>Previous</option>
<option <?php if($depositType=="current"){ echo "selected"; } ?> value='current'>Current</option>
</select>
</td>

</tr>
<tr class='grid'>
<td>Cash Assistant</td><td colspan=2><select name='cash_assistant'>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from login order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();
?>
<option value='<?php echo $row['username']; ?>' 
<?php 
if($cash_assist==""){
	if($row['username']==$_SESSION['username']){
		echo "selected";
	}
}
else {
	if($row['username']==$cash_assist){
	
		echo "selected";
	}

}

?> 
>
<?php
echo strtoupper($row['lastName']).", ".$row['firstName'];
?>
</option>
<?php
}
?>
</select>
</td>
</tr>

<tr class='subheader'>

<th>Denomination</th>
<th>Quantity</th>
<th>Amount</th>
</tr>
<tr class='grid'>
	<td align=center>1000</td><td><input type='text' style='text-align:right'  id='1000denom' name='1000denom' value='<?php echo $currency["1000"]['value']; ?>' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')" /></td><td><input style='text-align:right' type='text' name='amount1' id='amount1' value='<?php echo $currency['1000']['id']*$currency["1000"]['value']; ?>'  /></td>
</tr>
<tr class='category'>
	<td align=center>500</td><td><input type='text' style='text-align:right'  id='500denom' name='500denom' value='<?php echo $currency["500"]['value']; ?>' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')" /></td><td><input style='text-align:right'  type='text' name='amount2' id='amount2'  value='<?php echo $currency['500']['id']*$currency["500"]['value']; ?>'  /></td>
</tr>
<tr class='grid'>
	<td align=center>200</td><td><input type='text' style='text-align:right'  id='200denom' name='200denom' value='<?php echo $currency["200"]['value']; ?>' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')" /></td><td><input style='text-align:right'  type='text' name='amount3' id='amount3'  value='<?php echo $currency['200']['id']*$currency["200"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>100</td><td><input type='text' style='text-align:right'  id='100denom' name='100denom' value='<?php echo $currency["100"]['value']; ?>' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')" /></td><td><input style='text-align:right'  type='text' name='amount4' id='amount4'  value='<?php echo $currency['100']['id']*$currency["100"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>50</td><td><input type='text' style='text-align:right'  id='50denom' name='50denom' value='<?php echo $currency["50"]['value']; ?>' onkeyup="amountCalculate(this.value,'50','amount5',event,'20denom')" /></td><td><input style='text-align:right'  type='text' name='amount5' id='amount5'  value='<?php echo $currency['50']['id']*$currency["50"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>20</td><td><input type='text' style='text-align:right'  id='20denom' name='20denom' value='<?php echo $currency["20"]['value']; ?>' onkeyup="amountCalculate(this.value,'20','amount6',event,'10denom')" /></td><td><input style='text-align:right'  type='text' name='amount6' id='amount6'  value='<?php echo $currency['20']['id']*$currency["20"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>10</td><td><input type='text' style='text-align:right'  id='10denom' name='10denom' value='<?php echo $currency["10"]['value']; ?>' onkeyup="amountCalculate(this.value,'10','amount7',event,'5denom')" /></td><td><input style='text-align:right'  type='text' name='amount7' id='amount7'  value='<?php echo $currency['10']['id']*$currency["10"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>5</td><td><input type='text' style='text-align:right'  id='5denom' name='5denom' value='<?php echo $currency["5"]['value']; ?>' onkeyup="amountCalculate(this.value,'5','amount8',event,'1denom')" /></td><td><input style='text-align:right'  type='text' name='amount8' id='amount8'  value='<?php echo $currency['5']['id']*$currency["5"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>1</td><td><input type='text' style='text-align:right'  id='1denom' name='1denom' value='<?php echo $currency["1"]['value']; ?>' onkeyup="amountCalculate(this.value,'1','amount9',event,'25cdenom')" /></td><td><input style='text-align:right'  type='text' name='amount9' id='amount9'  value='<?php echo $currency['1']['id']*$currency["1"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>.25</td><td><input type='text' style='text-align:right'  id='25cdenom' name='25cdenom' value='<?php echo $currency[".25"]['value']; ?>' onkeyup="amountCalculate(this.value,'.25','amount10',event,'10cdenom')" /></td><td><input style='text-align:right'  type='text' name='amount10' id='amount10'  value='<?php echo $currency['.25']['id']*$currency[".25"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>.10</td><td><input type='text' style='text-align:right'  id='10cdenom' name='10cdenom' value='<?php echo $currency[".10"]['value']; ?>' onkeyup="amountCalculate(this.value,'.10','amount11',event,'5cdenom')" /></td><td><input style='text-align:right'  type='text' name='amount11' id='amount11'  value='<?php echo $currency['.10']['id']*$currency[".10"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>.05</td><td><input type='text' style='text-align:right'  id='5cdenom' name='5cdenom' value='<?php echo $currency[".05"]['value']; ?>' onkeyup="amountCalculate(this.value,'.05','amount12',event,'revolving_remittance')" /></td><td><input style='text-align:right'  type='text' name='amount12' id='amount12'  value='<?php echo $currency['.05']['id']*$currency[".05"]['value']; ?>' /></td>
</tr>
<tr class='header'>
	<td>&nbsp;</td>
	<th>PNB Deposit</th>
	<td><input style='text-align:right'  name='amount' id='amount' type=text value='<?php echo $totalpost; ?>' /></td>
</tr>

<tr>
<td colspan=3 align=center><input type=button value='Submit' onclick='submitForm()' /></td>
</tr>

</table>
</form>