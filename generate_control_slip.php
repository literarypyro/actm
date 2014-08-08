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


//$control_id=$_SESSION['control_id'];
$control_id=$_GET['control_id'];
?>
<?php

	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/control slip.xls";

	$newFilename="printout/Control Slip ".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="Control Slip";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	

	$db=new mysqli("localhost","root","","finance");

	$tickets[0]="sjt";
	$tickets[1]="sjd";
	$tickets[2]="svt";
	$tickets[3]="svd";
	
	$grid[0]=15;
	$grid[1]=16;
	$grid[2]=17;
	$grid[3]=18;
	
	$sql="select * from control_slip inner join station on control_slip.station=station.id where control_slip.id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$type=$row['unit'];
		$station=$row['station_name'];
		$idCode=$row['ticket_seller'];
		
		$ticketSellerSQL="select * from ticket_seller where id='".$idCode."'";
		$ticketSellerRS=$db->query($ticketSellerSQL);
		$ticketSellerRow=$ticketSellerRS->fetch_assoc();
		
		
		$dateSQL="select * from logbook where id='".$row['log_id']."'";
		$dateRS=$db->query($dateSQL);
		$dateRow=$dateRS->fetch_assoc();
		
		
		
		if($type=="A/D"){
			addContent(setRange("C8","C8"),$excel,"X","true",$ExWs);

			addContent(setRange("F7","I7"),$excel,"Date: ".date("m/d/Y",strtotime($dateRow['date'])),"true",$ExWs);

			addContent(setRange("F8","J8"),$excel,"ID NUMBER: ".strtoupper($ticketSellerRow['first_name'])." ".strtoupper($ticketSellerRow['last_name'])." (".$idCode.")","true",$ExWs);
			
			$excel->getActiveSheet()->getStyle("F8:J8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		}
		else {
			addContent(setRange("C10","C10"),$excel,"X","true",$ExWs);
		
		}
		
		
		addContent(setRange("A6","F6"),$excel,"STATION: ".$station,"true",$ExWs);
		
		addContent(setRange("I5","J5"),$excel,"CS No.: ".$row['reference_id'],"true",$ExWs);
		
		
	
	}
	
	$sql="select * from additional_allocation where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$additional_ticket['sjt']=$row['sjt'];
		$additional_ticket['sjd']=$row['sjd'];
		$additional_ticket['svt']=$row['svt'];
		$additional_ticket['svd']=$row['svd'];
		
	}
	
	for($i=0;$i<4;$i++){

		$sql="select * from allocation where control_id='".$control_id."' and type='".$tickets[$i]."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		$row=$rs->fetch_assoc();
		$initial=$row['initial']*1+$row['initial_loose']*1;
		$additional=$additional_ticket[$tickets[$i]];
	
		addContent(setRange("B".$grid[$i],"B".$grid[$i]),$excel,$initial,"true",$ExWs);
		addContent(setRange("C".$grid[$i],"D".$grid[$i]),$excel,$additional,"true",$ExWs);

		
		
		
		$sql="select * from control_unsold where control_id='".$control_id."' and type='".$tickets[$i]."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		$row=$rs->fetch_assoc();
			
		$sealed=$row['sealed'];
		$loose_good=$row['loose_good'];
		$loose_defective=$row['loose_defective'];
		
		addContent(setRange("E".$grid[$i],"E".$grid[$i]),$excel,$sealed,"true",$ExWs);
		addContent(setRange("F".$grid[$i],"F".$grid[$i]),$excel,$loose_good,"true",$ExWs);
		addContent(setRange("G".$grid[$i],"G".$grid[$i]),$excel,$loose_defective,"true",$ExWs);
	}
	
	
	$sql="select * from control_sales_amount where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
		
	$sjt_amount=$row['sjt'];
	$sjd_amount=$row['sjd'];
	$svt_amount=$row['svt'];
	$svd_amount=$row['svd'];
	
	
	addContent(setRange("I15","J15"),$excel,$sjt_amount,"true",$ExWs);
	addContent(setRange("I16","J16"),$excel,$sjd_amount,"true",$ExWs);
	addContent(setRange("I17","J17"),$excel,$svt_amount,"true",$ExWs);
	addContent(setRange("I18","J18"),$excel,$svd_amount,"true",$ExWs);
	
	
	$sql="select * from fare_adjustment where control_id='".$control_id."'";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();

	$sjt_adjustment=$row['sjt'];
	$sjd_adjustment=$row['sjd'];
	$svt_adjustment=$row['svt'];
	$svd_adjustment=$row['svd'];	
	$c_adjustment=$row['c'];	
	$ot_adjustment=$row['ot'];	

	addContent(setRange("I24","J24"),$excel,$sjt_adjustment,"true",$ExWs);
	addContent(setRange("I25","J25"),$excel,$sjd_adjustment,"true",$ExWs);
	addContent(setRange("I26","J26"),$excel,$svt_adjustment,"true",$ExWs);
	addContent(setRange("I27","J27"),$excel,$svd_adjustment,"true",$ExWs);

	addContent(setRange("I29","J29"),$excel,$c_adjustment,"true",$ExWs);
	addContent(setRange("I30","J30"),$excel,$ot_adjustment,"true",$ExWs);

	$sql="select * from fare_adjustment_tickets where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();

	$sjt_adjustment=$row['sjt'];
	$sjd_adjustment=$row['sjd'];
	$svt_adjustment=$row['svt'];
	$svd_adjustment=$row['svd'];	
	$c_adjustment=$row['c'];	
	$ot_adjustment=$row['ot'];	

	addContent(setRange("H24","H24"),$excel,$sjt_adjustment,"true",$ExWs);
	addContent(setRange("H25","H25"),$excel,$sjd_adjustment,"true",$ExWs);
	addContent(setRange("H26","H26"),$excel,$svt_adjustment,"true",$ExWs);
	addContent(setRange("H27","H27"),$excel,$svd_adjustment,"true",$ExWs);

	addContent(setRange("H29","H29"),$excel,$c_adjustment,"true",$ExWs);
	addContent(setRange("H30","H30"),$excel,$ot_adjustment,"true",$ExWs);


	$sql="select * from control_cash where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();

		$overage=$row['overage'];
