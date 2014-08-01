<!--
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="css/styles.css" rel="stylesheet" type="text/css" />
-->
<!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->
<!--
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
-->

		<?php 
		$tickets[0]='sjt';
		$tickets[1]='sjd';
		$tickets[2]='svt';
		$tickets[3]='svd';
		
		$log_id=$_SESSION['log_id']; 
		if(isset($_POST['cs_ticket_seller'])){

			$ticket_seller=$_POST['cs_ticket_seller'];
			$unit=$_POST['unit'];
			$station=$_POST['station'];
			$reference_id=$_POST['reference_id'];
			
			$_SESSION['unit']=$unit;
			$_SESSION['ticket_seller']=$ticket_seller;

			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;

			$control_id="";
				if($nm>0){
					$row=$rs->fetch_assoc();
					$control_id=$row['id'];
					$_SESSION['control_id']=$control_id;

				}

				else if($nm==0) {
					$insert="insert into control_slip(log_id,ticket_seller,unit,station,status,reference_id) values ('".$log_id."','".$ticket_seller."','".$unit."','".$station."','open','".$reference_id."')";
					$rsInsert=$db->query($insert);
					$control_id=$db->insert_id;
					$_SESSION['control_id']=$control_id;
					
				}
	
	
			$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;

			if($nm==0){
				$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
				$updateRS=$db->query($update);
			}
		
			if(isset($_POST['initial_enable'])){
			}
			else {

				$sql="select * from allocation where control_id='".$control_id."'";

				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$transaction_no=$row['id'];
					$transaction_id=$row['transaction_id'];			
				
				}
				else {
					$date=date("Y-m-d H:i");
					$date_id=date("Ymd");
					
					$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','allocation')";
					
					$rsInsert=$db->query($transactionInsert);
								
					$insert_id=$db->insert_id;
							
					$transaction_id=$date_id."_".$insert_id;
					$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
					$rs=$db->query($sql);					


					$transaction_no=$insert_id;		
				
				}
				
				for($i=0;$i<count($tickets);$i++){		
					if(($_POST[$tickets[$i]."_allocation_a"]=="")&&($_POST[$tickets[$i]."_allocation_b"]=="")){

					}
					else {
						$initial=$_POST[$tickets[$i]."_allocation_a"];
						$additional=$_POST[$tickets[$i]."_allocation_b"];
						$initial_loose=$_POST[$tickets[$i]."_allocation_a_loose"];
						$additional_loose=$_POST[$tickets[$i]."_allocation_b_loose"];
						
						
						$sql="select * from allocation where control_id='".$control_id."' and type='".$tickets[$i]."'";

						$rs=$db->query($sql);
						$nm=$rs->num_rows;
						
						if($nm==0){
							$sql="insert into allocation(control_id,type,initial,additional,initial_loose,additional_loose,transaction_id) values ";
							$sql.="('".$control_id."','".$tickets[$i]."','".$initial."','".$additional."','".$initial_loose."','".$additional_loose."','".$transaction_id."')";
							$rs=$db->query($sql);

						}
						else {
							$sql="update allocation set initial='".$initial."',initial_loose='".$initial_loose."' where control_id='".$control_id."' and type='".$tickets[$i]."'";
							
							$rs=$db->query($sql);	

						}		
					}	
				}		

			
			}
		
		
		
		
		}
		?>
        <div class="widget" style="width:25%;" id='cslip_list'>
            <div class="whead">
                <h6>Control Slips Currently Open (Draggable)</h6>
                <div class="titleOpt">
                    <a href="#"  class="buttonM bDefault ml10" id="formDialog_open" title='Add New Control Slip'><span class="icos-add"></span><span class="clear"></span></a>
					

                        <div id="formDialog" class="dialog" title="New Control Slip" style='display:none;'>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" id='new_cslip' name='new_cslip' method='post' class='form_class'>
                                <div class="dialogSelect m10 searchDrop">
                                    <label>Ticket Seller</label>
                                    <select name="cs_ticket_seller" class='select' style='width:200px;' >
										<?php
										$db=new mysqli("localhost","root","","finance");
										$sql="select * from ticket_seller order by last_name";
										$rs=$db->query($sql);
										$nm=$rs->num_rows;
										?>
										<?php 
										for($i=0;$i<$nm;$i++){
											$row=$rs->fetch_assoc();
										?>
											<option value='<?php echo $row['id']; ?>' <?php if($ticketsellerpost==$row['id']){ echo "selected"; } ?>><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
										<?php
										}
										?>
                                    </select>
                                </div>
                                <div class="dialogSelect m10">
                                    <label>Unit</label>
                                    <select name="unit" >
										<option <?php if($unit=="A/D1"){ echo "selected"; } ?> value='A/D1'>AD1</option>
										<option <?php if($unit=="A/D2"){ echo "selected"; } ?> value='A/D2'>AD2</option>
										<option <?php if($unit=="TIM1"){ echo "selected"; } ?> value='TIM1'>TIM1</option>
										<option <?php if($unit=="TIM2"){ echo "selected"; } ?> value='TIM2'>TIM2</option>
										<option <?php if($unit=="TIM3"){ echo "selected"; } ?> value='TIM3'>TIM3</option>
                                    </select>
                                </div>
                                <div class="dialogSelect m10">
                                    <label>Station</label>
                                    <select name="station" style='width:200px;'>
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
                                </div>
                                <div class="dialogSelect m10">
									<label>Reference ID</label>
									<input type='text' name='reference_id' align=left style='width:200px;' />
								</div>	


                                <div class="divider"><span></span></div>
                                <label>Initial Allocation</label>
								<br>
								<table style='width:100%'>
								<tr>
									<th>Ticket Type</th>
									<th style='text-align:center'>Pieces</th>
									<th  style='text-align:center'>Loose</th>
								
								</tr>
								<tr>
								<td>SJT</td>
								<td><input type="text" name="sjt_allocation_a" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sjt_allocation_aloose" class="clear" placeholder="Enter Quantity" /></td>

								</tr>		
								<tr>
								<td>SJD</td>
								<td><input type="text" name="sjd_allocation_a" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sjd_allocation_a_loose" class="clear" placeholder="Enter Quantity" /></td>
								</tr>		
								<tr>
								<td>SVT</td>
								<td><input type="text" name="svt_allocation_a" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="svt_allocation_a_loose" class="clear" placeholder="Enter Quantity" /></td>
								</tr>		
								<tr>
								<td>SVD</td>
								<td><input type="text" name="svd_allocation_a" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="svd_allocation_a_loose" class="clear" placeholder="Enter Quantity" /></td>

								</tr>		
								
								
								</table>
                                <div>
                                    <span class="floatL"><input type="checkbox" class="check" name="initial_enable"  /><label>Initial Allocation not yet recorded</label></span>
                                    <span class="clear"></span>
                                </div>
                            </form>
                        </div>
					<!--
						<ul class="dropdown-menu pull-right">
							<li><a href="#"><span class="icos-add"></span>Add</a></li>
							<li><a href="#"><span class="icos-trash"></span>Remove</a></li>
							<li><a href="#" class=""><span class="icos-pencil"></span>Edit</a></li>
							<li><a href="#" class=""><span class="icos-heart"></span>Do whatever you like</a></li>
						</ul>
					-->
                </div>
                <div class="clear"></div>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault  table-hover">
                <thead>
                    <tr>
                        <td width='40%' >Name</td>
                        <td>Station</td>
                        <td>Unit</td>
                    </tr>
                </thead>
                <tbody>


				<?php
				$db=new mysqli("localhost","root","","finance");
				$sql="select control_slip.id as control_id,control_slip.*,ticket_seller.* from control_slip inner join ticket_seller on control_slip.ticket_seller=ticket_seller.id where control_slip.status='open' order by ticket_seller.last_name ";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					for($i=0;$i<$nm;$i++){
						$row=$rs->fetch_assoc();
						$stationSQL="select * from station where id='".$row['station']."'";
						$stationRS=$db->query($stationSQL);
						$stationRow=$stationRS->fetch_assoc();
						$station_name=$stationRow['station_name'];
						$control_id=$row['control_id'];
					?>
						<tr>
						<td><a href="#" onclick='window.open("test_control_slip.php?edit_control=<?php echo $control_id; ?>","control slip","height=750, width=1200, scrollbars=yes")' ><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></td>
						<td><?php echo $station_name; ?></td>
						<td><?php echo $row['unit']; ?></td>
						</tr>

				<?php
					}
				}
				?>
                </tbody>
            </table>
        </div>
