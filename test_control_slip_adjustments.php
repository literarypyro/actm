	<?php
		$db=new mysqli("localhost","root","","finance");
	?>
	<?php
		if(isset($_POST['fare_adjustment_id'])){
			$control_id=$_POST['fare_adjustment_id'];
			
			$sjt_adjustment=$_POST['sjt_adj_amt'];
			$sjd_adjustment=$_POST['sjd_adj_amt'];
			$svt_adjustment=$_POST['svt_adj_amt'];
			$svd_adjustment=$_POST['svd_adj_amt'];
			$c_adjustment=$_POST['c_adj_amt'];
			$ot_adjustment=$_POST['ot_adj_amt'];

			$sjt_adjustment_t=$_POST['sjt_adj_qty'];
			$sjd_adjustment_t=$_POST['sjd_adj_qty'];
			$svt_adjustment_t=$_POST['svt_adj_qty'];
			$svd_adjustment_t=$_POST['svd_adj_qty'];
			$c_adjustment_t=$_POST['c_adj_qty'];
			$ot_adjustment_t=$_POST['ot_adj_qty'];


			
			$sql="select * from fare_adjustment where control_id='".$control_id."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm==0){
				$sql="insert into fare_adjustment(control_id,sjt,sjd,svt,svd,c,ot) values ";
				$sql.="('".$control_id."','".$sjt_adjustment."','".$sjd_adjustment."','".$svt_adjustment."','".$svd_adjustment."','".$c_adjustment."','".$ot_adjustment."')";
				$rs=$db->query($sql);
			}	
			else {
				$sql="update fare_adjustment set c='".$c_adjustment."',ot='".$ot_adjustment."',sjt='".$sjt_adjustment."',sjd='".$sjd_adjustment."',svt='".$svt_adjustment."',svd='".$svd_adjustment."' where control_id='".$control_id."'";
				$rs=$db->query($sql);	
			
			}

			$sql="select * from fare_adjustment_tickets where control_id='".$control_id."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm==0){
				$sql="insert into fare_adjustment_tickets(control_id,sjt,sjd,svt,svd,c,ot) values ";
				$sql.="('".$control_id."','".$sjt_adjustment_t."','".$sjd_adjustment_t."','".$svt_adjustment_t."','".$svd_adjustment_t."','".$c_adjustment_t."','".$ot_adjustment_t."')";
				$rs=$db->query($sql);

			}	
			else {
				$sql="update fare_adjustment_tickets set c='".$c_adjustment_t."',ot='".$ot_adjustment_t."',sjt='".$sjt_adjustment_t."',sjd='".$sjd_adjustment_t."',svt='".$svt_adjustment_t."',svd='".$svd_adjustment_t."' where control_id='".$control_id."'";
				$rs=$db->query($sql);	
			}				


			
			
		
		}
		if(isset($_POST['refund_id'])){
			$control_id=$_POST['refund_id'];
			$refund_sj=$_POST['sj_refund_qty'];
			$refund_sv=$_POST['sv_refund_qty'];
			$refund_sj_amount=$_POST['sj_refund_amt'];
			$refund_sv_amount=$_POST['sv_refund_amt'];
			
			
			$sql="select * from refund where control_id='".$control_id."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm==0){
				$update="insert into refund(control_id,sj,sv,sj_amount,sv_amount) values ('".$control_id."','".$refund_sj."','".$refund_sv."','".$refund_sj_amount."','".$refund_sv_amount."')";
				$updateRS=$db->query($update);

			}
			else {
				$row=$rs->fetch_assoc();
				$update="update refund set sj='".$refund_sj."',sv='".$refund_sv."',sj_amount='".$refund_sj_amount."',sv_amount='".$refund_sv_amount."' where id='".$row['id']."'";
				$updateRS=$db->query($update);
			}	
				
		
		}
		
		if(isset($_POST['discount_id'])){
			$control_id=$_POST['discount_id'];
			
			$discount_sj=$_POST['sj_discount'];
			$discount_sv=$_POST['sv_discount'];
			
			$sql="select * from discount where control_id='".$control_id."'";

			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm==0){
				$update="insert into discount(control_id,sj,sv) values ('".$control_id."','".$discount_sj."','".$discount_sv."')";
				$updateRS=$db->query($update);
			}
			else {
				$row=$rs->fetch_assoc();
				$update="update discount set sj='".$discount_sj."',sv='".$discount_sv."' where id='".$row['id']."'";
				$updateRS=$db->query($update);

			}
			
			
			
			
			
			
			



		}
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
<table>
<tr>
<td>
<div class="widget fluid" style='width:500px;'>
    <div class="formRow">
        <div class="grid5"><label>Total Amount</label></div>
		<div class="grid5"><input type="text" name="total_amount"  value='<?php echo $total_amount; ?>' /></div>
        <div class="clear"></div>
    </div>
	<?php $cash_revenue_3=$total_amount+$cash_adjustments; ?>	

	
	<div class="formRow">
		<div class="grid5"><label>Fare Adjustment</label></div>

		<div class="grid4"><input type="text" name="fare_adjustment" readonly='readonly' value='<?php echo $cash_adjustments; ?>'  />
		</div>
		<div class='grid1'>
		<a href="#" title='Edit' id="fa_open"><i class='icos-pencil'></i></a>

            <div id="fare_adjustment_modal" style='display:none;' title="Fare Adjustment">
                <form action="test_control_slip.php" method='post' name='fare_adjustment_form' id='fare_adjustment_form' class='form_class'>
							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								</tr>
								<tr>
								<td>SJT</td>
								<td><input type="text" name="sjt_adj_qty" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sjt_adj_amt" class="clear" placeholder="Enter Amount" value='<?php echo $sjt_adjustment; ?>' /></td>
								</tr>		
								<tr>
								<td>SJD</td>
								<td><input type="text" name="sjd_adj_qty" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="sjd_adj_amt" class="clear" placeholder="Enter Amount" value='<?php echo $sjd_adjustment; ?>'  /></td>
								</tr>		
								<tr>
								<td>SVT</td>
								<td><input type="text" name="svt_adj_qty" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="svt_adj_amt" class="clear" placeholder="Enter Amount" value='<?php echo $svt_adjustment; ?>'  /></td>

								</tr>		
								<tr>
								<td>SVD</td>
								<td><input type="text" name="svd_adj_qty" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="svd_adj_amt" class="clear" placeholder="Enter Amount" value='<?php echo $svd_adjustment; ?>'  /></td>

								</tr>		
								<tr>
								<td>C</td>
								<td><input type="text" name="c_adj_qty" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="c_adj_amt" class="clear" placeholder="Enter Amount" value='<?php echo $c_adjustment; ?>'  /></td>
								</tr>		
								<tr>
								<td>OT</td>
								<td><input type="text" name="ot_adj_qty" class="clear" placeholder="Enter Quantity" /></td>
								<td><input type="text" name="ot_adj_amt" class="clear" placeholder="Enter Amount" value='<?php echo $ot_adjustment; ?>'  /></td>
								</tr>		
							</table>
							
							<input type=hidden name='fare_adjustment_id' value='<?php echo $control_id; ?>' />
							
							
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
		<div class='grid1'><a href='#' title='Taken From CTF Allocation'><i class='icos-cog'></i></a></div>
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
		
		
            <div id="refund_modal" style='display:none;' title="Add Refund">
							
				<form method='post' action='test_control_slip.php' id='refund_form' name='refund_form'>
							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								
								</tr>
								<tr>
								<td>SJ</td>
								<td><input type="text" name="sj_refund_qty" class="clear" placeholder="Enter Quantity" value='<?php echo $sj_refund; ?>' /></td>
								<td><input type="text" name="sj_refund_amt" class="clear" placeholder="Enter Amount"  value='<?php echo $sj_refund_amount; ?>' /></td>

								</tr>		
								<tr>
								<td>SV</td>
								<td><input type="text" name="sv_refund_qty" class="clear" placeholder="Enter Quantity" value='<?php echo $sv_refund; ?>' /></td>
								<td><input type="text" name="sv_refund_amt" class="clear" placeholder="Enter Amount" value='<?php echo $sv_refund_amount; ?>' /></td>

								</tr>		
							</table>
							<input type='hidden' name='refund_id' value='<?php echo $control_id; ?>' />
				</form>
		
		
		
		
		
		
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

		
		
            <div id="discount_modal" style='display:none;' title="Add Discount">

						<form action='test_control_slip.php' method='post' name='discount_form' id='discount_form'>
							<table style='width:100%'>
								<tr>
									<th>Type</th>
									<th style='text-align:center'>Quantity</th>
								
								</tr>
								<tr>
								<td>SJ</td>
								<td><input type="text" name="sj_discount" class="clear" placeholder="Enter Quantity"  value='<?php echo $sj_discount; ?>' /></td>

								</tr>		
								<tr>
								<td>SV</td>
								<td><input type="text" name="sv_discount" class="clear" placeholder="Enter Quantity" value='<?php echo $sv_discount; ?>'  /></td>

								</tr>		
							</table>
							<input type=hidden name='discount_id' value='<?php echo $control_id; ?>'>
						</form>
		
		
		
		
		
		
			</div>	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		</div>

		
        <div class="clear"></div>
    </div>
	
	<div class="formRow">
		<div class="grid5"><label>Initial Remittance</label></div>

		<div class="grid5"><input type="text" name="net_remittance" id='net_remittance' readonly='readonly' value='<?php echo $cash_revenue_3; ?>' /></div>
        <div class="clear"></div>
    </div>
	
	<?php 
	$sql="select * from cash_transfer where type='remittance' and control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	
	
	<?php 
	if($nm>0){
	}
	else {
	?>
	<div class="formRow" style='display:none;' name='open_remit' id='open_remit'>
		<div class="grid10" align=center><a href='#' name='open_ctf2' id='open_ctf2' class='btn btn-primary'>Open Remittance CTF</a></div>
        <div class="clear"></div>
    </div>
	<?php
	}
	?>
	
