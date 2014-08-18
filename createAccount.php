<?php
require("db_page.php");
?>

<?php
if(isset($_POST['username'])){
	
	if($_POST['enterPass']==$_POST['repeatPass']){

		$db=retrieveDb();
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
				$sql="insert into login(username,password,firstName,lastName,midInitial,role,position,id,status) values ('".$_POST['username']."','".$_POST['enterPass']."',\"".$_POST['firstName']."\",\"".$_POST['lastName']."\",\"".$_POST['midInitial']."\",'".$_POST['user_role']."',\"".$_POST['position']."\",'".$_POST['id_no']."','active')";
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
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->
<script type="text/javascript" src="js/jquery-min.js"></script>

<script type="text/javascript" src="js/plugins/forms/ui.spinner.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.mousewheel.js"></script>
 
<script type="text/javascript" src="js/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.pie.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/plugins/tables/jquery.sortable.js"></script>
<script type="text/javascript" src="js/plugins/tables/jquery.resizable.js"></script>

<script type="text/javascript" src="js/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.uniform.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.autotab.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.chosen.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.ibutton.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.validationEngine.js"></script>

<script type="text/javascript" src="js/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="js/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="js/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="js/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="js/plugins/wizards/jquery.form.wizard.js"></script>
<script type="text/javascript" src="js/plugins/wizards/jquery.validate.js"></script>
<script type="text/javascript" src="js/plugins/wizards/jquery.form.js"></script>

<script type="text/javascript" src="js/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.fancybox.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.fileTree.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.sourcerer.js"></script>

<script type="text/javascript" src="js/plugins/others/jquery.fullcalendar.js"></script>
<script type="text/javascript" src="js/plugins/others/jquery.elfinder.js"></script>

<script type="text/javascript" src="js/plugins/ui/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="js/files/bootstrap.js"></script>
<script type="text/javascript" src="js/files/functions.js"></script>
<script type="text/javascript" src="js/files/additional_function.js"></script> 
<script language='javascript'>
function checkRole(element){
	if(element.value=="ticket seller"){
		$('#pass_row').hide();
		$('#pass_row2').hide();
		$('#pass_row3').hide();

	}
	else {
		$('#pass_row').show();
		$('#pass_row2').show();
		$('#pass_row3').show();
	
	}


}

</script>
<?php
$db=retrieveDb();
$sql="select * from login where username='".$_SESSION['username']."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
$row=$rs->fetch_assoc();

$session_user=strtoupper($row['lastName']).", ".$row['firstName'];

}
?>
<title>Automated Cash and Ticket Management System</title>
<div class='content'>
<div id="top">
	<div class="wrapper">
    	<a href="#" title="" class="logo"><h2 style='color:white;'>Automated Cash and Ticket Management System (ACTM)</h2></a>
        
        <div class="clear"></div>
    </div>
</div>
<div style='height:50px;'></div>

    <div class="contentTop">
        <span class="pageTitle"><span class="icon-screen"></span>Admin Page</span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='test_select_log_shift.php'>Audit Shifts</a></span>

		
        <ul class="quickStats">
            <li>
                <a href="test_admin_page.php" class="blueImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">Cash Assistant/Admin</strong></div>
            </li>
            <li>
                <a href="admin_page_2.php" class="redImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">Ticket Seller</strong></div>
            </li>
            <li>
                <a href="createAccount.php" class="greenImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">New Account/User</strong></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>


	<?php 
	require("test_reference_line.php");
	?>
		<br>
<div class='content'>
<div class="wrapper" align=center>

<form id="usualValidate" action='createAccount.php' method='post'>
<table align=center class='tDefault table-bordered table-striped' cellpadding=2>
<thead>
<tr colspan=2 >
	<td colspan=2><a href='#'><h4>Create New Account</h4></a></td>
</tr>
</thead>
<tbody>
<tr class='formRow'>
	<td>Role</td>
	<td>
	<select name='user_role' id='user_role' onchange='checkRole(this)'>
		<option value='cash assistant'>Cash Assistant</option>
		<option value='administrator'>Administrator</option>
		<option value='ticket seller'>Ticket Seller</option>

	</select>
	</td>
</tr>
<tr class='formRow'>
	<td>First Name</td>
	<td><input type=text name='firstName' class="required" size=40 style='font-size:14px;'></td>
</tr>
<tr class='formRow'>
	<td>Last Name</td>
	<td><input type=text name='lastName' class="required" size=40 style='font-size:14px;' ></td>
</tr>
<tr class='formRow'>
	<td>Middle Name</td>
	<td><input type=text name='midInitial' class="required" size=40 style='font-size:14px;' ></td>
</tr>
<tr class='formRow'>
	<td>Position</td>
	<td><input type=text name='position' class="required" size=40 style='font-size:14px;' ></td>
</tr>
<tr class='formRow'>
	<td>Employee Number</td>
	<td><input type=text name='username' class="required" size=40 style='font-size:14px;' ></td>
</tr>
<tr class='formRow' id='pass_row'>
	<td>Account No</td>
	<td><input type=text name='id_no' id='id_no' class="required" size=40 style='font-size:14px;' ></td>
</tr>

<tr class='formRow' id='pass_row2'>
	<td>Password</td>
	<td><input type=password name='enterPass' id='enterPass' class="required" size=40 ></td>
</tr>
<tr class='formRow' id='pass_row3'>
	<td>Retype Password</td>
	<td><input type=password name='repeatPass' id='repeatPass' class="required" size=40></td>
</tr >

<?php
$db=retrieveDb();

?>


<tr>
	<td colspan=2 align=center><input type=submit align=center value='Submit' class="btn btn-primary" /></td>
</tr>
<tr>
<td colspan=2 id='exception' align=center> <b><a href='admin_page.php'>Go back to Admin Page</a></b></td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
</div>
</div>
</div>