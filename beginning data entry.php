<?php
session_start();
?>
<?php

$type=$_GET['type'];
$log_id=$_GET['loID'];


?>
<?php
	if(isset($_POST['log_id'])){
		$beginning_type=$_POST['beginning_type'];
		$db=new mysqli("localhost","root","","finance");
		if($beginning_type=="cash"){
			$search="select * from beginning_balance_cash where log_id='".$_POST['log_id']."'";
			$searchRS=$db->query($search);
			$searchNM=$searchRS->num_rows;
			
			if($searchNM>0){
			$update="update beginning_balance_cash set revolving_fund='".$_POST['revolving']."',for_deposit='".$_POST['deposit']."' where log_id='".$_POST['log_id']."'";
			$rs=$db->query($update);	
			
			}
			else {
			$update="insert into beginning_balance_cash(revolving_fund,for_deposit,log_id) values ('".$_POST['revolving']."','".$_POST['deposit']."','".$_POST['log_id']."')";

			$rs=$db->query($update);	
			
			}
		}
		else if($beginning_type=="sj"){
			$search="select * from beginning_balance_sjt where log_id='".$_POST['log_id']."'";
			$searchRS=$db->query($search);
			$searchNM=$searchRS->num_rows;
			
			if($searchNM>0){
			$update="update beginning_balance_sjt set sjt='".$_POST['sjt']."',sjd='".$_POST['sjd']."',sjt_loose='".$_POST['sjt_loose']."',sjd_loose='".$_POST['sjd_loose']."' where log_id='".$_POST['log_id']."'";
			$rs=$db->query($update);	
			}
			else {
			$update="insert into beginning_balance_sjt(sjt,sjd,sjt_loose,sjd_loose,log_id) values ('".$_POST['sjt']."','".$_POST['sjd']."','".$_POST['sjt_loose']."','".$_POST['sjd_loose']."','".$_POST['log_id']."')";
			$rs=$db->query($update);	
			
			}
		}
		else if($beginning_type=="sv"){
			$search="select * from beginning_balance_svt where log_id='".$_POST['log_id']."'";
			$searchRS=$db->query($search);
			$searchNM=$searchRS->num_rows;
			
			if($searchNM>0){
			$update="update beginning_balance_svt set svt='".$_POST['svt']."',svd='".$_POST['svd']."',svt_loose='".$_POST['svt_loose']."',svd_loose='".$_POST['svd_loose']."' where log_id='".$_POST['log_id']."'";
			$rs=$db->query($update);
			}
			else {
			$update="insert into beginning_balance_svt(svt,svd,svt_loose,svd_loose,log_id) values ('".$_POST['svt']."','".$_POST['svd']."','".$_POST['svt_loose']."','".$_POST['svd_loose']."','".$_POST['log_id']."')";
			$rs=$db->query($update);
			
			}
		
		
		}
	
	}

?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<script language='javascript'>
function submitForm(){

	document.forms['beginning_form'].submit();
	window.opener.location.reload();
	//self.close();
}
</script>
<form name='beginning_form' id='beginning_form' action='beginning data entry.php' method='post'>
<table class='controlTable2' width=100%>
<tr>
<th colspan=2 class='header'>
	Beginning Balance Entry
	<input type=hidden name='log_id' id='log_id' value='<?php echo $log_id; ?>'	/>
	<input type=hidden name='beginning_type' id='beginning_type' value='<?php echo $type; ?>' />
</th>
</tr>
<?php
if($type=="cash"){
?>
<tr>
	<td class='subheader'>Revolving Fund</td>
	<td class='grid'><input type=text name='revolving' /></td>

</tr>
<tr>
	<td class='subheader'>Deposit/Net Revenue</td>
	<td class='category'><input type=text name='deposit' /></td>
</tr>

<?php
}
else if($type=="sj"){
?>
<tr>
	<td class='subheader'>SJT</td>
	<td class='grid'><input type=text name='sjt' /></td>

</tr>
<tr>
	<td class='subheader'>SJT Loose</td>
	<td class='category'><input type=text name='sjt_loose' /></td>

</tr>

<tr>
	<td class='subheader'>SJD</td>
	<td class='grid'><input type=text name='sjd' /></td>

</tr>
<tr>
	<td class='subheader'>SJD Loose</td>
	<td class='category'><input type=text name='sjd_loose' /></td>

</tr>

<?php
}
else if($type=="sv"){
?>
<tr>
	<td class='subheader'>SVT</td>
	<td class='grid'><input type=text name='svt' /></td>

</tr>
<tr>
	<td class='subheader'>SVT Loose</td>
	<td class='category'><input type=text name='svt_loose' /></td>

</tr>

<tr>
	<td class='subheader'>SVD</td>
	<td class='grid'><input type=text name='svd' /></td>

</tr>
<tr>
	<td class='subheader'>SVD Loose</td>
	<td class='category'><input type=text name='svd_loose' /></td>

</tr>

<?php


}

?>
<tr>
<th colspan=2><input type=button value='Submit' onclick='submitForm()' /></th>
</tr>

</table>

</form>