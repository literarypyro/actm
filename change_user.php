<?php
session_start();
?>
<?php
if(isset($_POST['username'])){
	$log_id=$_SESSION['log_id'];
	$db=new mysqli("localhost","root","","finance");
	$sql="update logbook set cash_assistant='".$_POST['username']."' where id='".$log_id."'";
	$rs=$db->query($sql);
	
	$logDate=date("Y-m-d");
	$logTime=date("Y-m-d H:i:s");					
	$updateSQL="insert into log_history(username,log_id,date,login) values ";
	$updateSQL.="('".$_POST['username']."','".$log_id."','".$logDate."','".$logTime."')";

	$updateRS=$db->query($updateSQL);

	$logTime=date("Y-m-d H:i:s");
	$db=new mysqli("localhost","root","","finance");
	$updateSQL="update log_history set logout='".$logTime."' where log_id='".$log_id."'";

	$updateRS=$db->query($updateSQL);	

	echo "<script language='javascript'>";
	
	echo "window.opener.location='logout.php';";
	echo "self.close();";
	
	echo "</script>";
	
}

?>


<script language="javascript">
function changeUser(){
	var check=confirm("Change the Cash Assistant for the current shift?");
	if(check){
		document.forms["change_form"].submit();
	}


}
</script>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from logbook inner join login on logbook.cash_assistant=login.username where logbook.id='".$_SESSION['log_id']."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$userName=strtoupper($row['lastName']).", ".strtoupper($row['firstName']);

?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<body>
<form id='change_form' name='change_form' action='change_user.php' method='post'>

<table class='controlTable3'>
<tr class='header'><th colspan=2>Change Cash Assistant</th></tr>
<tr class='subheader'><th>Previous Cash Assistant:</th><td><?php echo $userName; ?></td></tr>
<tr ><th class='category'>New Cash Assistant:</th><td>	
<select name='username'>
	<?php
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from login where status='active' order by lastName";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
	<option value="<?php echo $row['username']; ?>">
	<?php echo strtoupper($row['lastName']).", ".$row['firstName']; ?>
	</option>
	<?php
	}
	?>
	</select>
</td></tr>
<tr><th class='category'>Enter Password of Previous CA:</th><td class='grid'><input type='password' name='oldpassword' /></td></tr>
<tr><th class='category'>Enter Password of New CA:</th><td class='grid'><input type='password' name='newpassword' /></td></tr>
<tr><th colspan=2><input type=button onclick='changeUser()' value='Submit' /></th></tr>
</table>
</form>
</body>