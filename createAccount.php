<?php
if(isset($_POST['username'])){
	
	if($_POST['password']==$_POST['repassword']){

		$db=new mysqli("localhost","root","","finance");
		$check="select * from login where username='".$_POST['username']."'";
		$checkRS=$db->query($check);
		$checkNM=$checkRS->num_rows;
		if($checkNM>0){
			echo "<div align=center>Username already exists.</div>";
		}
		else {
			if($_POST['user_role']=='ticket seller'){
				$sql="insert into ticket_seller(id,first_name,last_name,middle_name,position,employee_number,status) values ('".$_POST['id_no']."',\"".$_POST['firstName']."\",\"".$_POST['lastName']."\",\"".$_POST['midInitial']."\",\"".$_POST['position']."\",'".$_POST['username']."','active')";
				$rs=$db->query($sql);
				$login_id=$db->insert_id;
				
			}
			else {
				$sql="insert into login(username,password,firstName,lastName,midInitial,role,position,id,status) values ('".$_POST['username']."','".$_POST['password']."',\"".$_POST['firstName']."\",\"".$_POST['lastName']."\",\"".$_POST['midInitial']."\",'".$_POST['user_role']."',\"".$_POST['position']."\",'".$_POST['id_no']."','active')";
				$rs=$db->query($sql);
				$login_id=$db->insert_id;
			}
	
			echo "<div align=center>Data has been added.</div>";
		}
	}
	else {
	
	
	}
}
?>
<form action='createAccount.php' method='post'>
<table id=cssTable align=center style='border:1px solid gray' cellpadding=2>
<tr colspan=2 >
	<th colspan=2><h2>Create New Account</h2></th>
</tr>
<tr>
	<td>First Name</td>
	<td><input type=text name='firstName' size=40 ></td>
</tr>
<tr>
	<td>Last Name</td>
	<td><input type=text name='lastName' size=40 ></td>
</tr>
<tr>
	<td>Middle Name</td>
	<td><input type=text name='midInitial' size=40 ></td>
</tr>
<tr>
	<td>Position</td>
	<td><input type=text name='position' size=40 ></td>
</tr>
<tr>
	<td>Employee Number</td>
	<td><input type=text name='username' size=40 ></td>
</tr>
<tr>
	<td>ID No</td>
	<td><input type=text name='id_no' size=40 ></td>
</tr>

<tr>
	<td>Password</td>
	<td><input type=password name='password' size=40 ></td>
</tr>
<tr>
	<td>Retype Password</td>
	<td><input type=password name='repassword' size=40></td>
</tr>
<tr>
	<td>Role</td>
	<td>
	<select name='user_role' id='user_role'>
		<option value='cash assistant'>Cash Assistant</option>
		<option value='administrator'>Administrator</option>
		<option value='ticket seller'>Ticket Seller</option>

	</select>
	</td>
</tr>
<?php
$db=new mysqli("localhost","root","","finance");

?>


<tr>
	<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
<tr>
<td colspan=2 id='exception' align=center> <b><a href='admin_page.php'>Go back to Admin Page</a></b></td>
</tr>
</table>
</form>