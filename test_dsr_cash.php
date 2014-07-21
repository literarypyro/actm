<?php
session_start();
?>
<?php

$log_id=$_SESSION['log_id'];
?>
<?php
$dsrDate=$_SESSION['log_date'];
$station=$_SESSION['station'];

$stationStamp=$station;
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
        <span class="pageTitle"><span class="icon-screen"></span><a href='dsr_cash.php'>DSR (Original Template)</a></span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='test_cash_logbook.php'>Logbooks</a></span>

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
            <div class="whead"><h6>DSR Cash Section</h6>
                <div class="titleOpt">
					<a href="#" onclick='window.open("generate_dsr.php<?php echo $clause; ?>","_blank")' target='_blank' title='Print' ><span class="icos-printer"></span><span class="clear"></span></a>

                </div>
                <div class="titleOpt">
					
					<a href="#" title='Get Summary' data-toggle="dropdown"><span class="icos-notebook"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                            <li><a href="#" onclick='openSummary("sales")'><span class="icos-chart2"></span>Total Sales</a></li>
                            <li><a href="#"  onclick='openSummary("ticket")'><span class="icos-chart4"></span>Tickets</a></li>
                            <li><a href="#"  onclick='openSummary("cash")'><span class="icos-chart3"></span>Cash</a></li>

					</ul>
					



                </div>

				
			<div class="clear"></div>
			
			</div>
            
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
                <thead>
                    <tr>
						<td rowspan=2>Name of CA</td>
						<td rowspan=2>Name of Ticket Seller</td>
						<td rowspan=2>ID No.</td>
					
                        <td colspan=2>Single Journey</td>
                        <td colspan=2>Discounted SJ</td>
                        <td colspan=2>Stored Value</td>
                        <td colspan=2>Discounted SV</td>
                        
						<td rowspan=2>Fare Adjustment</td>
                        <td rowspan=2>OT Amount</td>
                        <td rowspan=2>Total Amount</td>
						
                    </tr>
					<tr>
						<td>Ticket Sold</td>
						<td>Amount</td>
						<td>Ticket Sold</td>
						<td>Amount</td>
						<td>Ticket Sold</td>
						<td>Amount</td>
						<td>Ticket Sold</td>
						<td>Amount</td>

					</tr>


				</thead>
                <tbody>
				<?php
				$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

				$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
				//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				/*
				$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
				$rsAlt=$db->query($sqlAlt);
				$nmAlt=$rsAlt->num_rows;
				*/
				/*
				if($nmAlt>0){
					$nm++;

				}
				*/
					$grandtotal["sjtSold"]=0;
					
					$grandtotal['sjtAmount']=0; 
						
					$grandtotal['sjdSold']=0;
					$grandtotal['sjdAmount']=0;
						
					$grandtotal['svdSold']=0;
					$grandtotal['svdAmount']=0;
					
					$grandtotal['svtSold']=0;
					$grandtotal['svtAmount']=0;
					$grandtotal['fare_adjustment']=0;
					$grandtotal['ot_amount']=0;
					$grandtotal['totalAmount']=0;

					for($i=0;$i<$nm;$i++){

						$subtotal["sjtSold"]=0;
						
						$subtotal['sjtAmount']=0; 
							
						$subtotal['sjdSold']=0;
						$subtotal['sjdAmount']=0;
							
						$subtotal['svdSold']=0;
						$subtotal['svdAmount']=0;
						
						$subtotal['svtSold']=0;
						$subtotal['svtAmount']=0;
						$subtotal['fare_adjustment']=0;
						$subtotal['ot_amount']=0;
						$subtotal['totalAmount']=0;

						

						$row=$rs->fetch_assoc();
						$log_id=$row['id'];

						$sql2="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller,unit";
						
						$rs2=$db->query($sql2);
						$nm2=$rs2->num_rows;
						
						$cash_assistSQL="select * from login where username='".$row['cash_assistant']."'";

						$cash_assistRS=$db->query($cash_assistSQL);
						$cash_assistRow=$cash_assistRS->fetch_assoc();
						
						$cash_assistant=$cash_assistRow['lastName'].", ".$cash_assistRow['firstName'];
						
						if($nm2>0){	
						?>
							<tr class='grid'>
							<td rowspan='<?php echo $nm2; ?>'><?php echo $cash_assistant; ?></td>
							<?php
							for($k=0;$k<$nm2;$k++){
								$row2=$rs2->fetch_assoc();
								
								$totalAmount=0;
								$unit=$row2['unit'];
								
								$remit_id=$row2['remit_id'];
								
								$ticketsellerSQL="select * from ticket_seller where id='".$row2['remit_ticket_seller']."'";	
								$ticketsellerRS=$db->query($ticketsellerSQL);
								$ticketsellerRow=$ticketsellerRS->fetch_assoc();
								$ticket_seller=$ticketsellerRow['last_name'].", ".$ticketsellerRow['first_name'];
								$ticket_id=$ticketsellerRow['id'];
								
								$allocationSQL="select * from control_sold inner join control_remittance on control_sold.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
								$allocationRS=$db->query($allocationSQL);
								$allocationNM=$allocationRS->num_rows;
								
								$sjtSold="&nbsp;";
								$sjdSold="&nbsp;";
								$svtSold="&nbsp;";
								$svdSold="&nbsp;";
								
								for($m=0;$m<$allocationNM;$m++){
									$allocationRow=$allocationRS->fetch_assoc();
									$sjtSold+=$allocationRow['sjt'];
									$sjdSold+=$allocationRow['sjd'];
									$svtSold+=$allocationRow['svt'];
									$svdSold+=$allocationRow['svd'];

								
								
								}
								

								$adjustmentSQL="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";

								$adjustmentRS=$db->query($adjustmentSQL);
								$adjustmentNM=$adjustmentRS->num_rows;
								$sjtAmount=0;
								$svtAmount=0;
								$sjdAmount=0;
								$svdAmount=0;
								
								
								for($n=0;$n<$adjustmentNM;$n++){
									$adjustmentRow=$adjustmentRS->fetch_assoc();
									$sjtAmount+=$adjustmentRow['sjt']*1;
									$sjdAmount+=$adjustmentRow['sjd']*1;
									$svtAmount+=$adjustmentRow['svt']*1;
									$svdAmount+=$adjustmentRow['svd']*1;
									
								}

								$totalAmount+=$sjtAmount;
								$totalAmount+=$sjdAmount;
								$totalAmount+=$svtAmount;
								$totalAmount+=$svdAmount;
								
								$ot_amount=0;
								$fare_adjustment=0;
								
								$fareSQL="select * from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";

								$fareRS=$db->query($fareSQL);
								$fareNM=$fareRS->num_rows;
								$ot_amount=0;	
								for($n=0;$n<$fareNM;$n++){
									$fareRow=$fareRS->fetch_assoc();
									$fare_adjustment+=$fareRow['sjt']+$fareRow['sjd']+$fareRow['svt']+$fareRow['svd']+$fareRow['c'];
									$ot_amount+=$fareRow['ot'];
								}
								$totalAmount+=$fare_adjustment;
							/*
								$otSQL="select * from control_cash where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."')";
								$otRS=$db->query($otSQL);
								$otNM=$otRS->num_rows;
								

								for($n=0;$n<$otNM;$n++){
									$otRow=$otRS->fetch_assoc();
									$ot_amount+=$otRow['ot']*1;
								
								}
							*/	
								$totalAmount+=$ot_amount;


								$subtotal["sjtSold"]+=$sjtSold;
								
								$subtotal['sjtAmount']+=$sjtAmount; 
									
								$subtotal['sjdSold']+=$sjdSold;
								$subtotal['sjdAmount']+=$sjdAmount;
									
								$subtotal['svdSold']+=$svdSold;
								$subtotal['svdAmount']+=$svdAmount;
								
								$subtotal['svtSold']+=$svtSold;
								$subtotal['svtAmount']+=$svtAmount;
								$subtotal['fare_adjustment']+=$fare_adjustment;
								$subtotal['ot_amount']+=$ot_amount;
								$subtotal['totalAmount']+=$totalAmount;


								
								if($k==0){
							?>	
								<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
								<td><?php echo $ticket_id; ?></td>

								<td align=right><?php echo number_format($sjtSold*1,0); ?></td>
								<td align=right><?php echo number_format($sjtAmount*1,2); ?></td>
								
								<td align=right><?php echo number_format($sjdSold*1,0); ?></td>
								<td align=right><?php echo number_format($sjdAmount*1,2); ?></td>
								
								<td align=right><?php echo number_format($svdSold*1,0); ?></td>
								<td align=right><?php echo number_format($svdAmount*1,2); ?></td>
								
								<td align=right><?php echo number_format($svtSold*1,0); ?></td>
								<td align=right><?php echo number_format($svtAmount*1,2); ?></td>

								<td align=right><?php echo number_format($fare_adjustment*1,2); ?></td>
								<td align=right><?php echo number_format($ot_amount*1,2); ?></td>

								<td align=right><?php echo number_format($totalAmount*1,2); ?></td>
								<td><a href='#' onclick="deleteRow('<?php echo $remit_id; ?>','<?php echo $_GET['ext']; ?>')">X</a></td>
							</tr>
					<?php	
						}
						else {
					?>	
						<tr class='grid'>
							<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
							<td><?php echo $ticket_id; ?></td>		
							
							<td align=right><?php echo number_format($sjtSold*1,0); ?></td>
							<td align=right><?php echo number_format($sjtAmount*1,2); ?></td>
							
							<td align=right><?php echo number_format($sjdSold*1,0); ?></td>
							<td align=right><?php echo number_format($sjdAmount*1,2); ?></td>
							
							<td align=right><?php echo number_format($svdSold*1,0); ?></td>
							<td align=right><?php echo number_format($svdAmount*1,2); ?></td>
							
							<td align=right><?php echo number_format($svtSold*1,0); ?></td>
							<td align=right><?php echo number_format($svtAmount*1,2); ?></td>

							<td align=right><?php echo number_format($fare_adjustment*1,2); ?></td>
							<td align=right><?php echo number_format($ot_amount*1,2); ?></td>

							<td align=right><?php echo number_format($totalAmount*1,2); ?></td>

							
							
							<td><a href='#' onclick="deleteRow('<?php echo $remit_id; ?>','<?php echo $_GET['ext']; ?>')">X</a></td>
							
						</tr>
							
						
					<?php	
						}

				}
				}
if($nm2>0){	
	$grandtotal["sjtSold"]+=$subtotal["sjtSold"];
	
	$grandtotal['sjtAmount']+=$subtotal['sjtAmount']; 
		
	$grandtotal['sjdSold']+=$subtotal['sjdSold'];
	$grandtotal['sjdAmount']+=$subtotal['sjdAmount'];
		
	$grandtotal['svdSold']+=$subtotal['svdSold'];
	$grandtotal['svdAmount']+=$subtotal['svdAmount'];
	
	$grandtotal['svtSold']+=$subtotal['svtSold'];
	$grandtotal['svtAmount']+=$subtotal['svtAmount'];
	$grandtotal['fare_adjustment']+=$subtotal['fare_adjustment'];
	$grandtotal['ot_amount']+=$subtotal['ot_amount'];
	$grandtotal['totalAmount']+=$subtotal['totalAmount'];

?>
	<tr class='subheader'>
		<td colspan=3 align=center>Subtotal</th>
		<td align=right><font><?php echo number_format($subtotal["sjtSold"]*1,0); ?></font></td>
	
		<td align=right><font><?php echo	number_format($subtotal['sjtAmount']*1,2); ?></font></td> 
		
		<td align=right><font><?php echo	number_format($subtotal['sjdSold']*1,0); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['sjdAmount']*1,2); ?></font></td>
		
		<td align=right><font><?php echo	number_format($subtotal['svdSold']*1,0); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['svdAmount']*1,2); ?></font></td>
	
		<td align=right><font><?php echo	number_format($subtotal['svtSold']*1,0); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['svtAmount']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['fare_adjustment']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['ot_amount']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['totalAmount']*1,2); ?></font></td>		
		<td>&nbsp;</td>
		
	</tr>	
