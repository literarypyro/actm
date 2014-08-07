<?php
session_start();
?>
<?php
require("db_page.php");
?>

<?php
if(isset($_POST['modifyUser'])){
	$db=retrieveDb();
	if($_POST['action']=="edit"){
		$update="update ticket_seller set position='".$_POST['position']."',first_name='".$_POST['first_name']."',last_name='".$_POST['last_name']."',middle_name='".$_POST['middle_name']."',employee_number='".$_POST['employee_no']."' where id='".$_POST['modifyUser']."'";
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
}
else {
	header("Location: index.php");
}
?>

<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->

<link href="css/styles.css" rel="stylesheet" type="text/css" />
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

<script type="text/javascript" src="js/charts/chart_side.js"></script>
<script type="text/javascript" src="js/charts/hBar_side.js"></script>

<script language='javascript'>
function editUser(user_id){
	$.getJSON("processing.php?getTicketSeller="+user_id, function(data) {
		$('#first_name').val(data.first_name);
		$('#last_name').val(data.last_name);
		$('#middle_name').val(data.middle_name);
		$('#position').val(data.position);
		$('#employee_no').val(data.employee_no);
		$('#modifyUser').val(data.user_id);
		$('#del').prop('checked',false);
		$('#ts_modal').show();
		$('#ts_modal').dialog('open');
	});
} 

function deleteUser(element){
	if(element.checked){
		$('#first_name').prop("disabled",true);
		$('#last_name').prop("disabled",true);
		$('#middle_name').prop("disabled",true);
		$('#position').prop("disabled",true);
		$('#employee_no').prop("disabled",true);
		$('#modifyUser').prop("disabled",true);
		$('#action').val('delete');
	}
	else {
		$('#first_name').prop("disabled",false);
		$('#last_name').prop("disabled",false);
		$('#middle_name').prop("disabled",false);
		$('#position').prop("disabled",false);
		$('#employee_no').prop("disabled",false);
		$('#modifyUser').prop("disabled",false);

		$('#action').val('edit');
	
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
	
	
	    <div class="wrapper">

        <div class="widget" style='display:none' id='class_dTable'>
            <div class="whead"><h6>Ticket Seller</h6><div class="clear"></div></div>
            <div id="dyn2" class="shownpars">
                <a class="tOptions act" title="Options"><img src="images/icons/options" alt="" /></a>

<table width=100% class='dTable'>
<thead>
<tr>
<td>Name</td>
<td>Middle Name</td>
<td>Employee Number</td>
<td>Position</td>

</tr>
</thead>
<tbody>
<?php
$db=retrieveDb();
$sql="select * from ticket_seller where status='active' order by last_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<tr>
		<td><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?><a href='#' onclick='editUser("<?php echo $row['id']; ?>")' ><i class='icos-pencil pull-right'></i></a></td>
		<td><?php echo $row['middle_name']; ?></td>
		<td><?php echo $row['employee_number']; ?></td>
		<td><?php echo $row['position']; ?></td>

	</tr>
<?php
}
?>
</tbody>
</table>

</div>
<div class="clear"></div> 

</div>
</div>


							<div id="ts_modal" name='ts_modal' title="Edit User" style='display:none;'>
								
								<form autocomplete='off'  action='admin_page_2.php' method='post' name='ts_form' id='ts_form'>
								<input type='hidden' name='action' id='action' value='edit' class='form_action2'>
								<input type='hidden' name='modifyUser' id='modifyUser' />

								<table class='tDefault' id='ts_table' style='width:100%'>
								<tr>
									<td>First Name</td>
									<td><input type='text' name='first_name' id='first_name' /></td>
                                </tr>
								<tr>
									<td>Last Name</td>
									<td><input type='text' name='last_name' id='last_name' /></td>

								</tr>
								<tr>
									<td>Middle Name</td>
									<td><input type='text' name='middle_name' id='middle_name'  /></td>

								</tr>	
								<tr>
									<td>Position</td>
									<td>
										<input type='text' name='position' id='position'/>
									</td>
								</tr>
                                <tr>
									<td>Employee Number</td>
									<td><input type='text' name='employee_no' id='employee_no' /></td>
								</tr>	
								<tr>
									<td colspan=2><input type='checkbox' name='del' id='del' onclick='deleteUser(this)'/>Delete User</td>
								
								</tr>
								</tbody>
								
								</table>
								</form>
								</div>


						</div>

						</div>



















