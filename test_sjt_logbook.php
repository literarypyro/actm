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
            <div class="whead">
                <h6>SJT Logbook</h6>
                <div class="titleOpt">
                    <a href="#" data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="#" onclick='window.open("test_ticket_order.php","ticket_order","height=420, width=500, scrollbars=yes")'><span class="icos-add"></span>Ticket Order</a></li>
                        <li><a href="#" onclick='window.open("test_physically_defective.php","defective","height=500, width=450")'><span class="icos-add"></span>Physically Defective</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault table-hover">
                <thead>
                    <tr>
                        <th colspan=3 style='text-align:center;'>Particulars</th>
                        <th colspan=4 style='text-align:center;'>Tickets Supplied In/Out</th>
                        <th colspan=4 style='text-align:center;'>Tickets Remaining</th>
                        <th rowspan=3  style='text-align:center;' valign=bottom>Remarks</th>
                    </tr>
                    <tr>
						<td rowspan=2>Time</td>
						<td rowspan=2>Name</td>
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

				<?php
				$db=new mysqli("localhost","root","","finance");
				$sql="select * from beginning_balance_sjt where log_id='".$log_id."'";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				//$row=$rs->fetch_assoc();


				if($nm>0){

				$row=$rs->fetch_assoc();

				$sjt_loose_1=$row['sjt_loose'];
				$sjt_packs_1=$row['sjt'];


				$sjd_loose_1=$row['sjd_loose'];
				$sjd_packs_1=$row['sjd'];

				}
				else {

				$alternate="SELECT * FROM beginning_balance_sjt inner join logbook on beginning_balance_sjt.log_id=logbook.id and station='".$station."' order by date desc,field(revenue,'close','open'),field(shift,2,1,3)";

				$rs2=$db->query($alternate);
				$row=$rs2->fetch_assoc();
				$sjt_loose_1=$row['sjt_loose'];
				$sjt_packs_1=$row['sjt'];


				$sjd_loose_1=$row['sjd_loose'];
				$sjd_packs_1=$row['sjd'];

					$insert="insert into beginning_balance_sjt(log_id,sjt,sjt_loose,sjd,sjd_loose) values ('".$log_id."','".$sjt_packs_1."','".$sjt_loose_1."','".$sjd_packs_1."','".$sjd_loose_1."')";
					$insertRS=$db->query($insert);	

				}

				?>
				<tr>
				<td colspan=3>Beginning Balance <a href='#' style='text-decoration:none' onclick='window.open("beginning data entry.php?loID=<?php echo $log_id; ?>&type=sj","beginning","height=300, width=300")' >[Data Entry]</a></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

				<td>&nbsp;</td>
				<td>&nbsp;</td>

				<td align=right><?php echo $sjt_packs_1; ?></td>
				<td align=right><?php echo $sjt_loose_1; ?></td>

				<td align=right><?php echo $sjd_packs_1; ?></td>
				<td align=right><?php echo $sjd_loose_1; ?></td>

				<td>&nbsp;</td>
				
				
				</tr>

				<?php
				$db=new mysqli("localhost","root","","finance");


				$sql="select * from transaction where log_id='".$log_id."' and log_type in ('ticket','initial','annex','finance') and transaction_type not in ('ticket_amount')  order by id*1";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				for($a=0;$a<$nm;$a++){

					$row=$rs->fetch_assoc();

					$date=date("h:i a",strtotime($row['date']));
					$transaction_id=$row['transaction_id'];
					$type=$row['transaction_type'];
					$edit_id=$row['id'];

					$log_type=$row['log_type'];
					if($row['reference_id']==""){
					$remarks="&nbsp;";
					}
					else {
					$remarks=$row['reference_id'];
					}		
					$sjt_packs=0;
					$sjd_packs=0;
					$sjt_loose=0;
					$sjd_loose=0;
					$sjt_loose_in=0;
					$sjd_loose_in=0;
					
					$suffix="";
					
					if(($row['log_type']=='ticket')||($row['log_type']=="annex")||($row['log_type']=="finance")){
					
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
						//$sjt_loose=$ticketRow['sjt']%100;
						$sjt_loose=$ticketRow['sjt_loose'];
						$sjt_packs=$ticketRow['sjt'];
						$remarks=$ticketRow['reference_id'];
						//$sjt_packs=($ticketRow['sjt']*1-$sjt_loose);
						$sjd_loose=$ticketRow['sjd_loose'];
						$sjd_packs=$ticketRow['sjd'];
						
				//		$sjd_loose=$ticketRow['sjd']%10;
				//		$sjd_packs=($ticketRow['sjd']*1-$sjd_loose);	
						$unitType=$ticketRow['unit'];	
						$ticketSellerId=$ticketRow['ticket_seller'];
					
					}
					else if($row['log_type']=='initial'){
						$sjt_packs=0;
						$sjd_packs=0;

						$sjt_loose=0;
						$sjd_loose=0;			
						$sjt_loose_in=0;
						$sjd_loose_in=0;
						
						
						$trans_type=$row['transaction_type'];
						if($trans_type=="allocation"){
							$ticketSQL="select * from allocation where transaction_id='".$transaction_id."' and type in ('sjd','sjt')";

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
									$remarks=$ticketsRow['reference_id'];		
									
									$ticketSellerId=$ticketsRow['ticket_seller'];
									$unitType=$ticketsRow['unit'];
									if($ticketsRow['station']==$logST){
									}
									else {
										$extensionSQL="select * from station where id='".$ticketRow['station']."'";
										$extensionRS=$db->query($extensionSQL);
										$extensionRow=$extensionRS->fetch_assoc();
												
										$suffix=" - ".$extensionRow['station_name'];
											
									}							
									
								
								}


						
								if($ticketRow['type']=='sjt'){
									$sjt_packs=$ticketRow['initial']*1;
									$sjt_loose=$ticketRow['initial_loose']*1;

								
								}
								else if($ticketRow['type']=="sjd"){
									
									$sjd_packs=$ticketRow['initial']*1;
									$sjd_loose=$ticketRow['initial_loose']*1;
								}				
							}
						
						}
						else if($trans_type=="remittance"){
							$sjt_packs=0;
							$sjd_packs=0;
							
							$sjt_loose=0;
							$sjd_loose=0;		

							$sjt_loose_in=0;
							$sjd_loose_in=0;		

							
							$looseSQL="select * from control_unsold where transaction_id='".$transaction_id."' and type in ('sjd','sjt')";
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
									
									$ticketSellerId=$ticketsRow['ticket_seller'];
									$unitType=$ticketsRow['unit'];		
									$remarks=$ticketsRow['reference_id'];		
									if($ticketsRow['station']==$logST){
									}
									else {
										$extensionSQL="select * from station where id='".$ticketRow['station']."'";
										$extensionRS=$db->query($extensionSQL);
										$extensionRow=$extensionRS->fetch_assoc();
												
										$suffix=" - ".$extensionRow['station_name'];
											
									}						
								}
							
								if($looseRow['type']=='sjt'){
									$loose_total=$looseRow['loose_defective']*1;
									$sjt_loose=$loose_total;
									$sjt_loose_in=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
									$sjt_packs=$looseRow['sealed'];
								}
								else if($looseRow['type']=="sjd"){
									
									$loose_total=$looseRow['loose_defective']*1;
									$sjd_loose_in=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
									$sjd_loose=$loose_total;
									$sjd_packs=$looseRow['sealed'];
								
								}
							
							}
						}
					}
					
					$ticketSellerSQL="select * from ticket_seller where id='".$ticketSellerId."'";		

					$ticketSellerRS=$db->query($ticketSellerSQL);
					$ticketSellerRow=$ticketSellerRS->fetch_assoc();

					$verify=$sjt_packs+$sjt_loose+$sjd_packs+$sjd_loose+$sjt_loose_in+$sjd_loose_in;

					if($verify==0){
				//	if(($row['log_type']=='ticket')&&($verify==0)){
					}
					else {

				?>
				<tr>
					<td><?php echo $date; ?></td>
					<td><?php  
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
						<td align=right><font color=green>+<?php echo $sjt_packs*1; ?></font></td>
						<td align=right><font color=green>+<?php echo $sjt_loose_in*1; ?></font>
						<font color=red>(-<?php echo $sjt_loose*1; ?>)</font>
						</td>
						<td align=right><font color=green>+<?php echo $sjd_packs*1; ?></font></td>
						<td align=right><font color=green>+<?php echo $sjd_loose_in*1; ?></font>
						<font color=red>(-<?php echo $sjd_loose*1; ?>)</font>
						
						</td>
						
				<?php			
						
						}
						else if($type=="allocation"){
				?>

						<td style='color:red;' align=right>-<?php echo $sjt_packs*1; ?></td>
						<td style='color:red;' align=right>-<?php echo $sjt_loose*1; ?></td>
						<td style='color:red;' align=right>-<?php echo $sjd_packs*1; ?></td>
						<td style='color:red;' align=right>-<?php echo $sjd_loose*1; ?></td>
						
						
					<?php		
						}
					
					}
					else if(($log_type=="annex")||($log_type=="finance")){
				?>

						<td style='color:green;' align=right>+<?php echo $sjt_packs*1; ?></td>
						<td style='color:green;' align=right>+<?php echo $sjt_loose*1; ?></td>
						<td  style='color:green;' align=right>+<?php echo $sjd_packs*1; ?></td>
						<td style='color:green;'  align=right>+<?php echo $sjd_loose*1; ?></td>				



						
				<?php	
					}
					else if($log_type=="ticket"){
				?>		

						<td  style='color:red;' align=right>-<?php echo $sjt_packs*1; ?></td>
						<td  style='color:red;' align=right>-<?php echo $sjt_loose*1; ?></td>
						<td  style='color:red;' align=right>-<?php echo $sjd_packs*1; ?></td>
						<td  style='color:red;' align=right>-<?php echo $sjd_loose*1; ?></td>
				<?php	
					}

				?>	

				<?php
				//Total
					$total_style="style=''";
					if(($log_type=="annex")||($log_type=="finance")){
						$sjt_packs_1+=$sjt_packs*1;
						$sjd_packs_1+=$sjd_packs*1;
						$sjd_loose_1+=$sjd_loose;
						$sjt_loose_1+=$sjt_loose;
						$total_style="style='color:green;'";

					}
					else if($log_type=="ticket"){
						if($type=="allocation"){
							$sjt_packs_1-=$sjt_packs*1;
							$sjd_packs_1-=$sjd_packs*1;
							$sjd_loose_1-=$sjd_loose;
							$sjt_loose_1-=$sjt_loose;
							$total_style="style='color:red;'";
							
						}
						else if($type=="remittance"){
							$sjd_loose_1+=$sjd_loose_in-$sjd_loose;	
							$sjt_loose_1+=$sjt_loose_in-$sjt_loose;	
							$sjt_packs_1+=$sjt_packs*1;
							$sjd_packs_1+=$sjd_packs*1;
							$total_style="style='color:green;'";
						
						
						}
					}
					else if($log_type=="initial"){
						if($type=="allocation"){
							$sjt_packs_1-=$sjt_packs*1;
							$sjd_packs_1-=$sjd_packs*1;
							$sjd_loose_1-=$sjd_loose*1;
							$sjt_loose_1-=$sjt_loose*1;
							
							$total_style="style='color:red;'";
							
						}
						else if($type=="remittance"){
							$sjd_loose_1+=$sjd_loose_in-$sjd_loose;	
							$sjt_loose_1+=$sjt_loose_in-$sjt_loose;	
							$sjt_packs_1+=$sjt_packs*1;
							$sjd_packs_1+=$sjd_packs*1;
							
							$total_style="style='color:green;'";
							
						}
					
					}
					?>
						<td <?php echo $total_style; ?>  align=right><?php echo $sjt_packs_1; ?></td>
						<td <?php echo $total_style; ?>  align=right><?php echo $sjt_loose_1; ?></td>
						<td <?php echo $total_style; ?>  align=right><?php echo $sjd_packs_1; ?></td>	
						<td <?php echo $total_style; ?>  align=right><?php echo $sjd_loose_1; ?></td>
					<?php
					
					
				?>
				<td align=right><?php echo $remarks; ?> <a class='delete' href='#' onclick='deleteRecord("<?php echo $transaction_id; ?>","ticket")' >X</a></td>
				</tr>	

				<?php
					}
				}
				$sqlDefective="select * from physically_defective where log_id='".$log_id."'";
				$rsDefective=$db->query($sqlDefective);
				$nmDefective=$rsDefective->num_rows;

				if($nmDefective>0){
					$rowDefective=$rsDefective->fetch_assoc();
					$verify=$rowDefective['sjt']+$rowDefective['sjd'];
					if($verify>0){

						$date=date("h:i a",strtotime($rowDefective['date']));
					?>	
						<td><?php echo $date; ?></td>
						<td>Physically Defective</td>
						<td>&nbsp;</td>
						
						<td>&nbsp;</td>
						<td style='color:red;' align=right><?php echo $rowDefective['sjt']; ?></td>
						<td>&nbsp;</td>
						<td style='color:red;'  align=right><?php echo $rowDefective['sjd']; ?></td>	
					
					<?php
						$sjd_loose_1-=$rowDefective['sjd'];	
						$sjt_loose_1-=$rowDefective['sjt'];			
					?>
						<td style='color:red;' align=right><?php echo $sjt_packs_1; ?></td>
						<td style='color:red;' align=right><?php echo $sjt_loose_1; ?></td>
						<td style='color:red;' align=right><?php echo $sjd_packs_1; ?></td>	
						<td style='color:red;' align=right><?php echo $sjd_loose_1; ?></td>
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