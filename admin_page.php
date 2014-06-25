<?php
session_start();
?>
<?php
if(isset($_POST['modifyUser'])){
	$db=new mysqli("localhost","root","","finance");
	if($_POST['action']=="edit"){
		if($_POST['modifyField']=="role"){
			$update="update login set role='".$_POST['modifyRole']."' where username='".$_POST['modifyUser']."'";
			$rs=$db->query($update);
		}
		else {
			$update="update login set ".$_POST['modifyField']."='".$_POST['modifyValue']."' where username='".$_POST['modifyUser']."'";
			$rs=$db->query($update);
		}
	}
	else if($_POST['action']=="delete"){
		$update="update login set status='inactive' where username='".$_POST['modifyUser']."'";
		$rs=$db->query($update);
	
	}
}
?>
<?php
if(isset($_SESSION['username'])){
?>	
	<a href="createAccount.php" >Enter New User</a> | Edit User Information | <a href='admin_page_2.php'>Edit Ticket Seller</a> | <a href='select_log_shift.php'>View/Audit Shifts</a>
<?php
}
else {
	header("Location: index.php");
}
?>
<script language='javascript'>
function enableField(fieldType){
	if(fieldType=="role"){
		document.getElementById('modifyRole').disabled=false;
		document.getElementById('modifyValue').disabled=true;		
	}
	else {
		document.getElementById('modifyRole').disabled=true;
		document.getElementById('modifyValue').disabled=false;
	}
} 
function submitForm(){
	var action=document.getElementById('action').value;
	if(action=='delete'){
		var check=confirm("Delete the Account?");
		if(check){
			document.forms["admin_form"].submit();
		
		}
	
	}
	else {
		document.forms["admin_form"].submit();
	
	
	}

}
</script>
<br>
<br>

<form action='admin_page.php' id='admin_form' name='admin_form' method='post'>
<table style="border:1px solid gray">
<tr>
<th colspan=2>Edit User Data</th>
</tr>
<tr>
<td>Edit User</td>
<td>
<select name='modifyUser'>
<?php 
$db=new mysqli("localhost","root","","finance");
$userSQL="select * from login where status='active' order by lastName ";
$userRS=$db->query($userSQL);
$userNM=$userRS->num_rows;
for($i=0;$i<$userNM;$i++){
	$userRow=$userRS->fetch_assoc();
	?>
	<option value='<?php echo $userRow['username']; ?>'><?php echo strtoupper($userRow['lastName']).", ".$userRow['firstName']; ?></option>	
	<?php

}
?>
</select>
</td>
</tr>
<tr>
<td>Enter Action</td>
<td>
	<select name='action' id='action'>
		<option value='edit'>Edit</option>
		<option value='delete'>Delete</option>
	</select>

</tr>
</tr>
<tr>
<td>Modify this Data:</td>
<td>
<select name='modifyField' id='modifyField' onchange='enableField(this.value)'>
<option value='firstName'>First Name</option>
<option value='lastName'>Last Name</option>
<option value='midInitial'>Middle Name</option>
<option value='username'>Employee Number(Username)</option>
<option value='password'>Password</option>
<option value='role'>Role</option>

</select>

</td>
</tr>
<tr>
<td>Change To:</td>
<td><input type=text name='modifyValue' id='modifyValue' /></td>
</tr>
<tr>
<td>Change To:</td>
<td>
<select name='modifyRole' id='modifyRole'  disabled=true>
<?php 
//$db=new mysqli("localhost","root","","finance");
//$sql="select * from station";
//$rs=$db->query($sql);
//$nm=$rs->num_rows;
//for($i=0;$i<$nm;$i++){
//	$row=$rs->fetch_assoc();
?>

	<option value='cash assistant'>Cash Assistant</option>
	<option value='administrator'>Administrator</option>

<?php
//}
?>
</select>
</td>
</tr>
<tr>
<td colspan=2 align=center><input type=button value='Submit' onclick='submitForm()' /></td>
</tr>
</table>
</form>
<br><br>
<div align=right>
<a href='logout.php'>Log Out</a>
</div>
<table width=100% border=1>
<tr>
<td>Name</td>
<td>Middle Name</td>
<td>Username (Employee Number)</td>
<td>Password</td>
<td>Role</td>
<!--<td>Station(Cash Assistant)</td>
<td>Extension(Cash Assistant)</td>
<td>Shift</td>
-->

</tr>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from login where status='active' order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<tr>
		<td><?php echo strtoupper($row['lastName']).", ".$row['firstName']; ?></td>
		<td><?php echo $row['midInitial']; ?></td>
		<td><?php echo $row['username']; ?></td>
		<td><?php echo $row['password']; ?></td>
		<td><?php echo strtoupper($row['role']); ?></td>

	</tr>
<?php
}
?>
</table>