//		$add_others=$row['add_others'];

//		$discount=$row['discount'];
//		$refund=$row['refund'];
//		$less_others=$row['less_others'];
		$unpaid_shortage=$row['unpaid_shortage'];
		$cash_advance=$row['cash_advance'];
	
	}
	$sql="select * from unreg_sale where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$add_others=$row['sj']*1+$row['sv']*1;
	
	
	}


	$sql="select * from refund where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	
	if($nm>0){
		$row=$rs->fetch_assoc();	
		$refund_sj=$row['sj_amount'];
		$refund_sv=$row['sv_amount'];
	}
	
	$sql="select * from discount where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$discount_sj=$row['sj'];
		$discount_sv=$row['sv'];
	}
	
	addContent(setRange("I34","J34"),$excel,$cash_advance,"true",$ExWs);

	
	addContent(setRange("I35","J35"),$excel,$overage,"true",$ExWs);
	addContent(setRange("I36","J36"),$excel,$add_others,"true",$ExWs);
	
	addContent(setRange("I37","I37"),$excel,$refund_sj,"true",$ExWs);
	addContent(setRange("J37","J37"),$excel,$refund_sv,"true",$ExWs);


	addContent(setRange("I38","J38"),$excel,$unpaid_shortage,"true",$ExWs);
	
	
	addContent(setRange("I39","I39"),$excel,$discount_sj,"true",$ExWs);
	addContent(setRange("J39","J39"),$excel,$discount_sv,"true",$ExWs);

	
	
	save($ExWb,$excel,$newFilename); 	
	echo "Control Slip printout has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";
	
//$objReader = PHPExcel_IOFactory::createReader('Excel5');
//$objReader->setReadDataOnly(true);

