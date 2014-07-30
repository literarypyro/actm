<?php
session_start();
?>
<?php
?>
<?php 
if(isset($_POST['username'])){
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from login where username='".$_POST['username']."' and password='".$_POST['password']."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$logTime=date("Y-m-d H:i:s");	
		$updateSQL="update log_history set logout='".$logTime."' where (logout in ('0000-00-00') or logout is null)";
		$updateRS=$db->query($updateSQL);
		
		$_SESSION['username']=$_POST['username'];
		header("Location: test_select_log_shift.php");
	}
	else {
		header("Location: index.php");
	}
}
else {
?>
<script language='javascript'>
function searchCA(caname){
	var xmlHttp;
	
	var caHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			caHTML=xmlHttp.responseText;


			if(caHTML=="None available"){
				//document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var caTerms=caHTML.split("==>");
				
				var count=(caTerms.length)*1-1;

				var optionsGrid="";

			
				optionsGrid+="<select name='username'>";				
				

				for(var n=0;n<count;n++){
					var parts=caTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
					
				}
				optionsGrid+="</select>";
				

				
					
				document.getElementById('cafill').innerHTML=optionsGrid;

				//document.getElementById('programpageNumber').value="";
	
			
			}

		}
	} 
	

	xmlHttp.open("GET","process search.php?searchCA="+caname,true);
	xmlHttp.send();	



}
</script>
<body>
<table class='header' align=center>
<tr><th><h2>Taft Ave.</h2></th></tr>
</table>

<link rel="stylesheet" type="text/css" href="layout/login.css">
<table class='loginTable' align=center >
<tr>
<td  align=center>

<form enctype="multipart/form-data" action='index.php' method='post'>
<table  align=center  >
<tr><th colspan=2>Log-In Here:</th></tr>
<tr>
	<td>Enter User ID:</td>
	<td>
	
	<!--
	<input type=text name='searchCash' id='searchCash' onkeyup='searchCA(this.value)' />
	-->
	<input type='text' name='username' id='username' width='80%' />
	<?php
		$db=new mysqli("localhost","root","","finance");


	?>
	<div id='cafill' name='cafill'>
	</div>

	</td>
</tr>
<tr>
	<td>Enter Password:</td>
	<td><input type='password' name='password'  /></td>
</tr>
<tr>
	<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
</table>
</form>

<br>
<table id='cssTable' align=center width=300>
<tr><th colspan=2>Currently Logged On:</th></tr>
<?php
$sqlLog="select * from log_history where logout in ('0000-00-00') or logout is null";
$rsLog=$db->query($sqlLog);

$nmLog=$rsLog->num_rows;

if($nmLog>0){
	$rowLog=$rsLog->fetch_assoc();
	$ticket="select * from login where username='".$rowLog['username']."'";
	$ticketRS=$db->query($ticket);
	$ticketRow=$ticketRS->fetch_assoc();
?>	
	<tr><th colspan=2><?php echo strtoupper($ticketRow['lastName']).", ".$ticketRow['firstName']; ?></th></tr>

<?php
}
else {
?>
	<tr><th colspan=2>No one is logged on.</th></tr>
<?php
}


?>
</table>

<?php
}
?>
</tr>
</td>
</table>
<!--
<table align=center style='border: 1px solid gray'>-->
<!--style="color: #ffcc35;"-->
<!--<tr><th colspan=2>For new account:</th></tr><tr><th colspan=2><h3><a  href='createAccount.php'>
 Create a new Account 
</a></h3></th></tr>
</table>
-->
</body>