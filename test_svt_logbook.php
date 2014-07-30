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

	header("Location: test_svt_logbook.php");

}
?>
<?php
if(isset($_POST['to_ticket_seller'])){


	$receive_day=date("Y-m-d",strtotime($_POST['receive_date']));
		
	$receive_time=date("Y-m-d",strtotime($receive_day." ".$_POST['receive_time']));
		
	$date=$receive_time;
	$date_id=date("Ymd",strtotime($_POST['receive_date']));

	$type="allocation";
	
	$sjt=$_POST['sjt'];
	$sjd=$_POST['sjd'];
	$svt=$_POST['svt'];
	$svd=$_POST['svd'];
	
	$sjt_loose=$_POST['sjt_loose'];
	$sjd_loose=$_POST['sjd_loose'];
	$svt_loose=$_POST['svt_loose'];
	$svd_loose=$_POST['svd_loose'];
	$station=$_POST['station'];

	
	$control_id=$_POST['to_ticket_seller'];
	
	$control_sql="select * from control_slip where id='".$control_id."' limit 1";
	$control_rs=$db->query($control_sql);
		
	$control_row=$control_rs->fetch_assoc();
		
	$ticket_seller=$control_row['ticket_seller'];
		
	$unit=$control_row['unit'];
	
	
	
	$cash_assistant=$_POST['cash_assistant'];
	$reference_id=$_POST['reference_id'];
	$ticket_type=$_POST['classification'];

	if($ticket_type=="ticket_seller"){
		$transaction_type="ticket";

	}
	else if($ticket_type=="catransfer"){
		$transaction_type="ticket_catransfer";
	}
	else if($ticket_type=="finance"){
		$transaction_type="finance";
		
	}
	else if($ticket_type=="annex"){

		$transaction_type="annex";
	}
	$unit_type=$_POST['unit_type'];
	$classification=$_POST['classification'];
	
	$db=new mysqli("localhost","root","","finance");
	
//	$sql="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','".$transaction_type."','".$type."')";
	
	if($_POST['form_action']=="new"){
		
		$sql="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','".$transaction_type."','allocation')";

		$rs=$db->query($sql);

		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;
		$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs=$db->query($sql);
		
		$sql="insert into ticket_order(log_id,time,ticket_seller,cash_assistant,type,";
		$sql.="transaction_id,sjt,sjd,svt,svd,sjt_loose,sjd_loose,svt_loose,svd_loose,unit,classification,reference_id,station,control_id) values ";
		$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$cash_assistant."','".$type."',";
		$sql.="'".$transaction_id."','".$sjt."','".$sjd."','".$svt."','".$svd."','".$sjt_loose."',";
		$sql.="'".$sjd_loose."','".$svt_loose."','".$svd_loose."','".$unit_type."','".$classification."','".$reference_id."','".$station."','".$control_id."')";

		$rs=$db->query($sql);
		$insert_id=$db->insert_id;
		$ticket_id=$insert_id;
		if($transaction_type=="ticket"){
			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit_type."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;		
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				$control_id=$row['id'];
				
				$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				if($nm==0){
					$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
					$updateRS=$db->query($update);
				}			
				
				
			}		
		}
		
		

	}
	else if($_POST['form_action']=="edit"){
		$form_action="edit";
		$sql="select * from transaction where id='".$_POST['trans_edit']."'";

		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$transaction_id=$row['transaction_id'];	
		$insert_id=$row['id'];
		
		$ticket_type=$_POST['classification'];		
		
		if($ticket_type=="ticket_seller"){
			$transaction_type="ticket";

		}
		else if($ticket_type=="catransfer"){
			$transaction_type="ticket_catransfer";
		}
		else if($ticket_type=="finance"){
			$transaction_type="finance";
			
		}
		else if($ticket_type=="annex"){

			$transaction_type="annex";
		}		
		$sql2="update transaction set log_type='".$transaction_type."' where id='".$_POST['trans_edit']."'";
		$rs2=$db->query($sql2);		
		
		
		$sql2="update ticket_order set ticket_seller='".$ticket_seller."',station='".$station."',sjt='".$sjt."',svt='".$svt."',sjd='".$sjd."',svd='".$svd."',sjt_loose='".$sjt_loose."',sjd_loose='".$sjd_loose."',svt_loose='".$svt_loose."',svd_loose='".$svd_loose."',control_id='".$control_id."' where transaction_id='".$row['transaction_id']."'";
		$rs2=$db->query($sql2);


		if($transaction_type=="ticket"){
			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit_type."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;		
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				$control_id=$row['id'];
				
				$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				if($nm==0){
					$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
					$updateRS=$db->query($update);
				}			
				
				
			}		
		}
		
	}
	
	header("Location: test_svt_logbook.php");
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