//$excel = $objReader->load("treasury forms/control slip.xls");
//$ExWs = $excel->getActiveSheet();

//$ExWs=createWorksheet($excel,$workSheetName,"create");		


//$newFilename="printout/Control Slip ".$dateSlip.".xls";
//$workSheetName="Control Slip";
//$workbookname=$newFilename;
//$excel=startCOMGiven();
/*
$ExWb=$workbookname;	

$db=new mysqli("localhost","root","","finance");
$sql="select * from control_cash where control_id='".$control_id."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$overage=$row['overage'];
$add_others=$row['add_others'];

$discount=$row['discount'];
$refund=$row['refund'];
$less_others=$row['less_others'];
$unpaid_shortage=$row['unpaid_shortage'];

addContent(setRange("H35","H35"),$excel,$overage,"true",$ExWs);
addContent(setRange("H36","H36"),$excel,$add_others,"true",$ExWs);
addContent(setRange("H37","H37"),$excel,$refund,"true",$ExWs);
addContent(setRange("H38","H38"),$excel,$unpaid_shortage,"true",$ExWs);
addContent(setRange("H39","H39"),$excel,$discount,"true",$ExWs);

*/

//addContent(setRange("A".$rowCount,"M".$rowCount),$excel,"ENTRY/EXIT SUMMARY","true",$ExWs);





/*


save($ExWb,$excel,$newFilename); 
echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
*/

//}

	
//$ExWs=createWorksheet($excel,$workSheetName,"create");		

/*
$db=new mysqli("localhost","root","","finance");
$sql="select * from control_cash where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;


addContent(setRange("A".$rowCount,"M".$rowCount),$excel,"ENTRY/EXIT SUMMARY","true",$ExWs);

*/