</div>
</td>	
<?php
if($nm>0){
?>
<td style='width:50px;'>&nbsp;
</td>
<td>
<?php
$control_id=$_SESSION['control_id'];
$sql="select * from unreg_sale where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_unreg=$row['sjt']+$row['sjd'];
	$sv_unreg=$row['svt']+$row['svt'];

}
$sql="select * from control_cash where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$overage=$row['overage'];
	$unpaid_shortage=$row['unpaid_shortage'];


	
}

	$cash_revenue_3+=$overage;

	$cash_revenue_3+=$sj_unreg;
	$cash_revenue_3+=$sv_unreg;
	
	$cash_revenue_3-=$unpaid_shortage;
	
	$cash_final=$cash_revenue_3;
?>

<div class="widget fluid" style='float:right; position:absolute; width:500px;'>
	
	<div class="formRow">
		<div class="grid10"><label>Others</label></div>

        <div class="clear"></div>
    </div>

	<div class="formRow">
		<div class="grid5"><label>(Add) Overage</label></div>
		<div class="grid5"><label><?php echo $overage; ?></label></div>
        <div class="clear"></div>
    </div>
	<div class="formRow">
		<div class="grid10"><label>(Add) Unreg Sale</label></div>

        <div class="clear"></div>
    </div>
	<div class="formRow">
		<div class="grid3" style='text-align:center;'><label>SJ</label></div>
		<div class="grid2"><label><?php echo $unreg_sj; ?></label></div>

		<div class="grid3" style='text-align:center;'><label>SV</label></div>
		<div class="grid2"><label><?php echo $unreg_sv; ?></label></div>

    </div>

	<div class="formRow">
		<div class="grid5"><label>Unpaid Shortage</label></div>
		<div class="grid5"><label><?php echo $unpaid_shortage; ?></label></div>
        <div class="clear"></div>
    </div>

	<div class="formRow">
		<div class="grid5"><label>Final Remittance</label></div>
		<div class="grid5"><label><?php echo $cash_final; ?></label></div>
        <div class="clear"></div>
    </div>
	
	
</div>
</td>
<?php
}
?>					
</tr>
</table>