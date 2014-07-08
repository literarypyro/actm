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
<script type="text/javascript" src="js/files/additional_function.js"></script>


<div class="widget fluid" style='width:500px;'>
    <div class="formRow">
        <div class="grid5"><label>Total Amount</label></div>
		<div class="grid5"><input type="text" name="total_amount"  /></div>
        <div class="clear"></div>
    </div>
					
	<div class="formRow">
		<div class="grid5"><label>Fare Adjustment</label></div>

		<div class="grid4"><input type="text" name="fare_adjustment" readonly='readonly'  />
		</div>
		<div class='grid1'>
		<a href="#" title='Edit' id="fa_open"><i class='icos-pencil'></i></a>

            <div id="fare_adjustment_modal" title="Fare Adjustment">
                <form action="" method='post' class='form_class'>

							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								
								</tr>
								<tr>
								<td>SJT</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								<tr>
								<td>SJD</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								<tr>
								<td>SVT</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								<tr>
								<td>SVD</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								<tr>
								<td>C</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								<tr>
								<td>OT</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								
								
								</table>
				</form>	
            </div>
		</div>
        <div class="clear"></div>
    </div>

	
	<div class="formRow">
		<div class="grid5"><label>Cash Advance</label></div>

		<div class="grid4"><input type="text" name="cash_advance" readonly='readonly'  />
		</div>
		<div class='grid1'><a href='#' title='Track'><i class='icos-cog'></i></a></div>
        <div class="clear"></div>
    </div>
	<div class="formRow">
		<div class="grid5"><label>Overage</label></div>

		<div class="grid4"><input type="text" name="overage"  />
		</div>
		<div class='grid1'><a href='#' title='Submit'><i class='icos-arrowright'></i></a></div>
        <div class="clear"></div>
    </div>
	<div class="formRow">
		<div class="grid5"><label>Unreg Sale</label></div>

		<div class="grid4"><input type="text" name="unreg_sale"  readonly='readonly' />
		</div>
		<div class='grid1'><a href='#' title='Edit' id='unreg_open'><i class='icos-pencil'></i></a>

		
		
            <div id="unreg_sale_modal" title="Add Unreg Sale">
							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
								
								</tr>
								<tr>
								<td>SJ</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>

								</tr>		
								<tr>
								<td>SV</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>

								</tr>		
							</table>
				
		
		
		
		
		
		
			</div>	
		</div>
        <div class="clear"></div>
    </div>
	
	<div class="formRow">
		<div class="grid5"><label>Refund</label></div>

		<div class="grid4"><input type="text" name="refund"  readonly='readonly' />
		</div>
		<div class='grid1'>
		
		<a href='#' title='Edit' id="refund_open"><i class='icos-pencil'></i></a>
		
		
            <div id="refund_modal" title="Add Refund">
							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								
								</tr>
								<tr>
								<td>SJ</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
								<tr>
								<td>SV</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Amount" /></td>

								</tr>		
							</table>
				
		
		
		
		
		
		
			</div>	
		
		</div>


        <div class="clear"></div>
    </div>
	
	<div class="formRow">
		<div class="grid5"><label>Unpaid Shortage</label></div>

		<div class="grid4"><input type="text" name="unpaid_shortage" readonly='readonly'  />
		</div>
		<div class='grid1'><a href='#' title='Submit'><i class='icos-arrowright'></i></a></div>
        <div class="clear"></div>
    </div>
	<div class="formRow">
		<div class="grid5"><label>Discount</label></div>

		<div class="grid4"><input type="text" name="discount" readonly='readonly'  /></div>
		<div class='grid1'><a href='#' title='Edit' id='discount_open'><i class='icos-pencil'></i></a>

		
		
            <div id="discount_modal" title="Add Discount">
							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
								
								</tr>
								<tr>
								<td>SJ</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>

								</tr>		
								<tr>
								<td>SV</td>
								<td><input type="text" name="sampleInput" class="clear" placeholder="Enter Quantity" /></td>

								</tr>		
							</table>
				
		
		
		
		
		
		
			</div>	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		</div>

		
        <div class="clear"></div>
    </div>
	
	<div class="formRow">
		<div class="grid5"><label>Net Remittance</label></div>

		<div class="grid5"><input type="text" name="net_remittance" readonly='readonly'  /></div>
        <div class="clear"></div>
    </div>
	
	
</div>	
					
