<link href="layout/logbook style.css" rel="stylesheet" type="text/css" />

<table id='logHeader' width=100% style='border:1px solid gray'>
<tr>
<td width=50%>
Cash Assistant: 
<?php
$historySQL="select * from log_history where log_id='".$log_id."' group by username order by id";

$historyRS=$db->query($historySQL);
$historyNM=$historyRS->num_rows;
/*
if($historyNM>1){

	for($i=0;$i<$historyNM;$i++){
		$historyRow=$historyRS->fetch_assoc();
		$db=new mysqli("localhost","root","","finance");
		$sql="select * from login where username='".$historyRow['username']."'";
		
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		$row=$rs->fetch_assoc();
		if($i==0){
			echo strtoupper($row['lastName']).", ".$row['firstName'];
		}
		else {
			if($i==($historyNM-1)){
			echo "<font color=red><b>--".strtoupper($row['lastName']).", ".$row['firstName']."</b></font>";
			
			}
			else {
			echo "--".strtoupper($row['lastName']).", ".$row['firstName'];
			}
		}
		
		
	}
}
*/
//else {


$db=new mysqli("localhost","root","","finance");
$sql="select * from login where username='".$logUser."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
$row=$rs->fetch_assoc();

echo strtoupper($row['lastName']).", ".$row['firstName'];
echo " <a href='#' onclick='window.open(\"change_user.php\",\"change_user\",\"height=300, width=500\")'>[Change User]</a>";

}
else {
	echo "No one has logged to this shift yet.";
}

//}
?>
</td>
<th rowspan=2 align=left>
<h3><?php echo strtoupper($logStation); ?></h3>
</th>


<td align=right>
Date: <?php echo $logDate; ?><br>
</td>
</tr>
<tr>
<td>
Shift: <?php echo $shiftName; ?><br>
</td>

<td align=right><?php echo $logDayWeek; ?></td>
</tr>
</table>