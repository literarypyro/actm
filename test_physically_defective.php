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


    <div class="wrapper">
    
        <form action="" method='post' class="main">
            <fieldset>
                <div class="widget fluid grid3">
                    <div class="whead"><h6>Discrepancy Report</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Ticket Seller</label></div>
                        <div class="grid9  searchDrop">
						<?php
						$db=new mysqli("localhost","root","","finance");
						$sql="select * from ticket_seller order by last_name";
						$rs=$db->query($sql);
						$nm=$rs->num_rows;
						?>
						<select name='ticket_seller' class="select" >
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
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Select Station</label></div>
                        <div class="grid9 noSearch">
							<select name='station' class='select' style='width:200px'>

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
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid3"><label>SJT</label></div>
						<div class='grid6'>
						
						
							<input type='text' name='amount' />

						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SJD</label></div>
						<div class='grid6'>

						<input type='text' name='amount' />

						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SVT</label></div>
						<div class='grid6'>
						<input type='text' name='amount' />

						</div>
                        <div class="clear"></div>
                    </div>					
                    <div class="formRow">
                        <div class="grid3"><label>SVD</label></div>
						<div class='grid6'>
							<input type='text' name='amount' />
						</div>
                        <div class="clear"></div>
                    </div>

					
                    <div class="formRow">
						<div class='grid12' align=center><input  class='btn btn-primary' type='submit' value='Submit' /></div>
					</div>		

				</div>	
							
				
			</fieldset>
		</form>
	</div>