<script src="js/bootstrap-modal.js"></script>
<script src="js/bootstrap-modalmanager.js"></script>
<script language='javascript'>
function deleteRecord(transaction,type){
	var check=confirm("Delete the Transaction?");
	
	if(check){
		window.open("delete_transaction.php?tID="+transaction+"&type="+type,"_blank");
	}
}
</script>

<?php require("title_header.php"); ?>


<div class='content'>

    <div class="contentTop">
        <span class="pageTitle"><span class="icon-screen"></span>Logbooks</span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='svt_logbook.php'>Logbooks (Original Template)</a></span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='test_dsr_tickets_b.php'>Detailed Sales Report</a></span>

        <ul class="quickStats">
            <li>
                <a href="test_cash_logbook.php" class="blueImg"><img src="images/icons/quickstats/money.png" alt="" /></a>
                <div class="floatR"><strong class="blue">Cash Logbook</strong></div>
            </li>
            <li>
                <a href="test_sjt_logbook.php" class="redImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">SJT Logbook</strong></div>
            </li>
            <li>
                <a href="test_svt_logbook.php" class="greenImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">SVT Logbook</strong></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
	<?php require("test_reference_line.php"); ?>

    <div class="wrapper">

        <div class="widget">
            <div class="whead">
                <h6>SJT Logbook</h6>
                <div class="titleOpt">
                    <a href="#" data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="#" name='open_ticket' id='open_ticket' ><span class="icos-add"></span>Ticket Order</a></li>
                        <li><a href="#" name='open_defective' id='open_defective'><span class="icos-add"></span>Physically Defective</a></li>
                    </ul>
                </div>
                <div class="titleOpt">
					<a href="generateSVTLogbook.php" target='_blank' title='Print' ><span class="icos-printer"></span><span class="clear"></span></a>

                </div>
				
                <div class="clear"></div>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault table-hover">
                <thead>
                    <tr>
                        <th colspan=3 style='text-align:center;'>Particulars</th>
                        <th colspan=4 style='text-align:center;'>Tickets Supplied In/Out</th>
                        <th colspan=4 style='text-align:center;'>Tickets Remaining</th>
                        <th rowspan=3  style='text-align:center;' width='7%' valign=bottom>Remarks</th>
                    </tr>
                    <tr>
						<td rowspan=2>Time</td>
						<td rowspan=2 width='15%'>Name</td>
						<td rowspan=2>ID No.</td>
					
                        <td colspan=2>SJT</td>
                        <td colspan=2>SJD</td>

                        <td colspan=2>SJT</td>
                        <td colspan=2>SJD</td>
                    </tr>					
					<tr>
						<td>Pieces</td>
						<td>Loose</td>

						<td>Pieces</td>
						<td>Loose</td>

						<td>Pieces</td>
						<td>Loose</td>

						<td>Pieces</td>
						<td>Loose</td>
					</tr>
                </thead>
				<tbody>

				<tr>
				<?php
				$db=new mysqli("localhost","root","","finance");
				$sql="select * from beginning_balance_svt where log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
				$row=$rs->fetch_assoc();

				$svt_loose_1=$row['svt_loose'];
				$svt_packs_1=$row['svt'];


				$svd_loose_1=$row['svd_loose'];
				$svd_packs_1=$row['svd'];

				}
				else {

				$alternate="SELECT * FROM beginning_balance_svt inner join logbook on beginning_balance_svt.log_id=logbook.id and station='".$station."' order by date desc,field(revenue,'close','open'),field(shift,2,1,3)";

				$rs2=$db->query($alternate);
				$row=$rs2->fetch_assoc();
				$svt_loose_1=$row['svt_loose'];
				$svt_packs_1=$row['svt'];


				$svd_loose_1=$row['svd_loose'];
				$svd_packs_1=$row['svd'];

					$insert="insert into beginning_balance_svt(log_id,svt,svt_loose,svd,svd_loose) values ('".$log_id."','".$svt_packs_1."','".$svt_loose_1."','".$svd_packs_1."','".$svd_loose_1."')";
					$insertRS=$db->query($insert);	


				}	
				?>






				<td colspan=3>Beginning Balance <a href='#' style='text-decoration:none' onclick='window.open("beginning data entry.php?loID=<?php echo $log_id; ?>&type=sv","beginning","height=300, width=300")' >[Data Entry]</a></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

				<td>&nbsp;</td>
				<td>&nbsp;</td>


				<td align=right><?php echo $svt_packs_1; ?></td>
				<td align=right><?php echo $svt_loose_1; ?></td>

				<td align=right><?php echo $svd_packs_1; ?></td>
				<td align=right><?php echo $svd_loose_1; ?></td>
				<td>&nbsp;</td>
				</tr>

				<?php
				$db=new mysqli("localhost","root","","finance");
				$sql="select * from transaction where log_id='".$log_id."' and log_type in ('ticket','initial','annex','finance') and transaction_type not in ('ticket_amount') order by id*1";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($a=0;$a<$nm;$a++){

					$row=$rs->fetch_assoc();

					$date=date("h:i a",strtotime($row['date']));
					$edit_id=$row['id'];
					$transaction_id=$row['transaction_id'];
					
					$type=$row['transaction_type'];
					$log_type=$row['log_type'];
					if($row['reference_id']==""){
					$remarks="&nbsp;";
					}
					else {
					$remarks=$row['reference_id'];
					}	
					$suffix="";
					
					
					$svt_packs=0;
					$svd_packs=0;
					$svt_loose=0;
					$svd_loose=0;

					$svt_loose_in=0;	
					$svd_loose_in=0;	
					
					if(($row['log_type']=='ticket')||($row['log_type']=="annex")||($row['log_type']=="finance")){
						$svt_packs=0;
						$svd_packs=0;
						$svt_loose=0;
						$svd_loose=0;		
						$svt_loose_in=0;	
						$svd_loose_in=0;	

						$ticketSQL="select * from ticket_order where transaction_id='".$transaction_id."'";

						$ticketRS=$db->query($ticketSQL);
							
						$ticketRow=$ticketRS->fetch_assoc();
						
						if($ticketRow['station']==$logST){
						}
						else {
							$extensionSQL="select * from station where id='".$ticketRow['station']."'";
							$extensionRS=$db->query($extensionSQL);
							$extensionRow=$extensionRS->fetch_assoc();
									
							$suffix=" - ".$extensionRow['station_name'];
								
						}		
						
						
						
						$ticketSellerId=$ticketRow['ticket_seller'];			
						$unitType=$ticketRow['unit'];	
						$remarks=$ticketRow['reference_id'];	
						$svt_loose=$ticketRow['svt_loose'];
						$svt_packs=$ticketRow['svt'];
						
						//$svt_packs=($ticketRow['svt']*1-$svt_loose);
						$svd_loose=$ticketRow['svd_loose'];
						$svd_packs=$ticketRow['svd'];		
						
					}
					else if($row['log_type']=='initial'){
						$svt_packs=0;
						$svd_packs=0;
						
						$svt_loose=0;
						$svd_loose=0;			
						$svt_loose_in=0;	
						$svd_loose_in=0;	
						
						$trans_type=$row['transaction_type'];
						if($trans_type=="allocation"){
							$ticketSQL="select * from allocation where transaction_id='".$transaction_id."' and type in ('svd','svt')";
							$ticketRS=$db->query($ticketSQL);
							$ticketNM=$ticketRS->num_rows;		
							
							//$ticketSellerId=$ticketsRow['ticket_seller'];
							
							for($i=0;$i<$ticketNM;$i++){			
								$ticketRow=$ticketRS->fetch_assoc();			
								if($i==0){
									$control_id=$ticketRow['control_id'];
									$tsSQL="select * from control_slip where id='".$control_id."'";
									$tsRS=$db->query($tsSQL);
									$tsNM=$tsRS->num_rows;
									$ticketsRow=$tsRS->fetch_assoc();
									
									if($ticketsRow['station']==$logST){
									}
									else {
										$extensionSQL="select * from station where id='".$ticketRow['station']."'";
										$extensionRS=$db->query($extensionSQL);
										$extensionRow=$extensionRS->fetch_assoc();
												
										$suffix=" - ".$extensionRow['station_name'];
											
									}		
									$remarks=$ticketsRow['reference_id'];
									$ticketSellerId=$ticketsRow['ticket_seller'];
									$unitType=$ticketsRow['unit'];					
								
								}


						
								if($ticketRow['type']=='svt'){
									$svt_packs=$ticketRow['initial']*1;
									$svt_loose=$ticketRow['initial_loose']*1;
								
								}
								else if($ticketRow['type']=="svd"){
									
									$svd_packs=$ticketRow['initial']*1;
									$svd_loose=$ticketRow['initial_loose']*1;
								
								}				
							}
						
						}
						else if($trans_type=="remittance"){
							$svt_loose=0;
							$svd_loose=0;			

							$svt_packs=0;
							$svd_packs=0;	
							$svt_loose_in=0;
							$svd_loose_in=0;			
							$looseSQL="select * from control_unsold where transaction_id='".$transaction_id."' and type in ('svd','svt')";
							$looseRS=$db->query($looseSQL);
							$looseNM=$looseRS->num_rows;
						
							for($i=0;$i<$looseNM;$i++){
								$looseRow=$looseRS->fetch_assoc();
								if($i==0){
									$control_id=$looseRow['control_id'];
									$tsSQL="select * from control_slip where id='".$control_id."'";
									$tsRS=$db->query($tsSQL);
									$tsNM=$tsRS->num_rows;
									$ticketsRow=$tsRS->fetch_assoc();
									if($ticketsRow['station']==$logST){
									}
									else {
										$extensionSQL="select * from station where id='".$ticketRow['station']."'";
										$extensionRS=$db->query($extensionSQL);
										$extensionRow=$extensionRS->fetch_assoc();
												
										$suffix=" - ".$extensionRow['station_name'];
											
									}			
									$remarks=$ticketsRow['reference_id'];		
									$ticketSellerId=$ticketsRow['ticket_seller'];
									$unitType=$ticketsRow['unit'];				
								
								}
							
								if($looseRow['type']=='svt'){
									$loose_total=$looseRow['loose_defective']*1;
									$svt_loose=$loose_total;
									$svt_loose_in=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
									$svt_packs=$looseRow['sealed'];
								}
								else if($looseRow['type']=="svd"){
									
									$loose_total=$looseRow['loose_defective']*1;
									$svd_loose_in=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
									$svd_loose=$loose_total;
									$svd_packs=$looseRow['sealed'];
								
								}
							
							}
						}		
					
					
					
					}

					$ticketSellerSQL="select * from ticket_seller where id='".$ticketSellerId."'";		
					$ticketSellerRS=$db->query($ticketSellerSQL);
					$ticketSellerRow=$ticketSellerRS->fetch_assoc();
						
					$verify=$svt_packs+$svt_loose+$svd_packs+$svd_loose+$svt_loose_in+$svd_loose_in;

					if($verify==0){
					}
					else {


				?>
				<tr>
					<td><?php echo $date; ?></td>
					<td>
					<?php
					if($log_type=="initial"){
						if($_SESSION['viewMode']=="login"){
							echo "<a href='#' style='text-decoration:none' onclick='window.open(\"control_slip.php?edit_control=".$control_id."\",\"control slip\",\"height=750, width=800, scrollbars=yes\")'>".strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name']; 
							if($unitType==""){ } else { echo " - ".$unitType; } 
							echo "</a>"; 
						}
						else {
							echo strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'];
							if($unitType==""){ } else { echo " - ".$unitType; } 
							
						}

					}
					else if($log_type=="annex"){
						if($_SESSION['viewMode']=="login"){
							echo "<a href='#' style='text-decoration:none' onclick='window.open(\"ticket_order.php?tID=".$edit_id."\",\"transfer\",\"height=420, width=500, scrollbars=yes\")'>FROM ANNEX</a>"; 
						}
						else {
							echo "FROM ANNEX";
						}
					}
					else if($log_type=="finance"){
						if($_SESSION['viewMode']=="login"){
							echo "<a href='#' style='text-decoration:none' onclick='window.open(\"ticket_order.php?tID=".$edit_id."\",\"transfer\",\"height=420, width=500, scrollbars=yes\")'>FROM FINANCE TRAIN</a>"; 
						}
						else {
							echo "FROM FINANCE TRAIN";
						}

					}

					else {
						if($_SESSION['viewMode']=="login"){
							echo "<a href='#' style='text-decoration:none' onclick='window.open(\"ticket_order.php?tID=".$edit_id."\",\"transfer\",\"height=420, width=500, scrollbars=yes\")'>".strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'].$suffix; 
							if($unitType==""){ } else { echo " - ".$unitType; } echo "</a>"; 
						}
						else {
							echo strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'].$suffix;
							if($unitType==""){ } else { echo " - ".$unitType; }			
						}
					}	
					?></td>
					<td align=center>
					<?php 
						if($type=="deposit"){
							echo "&nbsp;";
						}
						else if($type=="remittance"){
							if($log_type=="cash"){
								echo $ticketSellerRow['id'];
							}
							else {
								if(($log_type=='annex')||($log_type=='finance')){
									echo "&nbsp;";
								}
								else {
									echo $ticketSellerRow['id'];
								
								}
								
							}
							
						}
						else { 
							if(($log_type=='annex')||($log_type=='finance')){
								echo "&nbsp;";
							}
							else {
								echo $ticketSellerRow['id'];
								
							}
						}	
						?>
					</td>		
					
				<?php	
				//First Grid
					if($log_type=="initial"){
						if($type=="remittance"){
				?>
						<td align=right><font color=green>+<?php echo $svt_packs*1; ?></font></td>
						<td align=right><font color=green>+<?php echo $svt_loose_in*1; ?></font>
						<font color=red>(-<?php echo $svt_loose*1; ?>)</font>
						</td>
						<td align=right><font color=green>+<?php echo $svd_packs*1; ?></font></td>
						<td align=right><font color=green>+<?php echo $svd_loose_in*1; ?></font>
						<font color=red>(-<?php echo $svd_loose*1; ?>)</font>
										
						</td>
						
				<?php			
						
						}
						else if($type=="allocation"){
				?>
						<td style='color:red;' align=right>-<?php echo $svt_packs*1; ?></td>
						<td style='color:red;' align=right>-<?php echo $svt_loose*1; ?></td>
						<td style='color:red;' align=right>-<?php echo $svd_packs*1; ?></td>
						<td style='color:red;' align=right>-<?php echo $svd_loose*1; ?></td>
						
						
					<?php		
						}
					
					}
					else if(($log_type=="annex")||($log_type=="finance")){
				?>
						<td style='color:green;' align=right>+<?php echo $svt_packs*1; ?></td>
						<td style='color:green;' align=right>+<?php echo $svt_loose*1; ?></td>
						<td  style='color:green;' align=right>+<?php echo $svd_packs*1; ?></td>
						<td style='color:green;'  align=right>+<?php echo $svd_loose*1; ?></td>		
						
					
				<?php	
					}
					else if($log_type=="ticket"){
				?>		
						<td  style='color:red;' align=right>-<?php echo $svt_packs*1; ?></td>
						<td  style='color:red;' align=right>-<?php echo $svt_loose*1; ?></td>
						<td  style='color:red;' align=right>-<?php echo $svd_packs*1; ?></td>
						<td  style='color:red;' align=right>-<?php echo $svd_loose*1; ?></td>
				<?php	
					}

				?>	

				<?php
				//Total
					if(($log_type=="annex")||($log_type=="finance")){
						$svt_packs_1+=$svt_packs*1;
						$svd_packs_1+=$svd_packs*1;
						$svd_loose_1+=$svd_loose;
						$svt_loose_1+=$svt_loose;
						$total_style="style='color:green;'";

					}
					else if($log_type=="ticket"){

						if($type=="allocation"){
							$svt_packs_1-=$svt_packs*1;
							$svd_packs_1-=$svd_packs*1;
							$svd_loose_1-=$svd_loose*1;
							$svt_loose_1-=$svt_loose*1;
														$total_style="style='color:red;'";

						}
						else if($type=="remittance"){

							$svd_loose_1+=$svd_loose_in-$svd_loose;	

							$svt_loose_1+=$svt_loose_in-$svt_loose;	
							$svt_packs_1+=$svt_packs*1;
							$svd_packs_1+=$svd_packs*1;
							$total_style="style='color:green;'";

						}
						
					}
					else if($log_type=="initial"){
						if($type=="allocation"){
							$svt_packs_1-=$svt_packs*1;
							$svd_packs_1-=$svd_packs*1;
							$svd_loose_1-=$svd_loose*1;
							$svt_loose_1-=$svt_loose*1;
							$total_style="style='color:red;'";

						}
						else if($type=="remittance"){
							$svd_loose_1+=$svd_loose_in-$svd_loose;	
							$svt_loose_1+=$svt_loose_in-$svt_loose;	
							$svt_packs_1+=$svt_packs*1;
							$svd_packs_1+=$svd_packs*1;
							
							$total_style="style='color:green;'";

						}
					
					}
					?>
						<td <?php echo $total_style; ?>   align=right><?php echo $svt_packs_1; ?></td>
						<td <?php echo $total_style; ?>   align=right><?php echo $svt_loose_1; ?></td>
						<td <?php echo $total_style; ?>   align=right><?php echo $svd_packs_1; ?></td>	
						<td <?php echo $total_style; ?>   align=right><?php echo $svd_loose_1; ?></td>
					<?php
					
					
				?>
				<td align=right ><?php echo $remarks; ?> <a href='#' class='delete'  onclick='deleteRecord("<?php echo $transaction_id; ?>","ticket")' >X</a></td>

				</tr>	


				<?php
				}
				 
				}
				?>
