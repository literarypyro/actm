<div id="control_unsold" name='control_unsold' class="customDialog" title="Unsold/Excess">
<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-3'>&nbsp;</td>
<td class='col-md-3'>Sealed</td>
<td class='col-md-3'>Loose Good</td>
<td class='col-md-3'>Loose Defective</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' /></td>
<td><input type='text' /></td>
<td><input type='text' /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' /></td>
<td><input type='text' /></td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text' /></td>
<td><input type='text' /></td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text' /></td>
<td><input type='text' /></td>
<td><input type='text' /></td>

</tr>


</table>
</div>
<div id="control_discrepancy" name='control_discrepancy' class="customDialog" title="Discrepancy Report">
        <form action="" method='post' class="main">
            <fieldset>
                <div class="widget fluid grid3">
					<div class="formRow">
						<div class="grid3"><label>Classification</label></div>

                        <div class="grid9">
						<select name='classification'>
							<option value='ticket' <?php if($classification=="ticket"){ echo "selected"; } ?>>Ticket</option>
						</select>
						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Reported By:</label></div>
                        <div class="grid9  noSearch">
						<select name='reported' class='select'>
							<option value='ticket seller' <?php if($reported=="ticket seller"){ echo "selected"; } ?>>Ticket Seller</option>
							<option value='cash assistant' <?php if($reported=="cash assistant"){ echo "selected"; } ?> >Cash Assistant</option>
						</select>
						</div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid3"><label>SJT</label></div>
                        <div class="grid4 noSearch">
							<select name='sjt_classification' class='select' style='width:100%'>
								<option value='shortage' <?php if($sjt_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
								<option value='overage' <?php if($sjt_classification=="overage"){ echo "selected"; } ?>>Overage</option>
							</select>
						</div>
						<div class='grid5'>
						
						
							<input type='text' name='amount' />

						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SJD</label></div>
                        <div class="grid4 noSearch">
							<select name='sjt_classification' class='select' style='width:100%'>
								<option value='shortage' <?php if($sjt_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
								<option value='overage' <?php if($sjt_classification=="overage"){ echo "selected"; } ?>>Overage</option>
							</select>
						</div>
						<div class='grid5'>

						<input type='text' name='amount' />

						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SVT</label></div>
                        <div class="grid4 noSearch">
						<select name='sjt_classification' class='select' style='width:100%'>
							<option value='shortage' <?php if($sjt_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
							<option value='overage' <?php if($sjt_classification=="overage"){ echo "selected"; } ?>>Overage</option>
						</select>
						</div>
						<div class='grid5'>
						<input type='text' name='amount' />

						</div>
                        <div class="clear"></div>
                    </div>					
                    <div class="formRow">
                        <div class="grid3"><label>SVD</label></div>
                        <div class="grid4 noSearch">
						<select name='sjt_classification' class='select' style='width:100%'>
							<option value='shortage' <?php if($sjt_classification=="shortage"){ echo "selected"; } ?>>Shortage</option>
							<option value='overage' <?php if($sjt_classification=="overage"){ echo "selected"; } ?>>Overage</option>
						</select>
						</div>
						<div class='grid5'>
							<input type='text' name='amount' />
						</div>
                        <div class="clear"></div>
                    </div>


				</div>	
							
				
			</fieldset>
		</form>

</div>
<div id="control_allocation" name='control_allocation' class="customDialog" title="Initial Allocation">
<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-4'>&nbsp;</td>
<td class='col-md-4'>Pieces</td>
<td class='col-md-4'>Loose</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' /></td>
<td><input type='text' /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' /></td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text' /></td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text' /></td>
<td><input type='text' /></td>

</tr>


</table>
</div>
<div id="control_sold" name='control_sold' class="customDialog" title="Tickets Sold">
<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-3'>&nbsp;</td>
<td class='col-md-9'>Pieces</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text' /></td>

</tr>


</table>
</div>
<div id="control_amount" name='control_amount' class="customDialog" title="Tickets Sold">
<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-3'>&nbsp;</td>
<td class='col-md-9'>Amount</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text' /></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text' /></td>

</tr>


</table>
</div>