<?php
}
}
?>
	<tr  class='header'>
		<td colspan=3  align=center>Grand Total</td>
		<td align=right><font><?php echo number_format($grandtotal["sjtSold"]*1,0); ?></font></td>
	
		<td align=right><font><?php echo number_format($grandtotal['sjtAmount']*1,2); ?></font></td> 
		
		<td align=right><font><?php echo number_format($grandtotal['sjdSold']*1,0); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['sjdAmount']*1,2); ?></font></td>
		
		<td align=right><font><?php echo number_format($grandtotal['svdSold']*1,0); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['svdAmount']*1,2); ?></font></td>
	
		<td align=right><font><?php echo number_format($grandtotal['svtSold']*1,0); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['svtAmount']*1,2); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['fare_adjustment']*1,2); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['ot_amount']*1,2); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['totalAmount']*1,2); ?></font></td>		
		<td>&nbsp;</td>
		
	</tr>
								
						
					
			</tbody>

            </table>
        </div>


	</div>
	
</div>	


<?php 
require("test_dsr_summary.php");

?>						
						
						
						
						
<!--
						
						
						
						<div id="customDialog" class="customDialog" title="Dialog with other custom elements">
                            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
                                <thead>
                                    <tr>
                                        <td width="50">Image</td>
                                        <td class="sortCol"><div>Description<span></span></div></td>
                                        <td width="130" class="sortCol"><div>Date<span></span></div></td>
                                        <td width="120">File info</td>
                                        <td width="100">Actions</td>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <div class="itemActions">
                                                <label>Apply action:</label>
                                                <select>
                                                    <option value="">Select action...</option>
                                                    <option value="Edit">Edit</option>
                                                    <option value="Delete">Delete</option>
                                                    <option value="Move">Move somewhere</option>
                                                </select>
                                            </div>
                                            <div class="tPages">
                                                <ul class="pages">
                                                    <li class="prev"><a href="#" title=""><span class="icon-arrow-14"></span></a></li>
                                                    <li><a href="#" title="" class="active">1</a></li>
                                                    <li><a href="#" title="">2</a></li>
                                                    <li><a href="#" title="">3</a></li>
                                                    <li><a href="#" title="">4</a></li>
                                                    <li><a href="#" title="">5</a></li>
                                                    <li><a href="#" title="">6</a></li>
                                                    <li>...</li>
                                                    <li><a href="#" title="">20</a></li>
                                                    <li class="next"><a href="#" title=""><span class="icon-arrow-17"></span></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td><a href="images/big.png" title="" class="lightbox"><img src="images/live/face1.png" alt="" /></a></td>
                                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                                        <td>Feb 12, 2012. 12:28</td>
                                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                                        <td>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="images/big.png" title="" class="lightbox"><img src="images/live/face1.png" alt="" /></a></td>
                                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                                        <td>Feb 12, 2012. 12:28</td>
                                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                                        <td>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="images/big.png" title="" class="lightbox"><img src="images/live/face1.png" alt="" /></a></td>
                                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                                        <td>Feb 12, 2012. 12:28</td>
                                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                                        <td>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="images/big.png" title="" class="lightbox"><img src="images/live/face1.png" alt="" /></a></td>
                                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                                        <td>Feb 12, 2012. 12:28</td>
                                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                                        <td>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="images/big.png" title="" class="lightbox"><img src="images/live/face1.png" alt="" /></a></td>
                                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                                        <td>Feb 12, 2012. 12:28</td>
                                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                                        <td>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>



-->