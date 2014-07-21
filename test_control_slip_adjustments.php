
		<?php
		$db=new mysqli("localhost","root","","finance");
		?>
		
		
		<?php
		$control_id=$_SESSION['control_id'];

		$sql="select * from control_slip where id='".$control_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();

		?>
<?php
$sql="select * from fare_adjustment where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	//$fare_adjustment=$row['fare_adjustment'];
	$sjt_adjustment=$row['sjt'];
	$sjd_adjustment=$row['sjd'];
	$svt_adjustment=$row['svt'];
	$svd_adjustment=$row['svd'];
	$c_adjustment=$row['c'];
	$ot_adjustment=$row['ot'];
	
	$cash_adjustments=0;
	//$cash_adjustments+=$fare_adjustment*1;
	$cash_adjustments+=$sjt_adjustment*1;
	$cash_adjustments+=$sjd_adjustment*1;
	$cash_adjustments+=$svt_adjustment*1;
	$cash_adjustments+=$svd_adjustment*1;
	$cash_adjustments+=$c_adjustment*1;
	$cash_adjustments+=$ot_adjustment*1;
	
}
?>
<div class="widget fluid" style='width:500px;'>
    <div class="formRow">
        <div class="grid5"><label>Total Amount</label></div>
		<div class="grid5"><input type="text" name="total_amount"  value='<?php echo $total_amount; ?>' /></div>
        <div class="clear"></div>
    </div>
	<?php $cash_revenue_3=$total_amount; ?>	

	
	<div class="formRow">
		<div class="grid5"><label>Fare Adjustment</label></div>

		<div class="grid4"><input type="text" name="fare_adjustment" readonly='readonly' value='<?php $cash_adjustments; ?>'  />
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
	<?php
	$sql="select sum(total) as total from cash_transfer where control_id='".$control_id."' and type in ('allocation')";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cash_advance=$row['total'];
		$cash_revenue_3+=$cash_advance;
	}

	?>
	
	
	
	
	
	<div class="formRow">
		<div class="grid5"><label>Cash Advance</label></div>

		<div class="grid4"><input type="text" name="cash_advance" readonly='readonly'  value='<?php echo $cash_advance; ?>' />
		</div>
		<div class='grid1'><a href='#' title='Track'><i class='icos-cog'></i></a></div>
        <div class="clear"></div>
    </div>
	
<?php	
$sql="select * from refund where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_refund=$row['sj'];
	$sv_refund=$row['sv'];

	$sj_refund_amount=$row['sj_amount'];
	$sv_refund_amount=$row['sv_amount'];

	$cash_revenue_3-=$sj_refund_amount;
	$cash_revenue_3-=$sv_refund_amount;
	
	
}

	
?>	
	
	
	
	
	
	<div class="formRow">
		<div class="grid5"><label>Refund</label></div>

		<div class="grid4"><input type="text" name="refund"  readonly='readonly' value='<?php echo $sj_refund_amount+$sv_refund_amount; ?>' />
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
	
	<?php	
	$sql="select * from discount where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$sj_discount=$row['sj'];
		$sv_discount=$row['sv'];

		
		$cash_revenue_3-=$sj_discount;
		$cash_revenue_3-=$sv_discount;

	}
	?>
	
	<div class="formRow">
		<div class="grid5"><label>Discount</label></div>

		<div class="grid4"><input type="text" name="discount" readonly='readonly' value='<?php echo $sj_discount+$sv_discount; ?>' /></div>
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
		<div class="grid5"><label>Initial Remittance</label></div>

		<div class="grid5"><input type="text" name="net_remittance" readonly='readonly' value='<?php echo $cash_revenue_3; ?>' /></div>
        <div class="clear"></div>
    </div>
	
	
</div>	
					
