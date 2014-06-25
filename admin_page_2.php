<?php
session_start();
?>
<?php
if(isset($_POST['modifyUser'])){
	$db=new mysqli("localhost","root","","finance");
	if($_POST['action']=="edit"){
		$update="update ticket_seller set ".$_POST['modifyField']."='".$_POST['modifyValue']."' where id='".$_POST['modifyUser']."'";
		$rs=$db->query($update);
	}
	else if($_POST['action']=="delete"){
		$update="update ticket_seller set status='inactive' where id='".$_POST['modifyUser']."'";
		$rs=$db->query($update);
	
	}
}
?>
<?php
if(isset($_SESSION['username'])){
?>	
	<a href="createAccount.php" >Enter New User</a> | <a href='admin_page.php'> Edit User Information </a> | Edit Ticket Seller | <a href='select_log_shift.php'>View/Audit Shifts</a>
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

<form action='admin_page_2.php' id='admin_form' name='admin_form' method='post'>
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
$userSQL="select * from ticket_seller where status='active' order by last_name ";
$userRS=$db->query($userSQL);
$userNM=$userRS->num_rows;
for($i=0;$i<$userNM;$i++){
	$userRow=$userRS->fetch_assoc();
	?>
	<option value='<?php echo $userRow['id']; ?>'><?php echo strtoupper($userRow['last_name']).", ".$userRow['first_name']; ?></option>	
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
<option value='first_name'>First Name</option>
<option value='last_name'>Last Name</option>
<option value='middle_name'>Middle Name</option>
<option value='employee_number'>Employee Number</option>
<option value='position'>Position</option>

</select>

</td>
</tr>
<tr>
<td>Change To:</td>
<td><input type=text name='modifyValue' id='modifyValue' /></td>
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
<td>Employee Number</td>
<td>Position</td>

</tr>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from ticket_seller where status='active' order by last_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<tr>
		<td><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></td>
		<td><?php echo $row['middle_name']; ?></td>
		<td><?php echo $row['employee_number']; ?></td>
		<td><?php echo $row['position']; ?></td>

	</tr>
<?php
}
?>
</table>