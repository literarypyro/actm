<?php
session_start();
?>
<?php

$log_id=$_SESSION['log_id'];
?>
<?php
$start = microtime(true);

$dsrDate=$_SESSION['log_date'];
$station=$_SESSION['station'];

$stationStamp=$station;
?>
<?php
function discrepCheck($discrepancy){

	$label="";


	if($discrepancy>=0){
		$label=$discrepancy; 
	}
	else {
		$label="(".($discrepancy*-1).")";
	}

	return $label;
	
	
	
	
	
	
}

$clause="";

if(isset($_GET['ext'])){
	$clause="?ext=Y";
}

?>
<?php
$db=new mysqli("localhost","root","","finance");
?>



<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

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

<div class='content'>
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-screen"></span>Detailed Sales Report</span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='dsr_tickets_b.php'>DSR (Original Template)</a></span>

        <ul class="quickStats">
            <li>
                <a href="test_dsr_cash.php" class="blueImg"><img src="images/icons/quickstats/money.png" alt="" /></a>
                <div class="floatR"><strong class="blue">DSR Cash</strong></div>
            </li>
            <li>
                <a href="test_dsr_tickets_a.php" class="redImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">DSR Tickets A</strong></div>
            </li>
            <li>
                <a href="test_dsr_tickets_b.php" class="greenImg"><img src="images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">DSR Tickets B</strong></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>


		
    <div class="wrapper">
	        <div class="widget">
            <div class="whead"><h6>DSR Tickets B</h6>
                <div class="titleOpt">
					
					<a href="#" title='Add Transaction' data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                            <li><a href="#" onclick='window.open("test_ctf.php","transfer","height=800, width=550, scrollbars=yes")'><span class="icos-add"></span>Cash Transfer Form</a></li>
                            <li><a href="#"  onclick='window.open("test_pnb_deposit.php","deposit","height=550, width=550")'><span class="icos-add"></span>PNB Deposit</a></li>
					</ul>
					
                </div>
                <div class="titleOpt">
					<a href="generateCashLogbook.php" target='_blank' title='Print' ><span class="icos-printer"></span><span class="clear"></span></a>

                </div>
				
			<div class="clear"></div>
			
			</div>
            
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
                <thead>
                    <tr>
						<td rowspan=2>Name of CA</td>
						<td rowspan=2>Name of Ticket Seller</td>
						<td rowspan=2>ID No.</td>
					
                        <td colspan=4>Remitted (Loose)</td>
                        <td colspan=4>Over(Lacking)</td>
                        <td colspan=4>Defective Ticket</td>
						
                    </tr>
					<tr>
						<td>SJ</td>
						<td>DSJ</td>
						<td>SV</td>
						<td>DSV</td>

						<td>SJ</td>
						<td>DSJ</td>
						<td>SV</td>
						<td>DSV</td>

						<td>SJ</td>
						<td>DSJ</td>
						<td>SV</td>
						<td>DSV</td>

					</tr>


				</thead>
                <tbody>
				
				<?php
				$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

				$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
					$log_id=$row['id'];
					
					$sql2="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller,unit";


					$rs2=$db->query($sql2);
					
					
					$nm2=$rs2->num_rows;
					
					$cash_assistSQL="select * from login where username='".$row['cash_assistant']."'";
					$cash_assistRS=$db->query($cash_assistSQL);
					$cash_assistRow=$cash_assistRS->fetch_assoc();
					
					$cash_assistant=$cash_assistRow['lastName'].", ".$cash_assistRow['firstName'];
					
					$subtotal['sj_unreg']=0; 
					$subtotal['sv_unreg']=0; 
					$subtotal['sj_discount']=0; 
					$subtotal['sv_discount']=0; 
					$subtotal['sj_refund']=0; 
					$subtotal['sv_refund']=0; 

					$subtotal['sj_r_amount']=0; 
					$subtotal['sv_r_amount']=0; 

					$subtotal['overage']=0; 

					$subtotal['paid_shortage']=0; 
					$subtotal['unpaid_shortage']=0; 

					$subtotal['sjtLoose']=0; 
					$subtotal['sjdLoose']=0; 
					$subtotal['svdLoose']=0; 
					$subtotal['svtLoose']=0; 
							
					$subtotal['sjt_label']=0; 
					$subtotal['sjd_label']=0; 
					$subtotal['svt_label']=0; 
					$subtotal['svd_label']=0; 
					
					$subtotal['sjt_discrepancy']=0; 
					$subtotal['sjd_discrepancy']=0; 
					$subtotal['svt_discrepancy']=0; 
					$subtotal['svd_discrepancy']=0; 
					
					

					$subtotal['sjtDefective']=0; 
					$subtotal['sjdDefective']=0; 
					$subtotal['svdDefective']=0; 
					$subtotal['svtDefective']=0; 
					
					
					
					$subtotal['sjt_discrepancy']=0; 
					$subtotal['sjd_discrepancy']=0; 
					$subtotal['svt_discrepancy']=0; 
					$subtotal['svd_discrepancy']=0; 	
					
					
					
				if($nm2>0){	
				?>
				<tr class='grid'>
				<td rowspan='<?php echo $nm2*1; ?>'><?php echo $cash_assistant; ?></td>
				<?php
				for($k=0;$k<$nm2;$k++){
					$start_cycle = microtime(true);

					$row2=$rs2->fetch_assoc();

					$remit_id=$row2['remit_id'];


					
					$unit=$row2['unit'];
					
					$ticketsellerSQL="select * from ticket_seller where id='".$row2['remit_ticket_seller']."'";	
					$ticketsellerRS=$db->query($ticketsellerSQL);
					$ticketsellerRow=$ticketsellerRS->fetch_assoc();
					$ticket_seller=$ticketsellerRow['last_name'].", ".$ticketsellerRow['first_name'];
					$ticket_id=$ticketsellerRow['id'];
					
					$unsoldSQL="select type,sum(loose_good) as ticket_sum,sum(loose_defective) as ticket_sum2 from control_unsold where control_id='".$row2['control_id']."' group by type";
					$unsoldRS=$db->query($unsoldSQL);

					$unsoldRS=$db->query($unsoldSQL);
					$unsoldNM=$unsoldRS->num_rows;
					
					$sjtLoose=0;
					$sjdLoose=0;
					$svtLoose=0;
					$svdLoose=0;
					
					$sjtDefective=0;
					$sjdDefective=0;
					$svtDefective=0;
					$svdDefective=0;


					for($m=0;$m<$unsoldNM;$m++){
						$unsoldRow=$unsoldRS->fetch_assoc();
						if($unsoldRow['type']=='sjt'){
							$sjtLoose=$unsoldRow['ticket_sum'];	
							$sjtDefective=$unsoldRow['ticket_sum2'];

						
						}
						else if($unsoldRow['type']=='sjd'){
							$sjdLoose=$unsoldRow['ticket_sum'];	
							$sjdDefective=$unsoldRow['ticket_sum2'];

							
						}
						else if($unsoldRow['type']=='svt'){
							$svtLoose=$unsoldRow['ticket_sum'];	
							$svtDefective=$unsoldRow['ticket_sum2'];
							
						}
						else if($unsoldRow['type']=='svd'){
							$svdLoose=$unsoldRow['ticket_sum'];	
							$svdDefective=$unsoldRow['ticket_sum2'];
							
						}
					
					
					}
					
					$sjt_physically_defective=0;
					$sjd_physically_defective=0;
					$svt_physically_defective=0;
					$svd_physically_defective=0;
					
					
					$asql2="select sjt,svt,sjd,svd from physically_defective where log_id='".$log_id."' and station='".$stationStamp."'";

					$ars2=$db->query($asql2);
					$anm2=$ars2->num_rows;	
					if($anm2>0){
						$arow2=$ars2->fetch_assoc();
						$sjt_physically_defective=$arow2['sjt'];
						$sjd_physically_defective=$arow2['sjd'];
						$svt_physically_defective=$arow2['svt'];
						$svd_physically_defective=$arow2['svd'];
					}


					$discrepancyTicketSQL="select ticket_type,type,sum(amount) as new_amount from discrepancy_ticket inner join control_remittance on transaction_id=control_id where remit_log='".$log_id."' and station='".$stationStamp."' and control_remittance.ticket_seller='".$row2['remit_ticket_seller']."' group by ticket_type";
					$discrepancyTicketRS=$db->query($discrepancyTicketSQL);
				 //   $end_query = (microtime(true) - $start_query);
				//	echo "Loaded in ".$end_query." seconds<br>";	
				//	$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket where transaction_id in (select control_slip.id from control_slip inner join remittance on control_slip.id=remittance.control_id where remittance.remit='".$log_id."' and control_slip.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."' and control_slip.unit='".$unit."') group by ticket_type";
					//echo $discrepancyTicketSQL."<br>";
					//	echo $control;	

				//	$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket inner join control_slip on discrepancy_ticket.transaction_id=concat('control_',control_slip.id) where discrepancy_ticket.log_id='".$log_id."' and discrepancy_ticket.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."' group by ticket_type";

					$discrepancyTicketNM=$discrepancyTicketRS->num_rows;
					
				//	$paid_shortage=0;
					$sjt_label="0";
					$sjd_label="0";
					$svt_label="0";
					$svd_label="0";

					$sjt_discrepancy=0;	
					$sjd_discrepancy=0;	
					$svt_discrepancy=0;	
					$svd_discrepancy=0;	
					



					
					if($discrepancyTicketNM>0){
					
						for($n=0;$n<$discrepancyTicketNM;$n++){
							$discrepRow=$discrepancyTicketRS->fetch_assoc();	
							if($discrepRow['ticket_type']=="sjt"){
								$sjt_discrepancy=$discrepRow['new_amount'];
						
								if($discrepRow['type']=="shortage"){
									$sjt_label="(".$sjt_discrepancy.")";
									$subtotal['sjt_discrepancy']-=$sjt_discrepancy;
								}
								else if($discrepRow['type']=="overage"){
									$sjt_label=$sjt_discrepancy;
									$subtotal['sjt_discrepancy']+=$sjt_discrepancy;

								}
							
							
							}
							else if($discrepRow['ticket_type']=="sjd"){
								$sjd_discrepancy=$discrepRow['new_amount'];
							
								if($discrepRow['type']=="shortage"){
									$sjd_label="(".$sjd_discrepancy.")";
									$subtotal['sjd_discrepancy']-=$sjd_discrepancy;
								
								}
								else if($discrepRow['type']=="overage"){
									$sjd_label=$sjd_discrepancy;
									$subtotal['sjd_discrepancy']+=$sjd_discrepancy;
									
								}
							
							
							}
							else if($discrepRow['ticket_type']=="svt"){
								$svt_discrepancy=$discrepRow['new_amount'];
							
								if($discrepRow['type']=="shortage"){
									$svt_label="(".$svt_discrepancy.")";
									$subtotal['svt_discrepancy']-=$svt_discrepancy;
								
								}
								else if($discrepRow['type']=="overage"){
									$svt_label=$svt_discrepancy;
									$subtotal['svt_discrepancy']+=$svt_discrepancy;

								}
							
							
							}
							else if($discrepRow['ticket_type']=="svd"){
								$svd_discrepancy=$discrepRow['new_amount'];
							
								if($discrepRow['type']=="shortage"){
									$svd_label="(".$svd_discrepancy.")";
									$subtotal['svd_discrepancy']-=$svd_discrepancy;
								
								}
								else if($discrepRow['type']=="overage"){
									$svd_label=$svd_discrepancy;
									$subtotal['svd_discrepancy']+=$svd_discrepancy;

								}
							
							
							}
							

						
						}
						
					
						
						
					//	$discrepancyRow=$discrepancyRS->fetch_assoc();
					//	$paid_shortage+=$discrepancyRow['shortage'];
					
					}
					
				//	$end_process = (microtime(true) - $start_process);
				//	echo "Loaded Process in ".$end_process." seconds<br>";	
					//	$overage-=$sj_unreg+$sv_unreg;
					$overage=$overage;
				//		$start_process1 = microtime(true);	
				//		echo "<br>Subtotals<br>";
						

					$subtotal['sjtLoose']+=$sjtLoose; 
					$subtotal['sjdLoose']+=$sjdLoose; 
					$subtotal['svdLoose']+=$svdLoose; 
					$subtotal['svtLoose']+=$svtLoose; 
							

					$subtotal['sjtDefective']+=$sjtDefective; 
					$subtotal['sjdDefective']+=$sjdDefective; 
					$subtotal['svdDefective']+=$svdDefective; 
					$subtotal['svtDefective']+=$svtDefective; 	
					
					
					if($k==0){

					
					}
					else {
						echo "<tr class='grid'>";
					}

				?>
						<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
						<td><?php echo $ticket_id; ?></td>	


						<td align=right><?php echo $sjtLoose; ?></td>
						<td align=right><?php echo $sjdLoose; ?></td>
						<td align=right><?php echo $svdLoose; ?></td>
						<td align=right><?php echo $svtLoose; ?></td>		

						<td align=right><?php echo $sjt_label; ?></td>
						<td align=right><?php echo $sjd_label; ?></td>
						<td align=right><?php echo $svd_label; ?></td>
						<td align=right><?php echo $svt_label; ?></td>

						<td align=right><?php echo $sjtDefective; ?></td>
						<td align=right><?php echo $sjdDefective; ?></td>
						<td align=right><?php echo $svdDefective; ?></td>
						<td align=right><?php echo $svtDefective; ?></td>		
						<td><a href='#' onclick="deleteRow('<?php echo $remit_id; ?>','<?php echo $_GET['ext']; ?>')">X</a></td>		
					</tr>
					
				<?php	
					
					


					
				//	$end_cycle=microtime(true)-$start_cycle;
				//	echo "<font color=red><b>Cycle lasted for ".$end_cycle." seconds</b></font><br>";
					
					
					
					}
				?>

				<?php	
						
						$subtotal['sjt_label']=discrepCheck($subtotal['sjt_discrepancy']);
						
						$subtotal['svt_label']=discrepCheck($subtotal['svt_discrepancy']);
						$subtotal['sjd_label']=discrepCheck($subtotal['sjd_discrepancy']);
						$subtotal['svd_label']=discrepCheck($subtotal['svd_discrepancy']);	

				}
				//	$start_cycle = microtime(true);
				if($nm2>0){


					$grandtotal['sjtLoose']+=$subtotal['sjtLoose']; 
					$grandtotal['sjdLoose']+=$subtotal['sjdLoose']; 
					$grandtotal['svdLoose']+=$subtotal['svdLoose']; 
					$grandtotal['svtLoose']+=$subtotal['svtLoose']; 
					
					$grandtotal['sjt_discrepancy']+=$subtotal['sjt_discrepancy'];
					$grandtotal['sjd_discrepancy']+=$subtotal['sjd_discrepancy'];
					$grandtotal['svt_discrepancy']+=$subtotal['svt_discrepancy'];
					$grandtotal['svd_discrepancy']+=$subtotal['svd_discrepancy'];
					
				/*	$grandtotal['sjt_label']+=$subtotal['sjt_label']; 
					$grandtotal['sjd_label']+=$subtotal['sjd_label']; 
					$grandtotal['svt_label']+=$subtotal['svt_label']; 
					$grandtotal['svd_label']+=$subtotal['svd_label']; 
				*/
					$grandtotal['sjtDefective']+=$subtotal['sjtDefective']; 
					$grandtotal['sjdDefective']+=$subtotal['sjdDefective']; 
					$grandtotal['svdDefective']+=$subtotal['svdDefective']; 
					$grandtotal['svtDefective']+=$subtotal['svtDefective']; 

				?>
				<tr class='subheader'>
				<td align=center colspan=3>Subtotal</td>


					<td align=right><font><?php echo $subtotal['sjtLoose']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['sjdLoose']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['svdLoose']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['svtLoose']; ?></font></td> 
							
					<td align=right><font><?php echo $subtotal['sjt_label']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['sjd_label']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['svd_label']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['svt_label']; ?></font></td> 

					<td align=right><font><?php echo $subtotal['sjtDefective']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['sjdDefective']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['svdDefective']; ?></font></td> 
					<td align=right><font><?php echo $subtotal['svtDefective']; ?></font></td> 
					<td>&nbsp;</td>
					
					</tr>
				<?php
				}
				}


					if($grandtotal['sjt_discrepancy']>=0){
						$grandtotal['sjt_label']=$grandtotal['sjt_discrepancy']; 
					}
					else {
						$grandtotal['sjt_label']="(".($grandtotal['sjt_discrepancy']*-1).")";
					}
					
					if($grandtotal['sjd_discrepancy']>=0){
						$grandtotal['sjd_label']=$grandtotal['sjd_discrepancy']; 
					}
					else {
						$grandtotal['sjd_label']="(".($grandtotal['sjd_discrepancy']*-1).")";
					}
					
					if($grandtotal['svt_discrepancy']>=0){
						$grandtotal['svt_label']=$grandtotal['svt_discrepancy']; 
					}
					else {
						$grandtotal['svt_label']="(".($grandtotal['svt_discrepancy']*-1).")";
					}
					
					if($grandtotal['svd_discrepancy']>=0){
						$grandtotal['svd_label']=$grandtotal['svd_discrepancy']; 
					}
					else {
						$grandtotal['svd_label']="(".($grandtotal['svd_discrepancy']*-1).")";
					}
					


				?>
				<tr class='header'>
				<td colspan=3 align=center>Grand Total</td>


					<td align=right><font><?php echo $grandtotal['sjtLoose']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['sjdLoose']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['svdLoose']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['svtLoose']; ?></font></td> 
							
					<td align=right><font><?php echo $grandtotal['sjt_label']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['sjd_label']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['svd_label']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['svt_label']; ?></font></td> 

					<td align=right><font><?php echo $grandtotal['sjtDefective']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['sjdDefective']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['svdDefective']; ?></font></td> 
					<td align=right><font><?php echo $grandtotal['svtDefective']; ?></font></td> 
					<td>&nbsp;</td>	
					
					</tr>

				
				
				
				
				
				</tbody>

            </table>
        </div>


	</div>
	
</div>	