/*
if($viewType=="weekly"){
	$difference=(strtotime($endDate)*1-strtotime($startDate)*1)/(86400);

	if($difference>7){
		$difference=7;
		$endDate=date("Y-m-d",strtotime($startDate."+5 days"));
		
		
	}

	$newFilename="printout/".date("d M",strtotime($startDate))."-".date("d M, Y",strtotime($endDate)).".xls";
	$workSheetName="CCS Ridership";
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	$ExWb=$workbookname;	

	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		
	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(14);	

	$rowCount=2;

	addContent(setRange("A".$rowCount,"M".$rowCount),$excel,"ENTRY/EXIT SUMMARY","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$rowCount++;
	addContent(setRange("A".$rowCount,"M".$rowCount),$excel,date("d F",strtotime($startDate))."-".date("d F, Y",strtotime($endDate)),"true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	
	$rowCount=5;
	$columnCount=66;

	$sumStart=$rowCount;
	$sumEnd=$rowCount*1+14;
	
	
	$cell=strtoupper("A".($rowCount*1));
	$cell2=strtoupper("A".($rowCount*1+1));

	addContent(setRange($cell,$cell2),$excel,"Station","true",$ExWs);
	$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);	
	$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setWrapText(true);			
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);
	$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);


	$i=0;
		
	$prefixIter=66;
	$initialPrefix="";
//		if($difference==0){ $difference++; }
	for($n=0;$n<($difference*1+1);$n++){
		$cellPrefix=$columnCount*1+($i*1);

		if($cellPrefix>90){
			$i=0;
			//$prefixIter+=1;
			$initialPrefix="A";
			$columnCount=66;
				
			$cellPrefix=$columnCount*1+($i*1);
		}
		else {
			$i++;
		}
		$prefix=$initialPrefix.chr($cellPrefix);
	//		$columnCount++;
		$cellPrefix2=$columnCount*1+($i*1);
		if($cellPrefix2>90){
			$i=0;
				//$prefixIter++;

			$initialPrefix="A";
			$columnCount=65;
			$cellPrefix2=$columnCount*1+($i*1);		
			$columnCount++;
		}
		else {
			$i++;
		}
		$prefix2=$initialPrefix.chr($cellPrefix2);
		
//		$columnCount++;	

		$cell=strtoupper($prefix.$rowCount);
		$cell2=strtoupper($prefix2.$rowCount);

		$date=date("Y-m-d",strtotime($startDate."+".$n." days"));
			
		addContent(setRange($cell,$cell2),$excel,$date,"true",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->getFont()->setBold(true);	
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell2)->applyFromArray($styleArray);
		$cell=strtoupper($prefix.($rowCount*1+1));
		$cell2=strtoupper($prefix2.($rowCount*1+1));
		addContent(setRange($cell,$cell),$excel,"Entry","false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getFont()->setBold(true);		

		$cell=strtoupper($prefix.($rowCount*1+1));
		$cell2=strtoupper($prefix2.($rowCount*1+1));
		addContent(setRange($cell2,$cell2),$excel,"Exit","false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell2.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
				),
			),
		);
		$excel->getActiveSheet()->getStyle($cell2.":".$cell2)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle($cell2.":".$cell2)->getFont()->setBold(true);		
			
		$cell=strtoupper($prefix.($rowCount*1+15));
		$cell2=strtoupper($prefix2.($rowCount*1+15));


		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle($cell2.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		addContent(setRange($cell,$cell),$excel,"=sum(".$prefix.$sumStart.":".$prefix.$sumEnd.")","false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle($cell)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle($cell)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

		addContent(setRange($cell2,$cell2),$excel,"=sum(".$prefix2.$sumStart.":".$prefix2.$sumEnd.")","false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell2)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle($cell2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle($cell2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle($cell2)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

		addContent(setRange($prefix.($rowCount*1+16),$prefix.($rowCount*1+16)),$excel,"=if(".$cell.">".$cell2.",".($cell)."-".($cell2).",\"\")","false",$ExWs);
		$excel->getActiveSheet()->getStyle($prefix.($rowCount*1+16).":".$prefix.($rowCount*1+16))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
				),
			),
		);
			$temp=$prefix.($rowCount*1+16);
			$excel->getActiveSheet()->getStyle($temp.":".$temp)->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($temp.":".$temp)->applyFromArray($styleArray);	
			$excel->getActiveSheet()->getStyle($temp.":".$temp)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent(setRange($prefix2.($rowCount*1+16),$prefix2.($rowCount*1+16)),$excel,"=IF(".$cell2.">".$cell.",".($cell2)."-".($cell).",\"\")","false",$ExWs);
			$excel->getActiveSheet()->getStyle($prefix.($rowCount*1+16).":".$prefix.($rowCount*1+16))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					),
				),
			);
			
			$temp=$prefix2.($rowCount*1+16);			
			$excel->getActiveSheet()->getStyle($temp.":".$temp)->getFont()->setBold(true);		
			$excel->getActiveSheet()->getStyle($temp.":".$temp)->applyFromArray($styleArray);	
			$excel->getActiveSheet()->getStyle($temp.":".$temp)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$cellPrex[$date]['Entry']=$prefix;
			$cellPrex[$date]['Exit']=$prefix2;

			
	

	}	
	$rowCount+=2;
	$db=new mysqli("localhost","root","","ridership");
	$sql="select * from station";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$prefix="A";
		$cell=strtoupper($prefix.$rowCount);
		addContent(setRange($cell,$cell),$excel,$row['station_name'],"false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);

		for($n=0;$n<($difference*1+1);$n++){			
			$date=date("Y-m-d",strtotime($startDate."+".$n." days"));	
			if($a==2){
				addContent(setRange($cellPrex[$date]['Entry'].$rowCount,$cellPrex[$date]['Entry'].$rowCount),$excel,"=".$cellPrex[$date]['Entry'].($rowCount*1-19)."+".$cellPrex[$date]['Entry'].($rowCount*1-38),"false",$ExWs);
				addContent(setRange($cellPrex[$date]['Exit'].$rowCount,$cellPrex[$date]['Exit'].$rowCount),$excel,"=".$cellPrex[$date]['Exit'].($rowCount*1-19)."+".$cellPrex[$date]['Exit'].($rowCount*1-38),"false",$ExWs);

			}
				$excel->getActiveSheet()->getStyle($cellPrex[$date]['Entry'].$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$excel->getActiveSheet()->getStyle($cellPrex[$date]['Entry'].$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);

				$excel->getActiveSheet()->getStyle($cellPrex[$date]['Exit'].$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$excel->getActiveSheet()->getStyle($cellPrex[$date]['Exit'].$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
				$excel->getActiveSheet()->getStyle($cellPrex[$date]['Exit'].$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

				
		}
		$rowCount++;
			
	}
	
		$cell=strtoupper("A".($rowCount*1));
		addContent(setRange($cell,$cell),$excel,"TOTAL","false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
				),
			),
		);		
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);		
		$rowCount++;
		$cell=strtoupper("A".($rowCount*1));
		addContent(setRange($cell,$cell),$excel,"Discrepancy","false",$ExWs);
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
				),
			),
		);			
		$excel->getActiveSheet()->getStyle($cell.":".$cell)->applyFromArray($styleArray);		
		$rowCount=6;

		$db=new mysqli("localhost","root","","ridership");
		$sql="select sum(rider_entry) as rider_entry, sum(rider_exit) as rider_exit, station, rider_date from ridership where rider_date between '".$startDate." 00:00:00' and '".$endDate." 23:23:59' group by rider_date,station"; 

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();	
			$date=date("Y-m-d",strtotime($row['rider_date']));

			$cell=strtoupper($cellPrex[$date]['Entry'].($rowCount*1+($row['station']*1)));
			$cell2=strtoupper($cellPrex[$date]['Exit'].($rowCount*1+($row['station']*1)));
			addContent(setRange($cell,$cell),$excel,$row['rider_entry'],"false",$ExWs);
			$excel->getActiveSheet()->getStyle($cell.":".$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			addContent(setRange($cell2,$cell2),$excel,$row['rider_exit'],"false",$ExWs);
			$excel->getActiveSheet()->getStyle($cell2.":".$cell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$entry_exit['0130'][$date]['Entry']+=$row['rider_entry']*1;
			$entry_exit['0130'][$date]['Exit']+=$row['rider_exit']*1;
					
		}
	save($ExWb,$excel,$newFilename); 
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	
}
else if($viewType=="hourly"){
	$newFilename="printout/".date("F d Y",strtotime($startDate))." Hourly Ridership.xls";
	$workSheetName="CCS Ridership";
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	$ExWb=$workbookname;	

	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$rowCount=2;

	addContent(setRange("A".$rowCount,"M".$rowCount),$excel,"HOURLY RIDERSHIP REPORT","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$rowCount+=2;

	$column=66;
	$column2=67;
	addContent(setRange("A".$rowCount,"A".($rowCount*1+1)),$excel,"Time","true",$ExWs);
	
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".($rowCount*1+1))->applyFromArray($styleArray);		
	
	
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	
	$db=new mysqli("localhost","root","","ridership");
	$station="select * from station";
	$rs=$db->query($station);
	$nm=$rs->num_rows;
	
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);			
	
	for($i=0;$i<$nm;$i++){
		$prefix=chr($column);
		$prefix2=chr($column2);
		if($column>90){
			$column=$column-26;
			$prefix="A".chr($column);
		
		}

		if($column2>90){
			$column2=$column2-26;
			$prefix2="A".chr($column2);
			
		}

		
		$row=$rs->fetch_assoc();
		addContent(setRange($prefix.$rowCount,$prefix2.$rowCount),$excel,$row['station_name'],"true",$ExWs);
		$excel->getActiveSheet()->getStyle($prefix.$rowCount.":".$prefix2.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$excel->getActiveSheet()->getStyle($prefix.$rowCount.":".$prefix2.$rowCount)->applyFromArray($styleArray);		
		
		addContent(setRange($prefix.($rowCount*1+1),$prefix.($rowCount*1+1)),$excel,"Entry","true",$ExWs);
		addContent(setRange($prefix2.($rowCount*1+1),$prefix2.($rowCount*1+1)),$excel,"Exit","true",$ExWs);

		$excel->getActiveSheet()->getStyle($prefix.($rowCount*1+1).":".$prefix.($rowCount*1+1))->applyFromArray($styleArray);		
		$excel->getActiveSheet()->getStyle($prefix2.($rowCount*1+1).":".$prefix2.($rowCount*1+1))->applyFromArray($styleArray);		
		
		$stationEntry[$row['id']]=$prefix;
		$stationExit[$row['id']]=$prefix2;

		$column+=2;
		$column2+=2;
	
	}

	addContent(setRange("AB".$rowCount,"AC".$rowCount),$excel,"Total","true",$ExWs);
	$excel->getActiveSheet()->getStyle("AB".$rowCount.":AC".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("AB".$rowCount.":AC".$rowCount)->applyFromArray($styleArray);		


	$excel->getActiveSheet()->getStyle("AB".($rowCount*1+1).":AB".($rowCount*1+1))->applyFromArray($styleArray);		
	$excel->getActiveSheet()->getStyle("AC".($rowCount*1+1).":AC".($rowCount*1+1))->applyFromArray($styleArray);		
	
	addContent(setRange("AB".($rowCount*1+1),"AB".($rowCount*1+1)),$excel,"Entry","true",$ExWs);
	addContent(setRange("AC".($rowCount*1+1),"AC".($rowCount*1+1)),$excel,"Exit","true",$ExWs);


	$rowCount+=2;
	$start=$rowCount;
	$hourSQL="select * from hourly";
	$rs=$db->query($hourSQL);
	$nm=$rs->num_rows;


	for($i=0;$i<$nm;$i++){
		$entryClause="=sum(";
		$exitClause="=sum(";

		$row=$rs->fetch_assoc();
		$hourRow[$row['hour_id']]=$rowCount*1;
		
		$hourLabel=$row['hour_id'].":00-".$row['hour_id'].":59";
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);	
		
		$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->applyFromArray($styleArray);		
		
		
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$hourLabel,"true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
		
		for($k=1;$k<=count($stationEntry);$k++){
			if($k==1){
				$entryClause.=$stationEntry[$k].$rowCount;		
			
			}
			else {
				$entryClause.=",".$stationEntry[$k].$rowCount;
			}
		
		}

		for($k=1;$k<=count($stationExit);$k++){
			if($k==1){
				$exitClause.=$stationExit[$k].$rowCount;		
			
			}
			else {
				$exitClause.=",".$stationExit[$k].$rowCount;
			}
		}
		
		$entryClause.=")";
		$exitClause.=")";

		addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,$entryClause,"true",$ExWs);
		addContent(setRange("AC".$rowCount,"AC".$rowCount),$excel,$exitClause,"true",$ExWs);
		$excel->getActiveSheet()->getStyle("AB".$rowCount.":AB".$rowCount)->applyFromArray($styleArray);		
		$excel->getActiveSheet()->getStyle("AC".$rowCount.":AC".$rowCount)->applyFromArray($styleArray);			


		for($n=1;$n<=count($stationEntry);$n++){
			$excel->getActiveSheet()->getStyle($stationEntry[$n].$rowCount.":".$stationEntry[$n].$rowCount)->applyFromArray($styleArray);		


		}

		for($n=1;$n<=count($stationExit);$n++){
			$excel->getActiveSheet()->getStyle($stationExit[$n].$rowCount.":".$stationExit[$n].$rowCount)->applyFromArray($styleArray);		

		}

		
		$rowCount++;	
	}
	
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->applyFromArray($styleArray);		

	
	addContent(setRange("A".$rowCount,"A".$rowCount),$excel,"TOTAL","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":A".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$end=$rowCount*1-1;
	
	for($i=1;$i<=count($stationEntry);$i++){
		addContent(setRange($stationEntry[$i].$rowCount,$stationEntry[$i].$rowCount),$excel,"=sum(".$stationEntry[$i].$start.":".$stationEntry[$i].$end.")","true",$ExWs);
		$excel->getActiveSheet()->getStyle($stationEntry[$i].$rowCount.":".$stationEntry[$i].$rowCount)->applyFromArray($styleArray);		


	}
	for($i=1;$i<=count($stationExit);$i++){
		addContent(setRange($stationExit[$i].$rowCount,$stationExit[$i].$rowCount),$excel,"=sum(".$stationExit[$i].$start.":".$stationExit[$i].$end.")","true",$ExWs);
		$excel->getActiveSheet()->getStyle($stationExit[$i].$rowCount.":".$stationExit[$i].$rowCount)->applyFromArray($styleArray);		

	}

	addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,"=sum(AB".$start.":AB".$end.")","true",$ExWs);
	addContent(setRange("AC".$rowCount,"AC".$rowCount),$excel,"=sum(AC".$start.":AC".$end.")","true",$ExWs);

	$excel->getActiveSheet()->getStyle("AB".$rowCount.":AB".$rowCount)->applyFromArray($styleArray);	
	$excel->getActiveSheet()->getStyle("AC".$rowCount.":AC".$rowCount)->applyFromArray($styleArray);		
	
	$sql="select * from ridership where rider_date='".$startDate."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		addContent(setRange($stationEntry[$row['station']].$hourRow[$row['hour']],$stationEntry[$row['station']].$hourRow[$row['hour']]),$excel,$row['rider_entry'],"true",$ExWs);
		addContent(setRange($stationExit[$row['station']].$hourRow[$row['hour']],$stationExit[$row['station']].$hourRow[$row['hour']]),$excel,$row['rider_exit'],"true",$ExWs);

		
		

	}
	$rowCount++;
	
	
	
	save($ExWb,$excel,$newFilename); 
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";
	

}
else if($viewType=="monthly"){
	$newFilename="printout/".date("F",strtotime($startDate))." Ridership.xls";
	$workSheetName="CCS Ridership";
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	$ExWb=$workbookname;	

	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

	$excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
	$excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);	
	$excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);

	$excel->getActiveSheet()->getColumnDimension('E')->setWidth(0);
	$excel->getActiveSheet()->getColumnDimension('I')->setWidth(0);
	
	$rowCount=2;
	addContent(setRange("A".$rowCount,"M".$rowCount),$excel,"RIDERSHIP REPORT","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$rowCount++;
	addContent(setRange("A".$rowCount,"M".$rowCount),$excel,date("F Y",strtotime($startDate)),"true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setBold(true);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getFont()->setSize(14);		
	$excel->getActiveSheet()->getStyle("A".$rowCount.":M".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$rowCount+=2;
	addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"Dates","true",$ExWs);
	addContent(setRange("G".$rowCount,"G".$rowCount),$excel,"Total Entry","true",$ExWs);
	addContent(setRange("H".$rowCount,"H".$rowCount),$excel,"Total Exit","true",$ExWs);

	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);		
	$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->applyFromArray($styleArray);			
	$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->applyFromArray($styleArray);			
	$excel->getActiveSheet()->getStyle("H".$rowCount.":H".$rowCount)->applyFromArray($styleArray);			
	
	
	$rowCount++;
	$limit=date("t",strtotime($startDate));
	
	$month=date("m",strtotime($startDate));
	$year=date("Y",strtotime($startDate));
	$begDate=date("Y-m-d",strtotime($year."-".$month."-01"));
	
	$rider_entry=0;
	$rider_exit=0;
	$db=new mysqli("localhost","root","","ridership");
	$start=$rowCount;
	
	$averageWeekend="=average(";
	$averageWeekday="=average(";
	
	$weekendCount=0;
	$weekdayCount=0;
	for($i=0;$i<$limit;$i++){
	
		$date=date("Y-m-d",strtotime($begDate."+".$i." days"));
		$dateLabel=date("d M Y",strtotime($date));
		$dateFill=date("F d, Y",strtotime($date));
		$riderSQL="select sum(rider_entry) as rider_entry, sum(rider_exit) as rider_exit from ridership where rider_date='".$date."' group by rider_date";
		
		$riderRS=$db->query($riderSQL);
		$riderRow=$riderRS->fetch_assoc();

		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$dateLabel,"true",$ExWs);
		addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$riderRow['rider_entry'],"true",$ExWs);
		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$riderRow['rider_exit'],"true",$ExWs);
		addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$dateFill,"true",$ExWs);

		$excel->getActiveSheet()->getStyle("F".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);

		
		$excel->getActiveSheet()->getStyle("H".$rowCount)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
		$excel->getActiveSheet()->getStyle("H".$rowCount)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

		$excel->getActiveSheet()->getStyle("H".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
		$excel->getActiveSheet()->getStyle("G".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);

		
		$dayOfWeek=date("w",strtotime($date));
		if(($dayOfWeek==6)||($dayOfWeek==0)){
			if($weekendCount==0){
				$averageWeekend.="G".$rowCount;
			
			}
			else {
				$averageWeekend.=",G".$rowCount;
			}
			$weekendCount++;
			$excel->getActiveSheet()->getStyle("F".$rowCount.":H".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
		}		
		else {
			if($weekdayCount==0){
				$averageWeekday.="G".$rowCount;
			
			}
			else {
				$averageWeekday.=",G".$rowCount;
			}
			$weekdayCount++;
		}
		
		
		
		$rowCount++;
	}	
	$averageWeekend.=")";
	$averageWeekday.=")";
	
	
	
	$end=$rowCount-1;
	addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"Total","true",$ExWs);
	addContent(setRange("G".$rowCount,"G".$rowCount),$excel,"=SUM(G".$start.":G".$end.")","true",$ExWs);
	addContent(setRange("H".$rowCount,"H".$rowCount),$excel,"=SUM(H".$start.":H".$end.")","true",$ExWs);

	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			),
		),
	);		
	$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->applyFromArray($styleArray);		
	$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->applyFromArray($styleArray);		
	$excel->getActiveSheet()->getStyle("H".$rowCount.":H".$rowCount)->applyFromArray($styleArray);		
	
	
	$rowCount++;
	$rowCount++;
	addContent(setRange("D".$rowCount,"G".$rowCount),$excel,"Highest Ridership for the Month","true",$ExWs);
	addContent(setRange("H".$rowCount,"J".$rowCount),$excel,"=max(G".$start.":G".$end.")","true",$ExWs);

	$rowCount++;
	addContent(setRange("D".$rowCount,"G".$rowCount),$excel,"Date","true",$ExWs);
	addContent(setRange("H".$rowCount,"J".$rowCount),$excel,"=VLOOKUP(MAX(G".$start.":G".$end."),G".$start.":I".$end.",3,0)","true",$ExWs);	
	$excel->getActiveSheet()->getStyle("H".$rowCount.":J".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	$rowCount++;
	addContent(setRange("D".$rowCount,"G".$rowCount),$excel,"Daily Ridership Average","true",$ExWs);
	addContent(setRange("H".$rowCount,"J".$rowCount),$excel,"=average(G".$start.":G".$end.")","true",$ExWs);

	$rowCount++;
	addContent(setRange("D".$rowCount,"G".$rowCount),$excel,"Daily Weekdays Ridership Average","true",$ExWs);
	addContent(setRange("H".$rowCount,"J".$rowCount),$excel,$averageWeekday,"true",$ExWs);

	$rowCount++;
	addContent(setRange("D".$rowCount,"G".$rowCount),$excel,"Daily Weekend Ridership Average","true",$ExWs);
	addContent(setRange("H".$rowCount,"J".$rowCount),$excel,$averageWeekend,"true",$ExWs);
	
*/	
	

	
	
	

?>