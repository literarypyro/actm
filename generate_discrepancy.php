<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");
$startDate=$_GET['startDate'];
$endDate=$_GET['endDate'];
$viewType=$_GET['view'];


$control_id=$_SESSION['control_id'];
?>
<?php

	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/Discrepancy Form.xls";

	$newFilename="printout/Discrepancy ".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="Discrepancy Report";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
//	$transaction
	$db=new mysqli("localhost","root","","finance");
	
	$type=$_GET['type'];
	$transaction_id=$_GET['transID'];
	if($type=="ticket"){
		$sql="select * from transaction where transaction_id='".$transaction_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		
		$transaction_date=date("m/d/Y",strtotime($row['date']));

		$sql="select * from ticket_order where transaction_id='".$transaction_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$station=$row['station'];
		$ticket_seller=$row['ticket_seller'];
		if($station=="annex"){
			$station_name=strtoupper($station);
		}
		else {
			$sql="select * from station where id='".$station."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			$station_name=$row['station_name'];
		}
		$sql="select * from ticket_seller where id='".$ticket_seller."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$ts_name=ucfirst(strtolower($row['last_name'])).", ".ucfirst(strtolower($row['first_name']));
	

		addContent(setRange("B12","B12"),$excel,"x","true",$ExWs);			
		addContent(setRange("K8","N8"),$excel,$transaction_date,"true",$ExWs);			
			
		addContent(setRange("D8","H8"),$excel,$station_name,"true",$ExWs);			
		addContent(setRange("E9","H9"),$excel,$ts_name,"true",$ExWs);			
		
		$sql="select * from discrepancy_ticket where transaction_id='".$transaction_id."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
				
		$discrepancyCounter=0;
		$content="";
		if($nm>0){
		
			for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
				if($i==0){
					$classification=$row['classification'];
					$reference_id=$row['reference_id'];
					$reported=$row['reported'];
				}				
				
				if($row['ticket_type']=="sjt"){
					$sjt_classification=$row['type'];
					$sjt_amount=$row['amount'];
					if($sjt_amount>0){
						if($discrepancyCounter>0){
							$content.=", ";
							
						}
						else {
						}
						
						$content.=ucfirst($sjt_classification)." of ".$sjt_amount." SJT tickets";
					}				
					
					$discrepancyCounter++;
					
				}
				else if($row['ticket_type']=="sjd"){
					$sjd_classification=$row['type'];
					$sjd_amount=$row['amount'];
				
					if($sjd_amount>0){
						if($discrepancyCounter>0){
							$content.=", ";
							
						}
						else {
						}
						
						$content.=ucfirst($sjd_classification)." of ".$sjd_amount." SJD tickets";
					}			
					$discrepancyCounter++;

					
				}
				else if($row['ticket_type']=="svt"){
					$svt_classification=$row['type'];
					$svt_amount=$row['amount'];
					if($svt_amount>0){
						if($discrepancyCounter>0){
							$content.=", ";
							
						}
						else {
						}
						
						$content.=ucfirst($svt_classification)." of ".$svt_amount." SVT tickets";
					}				
					$discrepancyCounter++;
				
				
				}
				else if($row['ticket_type']=="svd"){
					$svd_classification=$row['type'];
					$svd_amount=$row['amount'];
					if($svd_amount>0){
						if($discrepancyCounter>0){
							$content.=", ";
							
						}
						else {
						}
						
						$content.=ucfirst($svd_classification)." of ".$svd_amount." SVD tickets";
					}				
					$discrepancyCounter++;

				}				
		
		
			}
		}

		if($discrepancyCounter>0){
			if($reported=="ticket seller"){
				addContent(setRange("B24","M30"),$excel,$content,"true",$ExWs);			
				
			}
			else if($reported=="cash assistant"){
				addContent(setRange("B15","M21"),$excel,$content,"true",$ExWs);			
			}
		
		
		}

	
		
	
	
	
	
	}
	else if($type=="cash"){
		$sql="select * from transaction where transaction_id='".$transaction_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		
		$transaction_date=date("m/d/Y",strtotime($row['date']));
		
		$sql="select * from cash_transfer where transaction_id='".$transaction_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		
		$station=$row['station'];
		$ticket_seller=$row['ticket_seller'];
		
		if($station=="annex"){
			$station_name=strtoupper($station);
		}
		else {
			$sql="select * from station where id='".$station."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			$station_name=$row['station_name'];
		}
		$sql="select * from ticket_seller where id='".$ticket_seller."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$ts_name=ucfirst(strtolower($row['last_name'])).", ".ucfirst(strtolower($row['first_name']));
		
		
	
		$sql="select * from discrepancy where transaction_id='".$transaction_id."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;		
		
		if($nm>0){
			$row=$rs->fetch_assoc();
			$overageType=$row['type'];
			$classification=$row['classification'];
			$amount=$row['amount'];
			$reference_id=$row['reference_id'];
			$reported=$row['reported'];			
			
			
			$content="Cash ".$overageType." of P".number_format($amount*1,2);		
			if($reported=="ticket seller"){
				addContent(setRange("B24","M30"),$excel,$content,"true",$ExWs);			
				
			}
			else if($reported=="cash assistant"){
				addContent(setRange("B15","M21"),$excel,$content,"true",$ExWs);			
			}
			
			addContent(setRange("K8","N8"),$excel,$transaction_date,"true",$ExWs);			
			
			addContent(setRange("D8","H8"),$excel,$station_name,"true",$ExWs);			
			addContent(setRange("E9","H9"),$excel,$ts_name,"true",$ExWs);			
			
			addContent(setRange("B11","B11"),$excel,"x","true",$ExWs);			
			
		}
	}
	save($ExWb,$excel,$newFilename); 	
	echo "Discrepancy Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	
	

?>