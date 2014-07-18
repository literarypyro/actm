<?php
session_start();
?>
<?php

$log_id=$_SESSION['log_id'];
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
        <span class="pageTitle"><span class="icon-screen"></span>Logbooks</span>
        <span class="pageTitle"><span class="icon-screen"></span><a href='cash_logbook.php'>Logbooks (Original Template)</a></span>

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


		
    <div class="wrapper">
	        <div class="widget">
            <div class="whead"><h6>Cash Logbook</h6>
                <div class="titleOpt">
					
					<a href="#" title='Add Transaction' data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                            <li><a href="#" onclick='window.open("test_ctf.php","transfer","height=800, width=600, scrollbars=yes")'><span class="icos-add"></span>Cash Transfer Form</a></li>
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
                        <th colspan=3 style='text-align:center;'>Particulars</th>
                        <th colspan=4 style='text-align:center;'>Cash In/Out</th>
                        <th rowspan=2  style='text-align:center;' valign=bottom>Shortage/Overage</th>
                        <th colspan=3 style='text-align:center;'>Balance</th>
                        <th rowspan=2  style='text-align:center;' valign=bottom>Remarks</th>
                    </tr>
                    <tr>
						<td>Time</td>
						<td>Name</td>
						<td>ID No.</td>
					
                        <td>Revolving Fund</td>
                        <td>For Deposit/Net Revenue</td>
                        <td>PNB Deposit</td>
                        <td>Total</td>
                        
						<td>Revolving Fund</td>
                        <td>For Deposit/Net Revenue</td>
                        <td>Total</td>
						
                    </tr>



				</thead>
                <tbody>
				<?php
				$count=0;
				$station=$_SESSION['station'];

				$db=new mysqli("localhost","root","","finance");

				$sql="select * from beginning_balance_cash where log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
				$row=$rs->fetch_assoc();
					$revolvingTotal=$row['revolving_fund'];
					$depositTotal=$row['for_deposit'];
					$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);
				}
				else {

				$alternate="SELECT * FROM beginning_balance_cash inner join logbook on beginning_balance_cash.log_id=logbook.id and station='".$station."' order by date desc,field(revenue,'close','open'),field(shift,2,1,3)";

				$rs2=$db->query($alternate);
				$row=$rs2->fetch_assoc();
					$revolvingTotal=$row['revolving_fund'];
					$depositTotal=$row['for_deposit'];
					$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);
					
					$insert="insert into beginning_balance_cash(log_id,revolving_fund,for_deposit) values ('".$log_id."','".$revolvingTotal."','".$depositTotal."')";
					$insertRS=$db->query($insert);	

				}	
				?>
				<tr>
					<td colspan=3>Beginning Balance <a href='#' style='text-decoration:none' onclick='window.open("beginning data entry.php?loID=<?php echo $log_id; ?>&type=cash","beginning","height=300, width=300")' >[Data Entry]</a></td>

					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>

					<td align=right><?php echo number_format($row['revolving_fund']*1,2); ?></td>
					<td align=right><?php echo number_format($row['for_deposit']*1,2); ?></td>
					<td align=right><?php echo number_format(($row['for_deposit']*1)+($row['revolving_fund']*1),2); ?></td>
					<td>&nbsp;</td>

				</tr>

				<?php

				$db=new mysqli("localhost","root","","finance");
				$sql="select * from transaction where log_id='".$log_id."' and log_type in ('cash') and transaction_type not in ('catransfer') order by id*1";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				for($i=0;$i<$nm;$i++){
					$cash_asst="";
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
					if($type=="shortage"){
						$type="remittance";
						$log_type="shortage";


					}
				/*
					else {
						echo $transaction_id."remit";
					
					}
					*/
					$suffix="";
					if($type=="deposit"){
						$cashSQL="select * from pnb_deposit where transaction_id='".$transaction_id."'";

						$cashRS=$db->query($cashSQL);
						
						$cashRow=$cashRS->fetch_assoc();	
						$deposit_type=$cashRow['type'];
						
					}
					else {
					
						if($type=="remittance"){

							if(($log_type=="cash")||($log_type=="shortage")){
								$cashSQL="select * from cash_transfer where transaction_id='".$transaction_id."'";
								$cashRS=$db->query($cashSQL);
								
								$cashRow=$cashRS->fetch_assoc();
								
								if($cashRow['station']==$logST){
								}
								else {
									if($cashRow['station']=="annex"){
									}
									else {
									$extensionSQL="select * from station where id='".$cashRow['station']."'";
									$extensionRS=$db->query($extensionSQL);
									$extensionRow=$extensionRS->fetch_assoc();
									
									$suffix=" - ".$extensionRow['station_name'];
									}
								}
								$cashStation=$cashRow['station'];
								
								$cash_assistantSQL="select * from login where username='".$cashRow['cash_assistant']."'";
								$cash_assistantRS=$db->query($cash_assistantSQL);
								$cash_assistantRow=$cash_assistantRS->fetch_assoc();
								
								$cash_asst=$cash_assistantRow['lastName'].", ".$cash_assistantRow['firstName'];
								
								
								$ticketSellerSQL="select * from ticket_seller where id='".$cashRow['ticket_seller']."'";		

								$ticketRS=$db->query($ticketSellerSQL);
								$ticketRow=$ticketRS->fetch_assoc();
								
								$revolving=$cashRow['total'];
								$deposit=$cashRow['net_revenue'];
								$total=$revolving*1+$deposit*1;
							}

							/*
							else if($log_type=="control"){
								$control="select * from cash_remittance where control_transaction_id='".$transaction_id."'";
								$controlRS=$db->query($control);
								$controlRow=$controlRS->fetch_assoc();
								
								$ticketSellerSQL="select * from ticket_seller where id='".$controlRow['ticket_seller']."'";		

								$ticketRS=$db->query($ticketSellerSQL);
								$ticketRow=$ticketRS->fetch_assoc();
								
								$revolving=0;
								$deposit=$controlRow['control_remittance'];
								$total=$revolving*1+$deposit*1;
								
							
							}
							*/
						}
						else if($type=="allocation"){
						
							$cashSQL="select * from cash_transfer where transaction_id='".$transaction_id."'";

							$cashRS=$db->query($cashSQL);
							
							$cashRow=$cashRS->fetch_assoc();
							
								if($cashRow['station']==$logST){
								}
								else {
									if($cashRow['station']=="annex"){
									}
									else {
									$extensionSQL="select * from station where id='".$cashRow['station']."'";
									$extensionRS=$db->query($extensionSQL);
									$extensionRow=$extensionRS->fetch_assoc();
									
									$suffix=" - ".$extensionRow['station_name'];
									}
								}
							
							$cashStation=$cashRow['station'];	
							
							$ticketSellerSQL="select * from ticket_seller where id='".$cashRow['ticket_seller']."'";		

							$ticketRS=$db->query($ticketSellerSQL);
							$ticketRow=$ticketRS->fetch_assoc();
							
							$revolving=$cashRow['total'];
							$deposit=$cashRow['net_revenue'];
							$total=$revolving*1+$deposit*1;
						
						}
						
					}
				
				?>	
					<?php 
					$style="";

					
					
					$sql3="select * from cash_remittance where ticket_seller='".$ticketRow['id']."' order by id desc";
					
					$rs3=$db->query($sql3);
					$nm3=$rs3->num_rows;
					if($nm3>0){
						$row3=$rs3->fetch_assoc();
						if($row3['cash_remittance']==""){
							if($type=="deposit"){
							}
							else {
							//	$style="style='background-color:yellow;'";
							}
						}
					}
					
					?>				
				<tr <?php echo $style; ?>>
					
					<td><?php echo $date; ?></td>
					<td>
					<?php 
					if($type=="deposit")
					{ echo "<a href='#' style='text-decoration:none' onclick='window.open(\"pnb_deposit.php?tID=".$edit_id."\",\"deposit\",\"height=550, width=500, scrollbars=yes\")'>PNB Deposit - ".strtoupper($deposit_type)."</a>"; 


					} 
					else if($type=="remittance"){ 
						if($log_type=="cash"){
							if($cashStation=="annex"){
								if($_SESSION['viewMode']=="login"){
									echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>ANNEX</a>"; 
								}
								else {
									echo "ANNEX";
								}
							
							}
							else {
								if($_SESSION['viewMode']=="login"){
									echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."</a>"; 
								}
								else {
									echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix;	
								}
							}
						}
						else if($log_type=="shortage"){
							if($_SESSION['viewMode']=="login"){
								echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix." - Payment for Shortage</a>"; 		
							}
							else {
								echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix." - Payment for Shortage";
							}
						}
					} 
					else if($type=="allocation"){ 
						if($cashStation=="annex"){
							if($_SESSION['viewMode']=="login"){
								echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>ANNEX</a>";  
							}
							else {
								echo "ANNEX";
							}
						}
						else {
							if($_SESSION['viewMode']=="login"){
								echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."</a>";  
							}
							else {
								echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix;
							}
						}
					}	
					?>
					
					</td>
					<td align=center>
					<?php 
						if($type=="deposit"){
							echo "&nbsp;";
						}
						else if($type=="remittance"){
							if($log_type=="cash"){
								if($cashStation=="annex"){
									echo "&nbsp;";
								}
								else {
								echo $ticketRow['id'];
								}
							}
							else {
								echo "&nbsp;";
							}
							
						}
						else { 
							if($cashStation=="annex"){
								echo "&nbsp;";
							}
							else {
							echo $ticketRow['id'];
							}
						}	
						?>
					</td>	
					<?php 
					if($type=="remittance"){
					?>
						<td style='color:green;' align=right>+<?php echo number_format($revolving*1,2); ?></td>
						<td style='color:green;' align=right>+<?php echo number_format($deposit*1,2); ?></td>
						<td>-</td>
						<td style='color:green;' align=right>+<?php echo number_format($total*1,2); ?></td>
					
					
					<?php
					
						$overageSQL="select * from discrepancy where transaction_id='".$transaction_id."'";
						$overageRS=$db->query($overageSQL);
						$overageNM=$overageRS->num_rows;
						if($overageNM>0){
							$overageRow=$overageRS->fetch_assoc();
							if($overageRow['type']=="shortage"){
								$overageLabel=number_format($overageRow['amount'],2);
							
							}
							else if($overageRow['type']=="overage"){
								$overageLabel="(".number_format($overageRow['amount'],2).")";
							
							}
						}
						else {
							$overageLabel=0;
						}
					?>
						<td align=right><?php echo $overageLabel; ?></td>

					<?php
					}
					else if($type=="allocation"){
					?>
						<td  style='color:red;' align=right>-<?php echo number_format($revolving*1,2); ?></td>
						<td>-</td>
						<td>-</td>
						<td  style='color:red;' align=right>-<?php echo number_format($revolving*1,2); ?></td>
						<td>-</td>	
					


						
					<?php
					}
					else if($type=="deposit"){
					?>	
						<td>-</td>
						<td>-</td>
						<td  style='color:red;' align=right>-<?php echo number_format($cashRow['amount']*1,2); ?></td>
						<td  style='color:red;' align=right>-<?php echo number_format($cashRow['amount']*1,2); ?></td>
						<td>-</td>	
					
					
					
					<?php	
					}
				?>
				<?php 
				
				if($type=="allocation"){
					$revolvingTotal=$revolvingTotal-$revolving;
					$revolving_style="style='color:red;'";
					$deposit_style="";
					$total_style="style='color:red;'";
				}
				else if($type=="remittance"){
					$revolvingTotal=$revolvingTotal+$revolving;
					
					$depositTotal=$depositTotal+$deposit;
					
					$revolving_style="style='color:green;'";
					$deposit_style="style='color:green;'";					
					$total_style="style='color:green;'";

				}
				
				if($type=="deposit"){
					$depositTotal=$depositTotal-($cashRow['amount']*1);

					$revolving_style="";
					$deposit_style="style='color:red;'";
					$total_style="style='color:red;'";

				}
				$displayTotal=($revolvingTotal*1)+($depositTotal*1);
				/*
				if($overageSwitch=="overage"){
					$displayTotal-=$overage;
				}
				else if($overageSwitch=="shortage"){
					$displayTotal+=$overage;
				}
				*/
				
				?>
				<td <?php echo $revolving_style; ?> align=right><?php echo number_format($revolvingTotal*1,2); ?></td>
				<td <?php echo $deposit_style; ?>  align=right><?php echo number_format($depositTotal*1,2); ?></td>
				<td <?php echo $total_style; ?>  align=right><?php echo number_format($displayTotal*1,2); ?></td>
				<td align=right><?php echo $remarks; ?> <a href='#' class='delete'  onclick='deleteRecord("<?php echo $transaction_id; ?>","cash")' >X</a></td>

				</tr>				
				<?php
				
				
				
				}	
				?>	
				

				<?php
				$sql="select * from transaction where transaction_type='catransfer' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$cTransferSQL="select * from cash_transfer where transaction_id='".$row['transaction_id']."'";
					$cTransferRS=$db->query($cTransferSQL);
					$cTR=$cTransferRS->fetch_assoc();
					$transaction_id=$row['transaction_id'];	
					$revolvingTransfer=$cTR['total'];
					$depositTransfer=$cTR['net_revenue'];
					$totalTransfer=$revolvingTransfer+$depositTransfer;
					
					$revolvingTotal-=$revolvingTransfer;
					$depositTotal-=$depositTransfer;
					$displayTotal-=$totalTransfer;
					$remarks=$cTR['reference_id'];
					$edit_id=$row['id'];
					
				?>			
				<tr>
					<td>&nbsp;</td>
					<td>
					<?php
					if($_SESSION['viewMode']=="login"){
					?>
					<a href='#' style='text-decoration:none' onclick='window.open("cash_transfer.php?tID=<?php echo $edit_id; ?>","transfer","height=800, width=500, scrollbars=yes")'>
					<?php
					}
					?>
					Turnover to CA
					<?php
					if($_SESSION['viewMode']=="login"){
					?>
					</a>
					<?php
					}
					?>	
					</td>
					<td>&nbsp;</td>
					

					
					<td align=right><?php echo number_format($revolvingTransfer,2); ?></td>
					<td align=right><?php echo number_format($depositTransfer,2); ?></td>
					<td>&nbsp;</td>

					<td align=right><?php echo number_format($totalTransfer,2); ?></td>
					<td>-</td>

					<td align=center>
					<?php 
					if($revolvingTotal==0){ echo "---"; } else { 
						if($revolvingTotal<0){
							echo "(".number_format(($revolvingTotal*-1),2).")";
						
						}
						else {
							echo number_format($revolvingTotal,2); 
						}
					} 
					?></td>
					<td align=center>
					<?php 
					if($depositTotal==0){ echo "---"; } else { 
						if($depositTotal<0){
							echo "(".number_format(($depositTotal*-1),2).")";
						
						}
						else {
							echo number_format($depositTotal,2); 
						}
					} 
					?></td>
					<td align=center><?php if($displayTotal==0){ echo "---"; } 
					else { 
						if($displayTotal<0){
							echo "(".number_format(($displayTotal*-1),2).")";
						
						}
						else {
							echo number_format($displayTotal,2); 
						}
					} 
					?></td>

					<td align=right><?php echo $remarks; ?> <a class='delete' href='#' onclick='deleteRecord("<?php echo $transaction_id; ?>","cash")' >X</a></td>	
				</tr>
				<?php
				}
				?> 				
			</tbody>

            </table>
        </div>

<br>
<?php require("test_cslip_list.php"); ?>

	</div>
	
</div>	