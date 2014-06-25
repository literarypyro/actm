<?php
session_start();
?>
<?php
if(isset($_GET['searchCA'])){
	$lastName=$_GET['searchCA'];

	$db=new mysqli("localhost","root","","finance");
	$sql="select * from login where lastName like '".$lastName."%%' order by lastName"; 
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['username'].";";
			$result.=$row['firstName'].";";
			$result.=strtoupper($row['lastName'])."==>";

	
		}
		echo $result;
	}
	else {
		echo "None available.";
	}
}

if(isset($_GET['searchTS'])){
	$lastName=$_GET['searchTS'];

	$db=new mysqli("localhost","root","","finance");
	$sql="select * from ticket_seller where last_name like '".$lastName."%%' order by last_name"; 
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$result="";
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$result.=$row['id'].";";
			$result.=$row['first_name'].";";
			$result.=strtoupper($row['last_name'])."==>";

	
		}
		echo $result;
	}
	else {
		echo "None available.";
	}
}

?>