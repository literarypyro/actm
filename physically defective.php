<?php
session_start();
?>
<?php
$log_id=$_SESSION['log_id'];

?>
<?php
$db=new mysqli("localhost","root","","finance");
if(isset($_POST['log_id'])){
	$sql="select * from physically_defective where log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$sjt=$_POST['sjt'];
	$svt=$_POST['svt'];
	$sjd=$_POST['sjd'];
	$svd=$_POST['svd'];
	$ticket_seller=$_POST['ticket_seller'];
	$date=date("Y-m-d H:i");
	$station=$_POST['station'];
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$update="update physically_defective set station='".$station."',ticket_seller='".$ticket_seller."',sjt='".$sjt."',svt='".$svt."',sjd='".$sjd."',sjt='".$sjt."',date='".$date."' where id='".$row['id']."'";
		$updateRS=$db->query($update);	
	}
	else {
		$update="insert into physically_defective(sjt,svt,sjd,svd,log_id,date,ticket_seller,station) values ('".$sjt."','".$svt."','".$sjd."','".$svd."','".$log_id."','".$date."','".$ticket_seller."','".$station."')";
		$updateRS=$db->query($update);	
	}


}

?>
<script language='javascript'>
function submitForm(){

	document.forms['defective_form'].submit();
	window.opener.location.reload();
	//self.close();
}
function searchTicketSeller(tName){
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

			
				optionsGrid+="<select name='ticket_seller_control' id='ticket_seller_control'>";				
				

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
	

	xmlHttp.open("GET","process search.php?searchTS="+tName,true);
	xmlHttp.send();	

}
</script>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<form name='defective_form' id='defective_form' action='physically defective.php' method='post'>
<table class='controlTable2' width=100%>
<tr class='header'>
<th colspan=2>
	Physically Defective
	<input type=hidden name='log_id' id='log_id' value='<?php echo $log_id; ?>'	/>
</th>
</tr>
<tr >
<td class='subheader'>From Ticket Seller:</td>
<td colspan=2 class='grid'>	<?php
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	<div id='cafill'>
	<select name='ticket_seller'>
	<?php 
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>' <?php if($row['id']==$ticket_seller){ echo "selected"; } ?> ><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
	<?php
	}
	?>
	
	</select>
	</div>
	<!--
	<br>
	<input type=button value='Add Ticket Seller' />
	-->
</td>
</tr>
<tr>
<td class='subheader'>Search Ticket Seller</td>
<td  class='category' colspan=2>
<input type=text name='searchTS' id='searchTS' onkeyup='searchTicketSeller(this.value)' />	

</td>
</tr>
<tr><td class='subheader' >Select Station</td>
<td class='grid' colspan=2>

	<select name='station'>

<?php
$db=new mysqli("localhost","root","","finance");
$logSQL="select * from logbook where id='".$log_id."'";

$logRS=$db->query($logSQL);
$logNM=$logRS->num_rows;
if($logNM>0){
$logRow=$logRS->fetch_assoc();
$cash_assistant=$logRow['cash_assistant'];


$stationSQL="select * from station where id='".$logRow['station']."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();
$station_name=$stationRow['station_name'];
$station_id=$stationRow['id'];





}
?>
	<option value='<?php echo $station_id; ?>'><?php echo $station_name; ?></option>
	<?php
	$extensionSQL="select * from extension inner join station on extension.extension=station.id where extension.station='".$logRow['station']."'";
	$extensionRS=$db->query($extensionSQL);
	$extensionNM=$extensionRS->num_rows;
	if($extensionNM>0){
		$extensionRow=$extensionRS->fetch_assoc();
		$extensionID=$extensionRow['extension'];
		$extensionName=$extensionRow['station_name'];
	?>
	<option value='<?php echo $extensionID; ?>'><?php echo $extensionName; ?></option>
	<?php
	}
	?>

	</select>
	</td>
</tr>


<tr>
	<td align=center class='subheader'>SJT</td>
	<td class='category'><input type=text name='sjt' /></td>
</tr>
<tr>
	<td  align=center class='subheader'>SJD</td>
	<td class='grid'><input type=text name='sjd' /></td>

</tr>

<tr>
	<td  align=center class='subheader'>SVT</td>
	<td class='category'><input type=text name='svt' /></td>

</tr>
<tr>
	<td  align=center class='subheader'>SVD</td>
	<td class='grid'><input type=text name='svd' /></td>

</tr>
<tr>
<th colspan=2><input type=button value='Submit' onclick='submitForm()' /></th>
</tr>
</table>
</form>