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
                    <div class="whead"><h6>PNB Deposit</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Reference ID (PNB)</label></div>
                        <div class="grid9"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					
					<div class="formRow">
						<div class="grid3"><label>Date and Time</label></div>

                        <div class="grid9"><div class='grid3'><input type="text" class="datepicker" /></div><div  class='grid3'> <input type="text" class="timepicker" size="10" /></div></div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid3"><label>Type</label></div>
                        <div class="grid9  noSearch">
						<select name='deposit_type' class='select'>
						<option <?php if($depositType=="previous"){ echo "selected"; } ?> value='previous'>Previous</option>
						<option <?php if($depositType=="current"){ echo "selected"; } ?> value='current'>Current</option>
						</select>
						</div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid3"><label>Cash Assistant</label></div>
                        <div class="grid9  searchDrop">
						<select name='cash_assistant' class='select'>
						<?php
						$db=new mysqli("localhost","root","","finance");
						$sql="select * from login where status='active' order by lastName";
						$rs=$db->query($sql);
						$nm=$rs->num_rows;
						for($i=0;$i<$nm;$i++){
						$row=$rs->fetch_assoc();
						?>
						<option value='<?php echo $row['username']; ?>' 
						<?php 
						if(isset($cash_assist)){
						if($row['username']==$cash_assist){ 
						echo "selected"; 

						}
						}
						else {
						if($row['username']==$_SESSION['username']){ 
						echo "selected"; 

						} 
						}?> >
						<?php
						echo strtoupper($row['lastName']).", ".$row['firstName'];
						?>
						</option>
						<?php
						}
						?>
						</select>						
						
						
						
						</div>
                        <div class="clear"></div>
                    </div>

				</div>	
							

                <div class="widget fluid">
                    <div class="whead">
						<div class="grid3"><h6>Denomination</h6></div>
						<div class="grid4"><h6>Quantity</h6></div>
						<div class="grid5"><h6>Amount</h6></div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>1000</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>

                        <div class="clear"></div>
                    </div>

					<div class="formRow">
						<div class="grid3"><label>500</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>

                        <div class="clear"></div>
                    </div>
					
					<div class="formRow">
						<div class="grid3"><label>200</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>100</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>50</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>20</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>10</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>5</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>1</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>.25</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>.10</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>.05</label></div>
                        <div class="grid4"><input type="text" name="regular" /></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
					
						<div class='grid4'>&nbsp;</div>
						<div class="grid3"><label>PNB Deposit</label></div>
                        <div class="grid5"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>

				</div>	
 
				
				<div class="widget fluid">
                    <div class="formRow">
						<div class='grid12' align=center><input  class='btn btn-primary' type='submit' value='Submit' /></div>
					</div>		
				</div>	
				
			</fieldset>
		</form>
	</div>