<?php
$sqlDefective="select * from physically_defective where log_id='".$log_id."'";
$rsDefective=$db->query($sqlDefective);
$nmDefective=$rsDefective->num_rows;

if($nmDefective>0){
	$rowDefective=$rsDefective->fetch_assoc();
	$verify=$rowDefective['svt']+$rowDefective['svd'];
	if($verify>0){

		$date=date("h:i a",strtotime($rowDefective['date']));
	?>	
		<td><?php echo $date; ?></td>
		<td>Physically Defective</td>
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td style='color:red;' align=right><?php echo $rowDefective['svt']; ?></td>
		<td>&nbsp;</td>
		<td style='color:red;'  align=right><?php echo $rowDefective['svd']; ?></td>	
					
		<?php
			$sjd_loose_1-=$rowDefective['svd'];	
			$sjt_loose_1-=$rowDefective['svt'];			
		?>
		<td style='color:red;' align=right><?php echo $svt_packs_1; ?></td>
		<td style='color:red;' align=right><?php echo $svt_loose_1; ?></td>
		<td style='color:red;' align=right><?php echo $svd_packs_1; ?></td>	
		<td style='color:red;' align=right><?php echo $svd_loose_1; ?></td>
		<td align=right>&nbsp; <a href='#' onclick='deleteRecord("<?php echo $rowDefective['id']; ?>","defective")' >X</a></td>

	<?php	
	}


}



?>
				
				
				
				</tbody>
			</table>
		</div>

	<?php
	require("test_cslip_list.php");
	?>

		
	</div>	
</div>	

	<?php
	require("test_forms2.php");
	?>
