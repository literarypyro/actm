<?php
$control_id=$_SESSION['control_id'];

$sql="select * from control_unsold where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$unsoldNM=$nm;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
	$unsold[$row['type']]["sealed"]=$row['sealed'];
	$unsold[$row['type']]["loose_good"]=$row['loose_good'];
	$unsold[$row['type']]["loose_defective"]=$row['loose_defective'];

	$total_unsold_a+=$unsold[$row['type']]['sealed'];
	$total_unsold_b+=$unsold[$row['type']]['loose_good'];
	$total_unsold_c+=$unsold[$row['type']]['loose_defective'];

	$unsold[$row['type']]['total']=$row['sealed']*1+$row['loose_good']*1+$row['loose_defective']*1;
}
?>





<div id="control_unsold" name='control_unsold' class="customDialog" title="Unsold/Excess">
<form action='test_control_slip.php' method='post' id='unsold_form2' name='unsold_form2'>

<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-3'>&nbsp;</td>
<td class='col-md-3'>Sealed</td>
<td class='col-md-3'>Loose Good</td>
<td class='col-md-3'>Loose Defective</td>
</tr>
<tr>
<td>SJT</td>
<td><input name='sjt_unsold_a' id='sjt_unsold_a' type='text'  value='<?php echo $unsold['sjt']['sealed']; ?>' /></td>
<td><input type='text' name='sjt_unsold_b'  value='<?php echo $unsold['sjt']['loose_good']; ?>'/></td>
<td><input type='text' name='sjt_unsold_c'  value='<?php echo $unsold['sjt']['loose_defective']; ?>'/></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' name='sjd_unsold_a'  value='<?php echo $unsold['sjd']['sealed']; ?>' /></td>
<td><input type='text' name='sjd_unsold_b'  value='<?php echo $unsold['sjd']['loose_good']; ?>'/></td>
<td><input type='text' name='sjd_unsold_c'  value='<?php echo $unsold['sjd']['loose_defective']; ?>'/></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text' name='svt_unsold_a'  value='<?php echo $unsold['svt']['sealed']; ?>' /></td>
<td><input type='text' name='svt_unsold_b'  value='<?php echo $unsold['svt']['loose_good']; ?>'/></td>
<td><input type='text' name='svt_unsold_c'  value='<?php echo $unsold['svt']['loose_defective']; ?>'/></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text' name='svd_unsold_a'  value='<?php echo $unsold['svd']['sealed']; ?>' /></td>
<td><input type='text' name='svd_unsold_b'  value='<?php echo $unsold['svd']['loose_good']; ?>'/></td>
<td><input type='text' name='svd_unsold_c'  value='<?php echo $unsold['svd']['loose_defective']; ?>'/>

</td>

</tr>


</table>
<input type=hidden name='unsold_id' value='<?php echo $control_id; ?>' />
</form>

</div>


<?php
$sql="select * from discrepancy_ticket where transaction_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		if($i==0){
			$classification=$row['classification'];
			$reference_id=$row['reference_id'];
		
		}
		
		if($row['ticket_type']=="sjt"){
			$sjt_classification=$row['type'];
			$sjt_amount=$row['amount'];
			$sjt_price=$row['price'];
			
			
		}
		else if($row['ticket_type']=="sjd"){
			$sjd_classification=$row['type'];
			$sjd_amount=$row['amount'];
			$sjd_price=$row['price'];
		
		
		}
		else if($row['ticket_type']=="svt"){
			$svt_classification=$row['type'];
			$svt_amount=$row['amount'];
		
			$svt_price=$row['price'];
		
		}
		else if($row['ticket_type']=="svd"){
			$svd_classification=$row['type'];
			$svd_amount=$row['amount'];
			$svd_price=$row['price'];

		}
	}
}




?>






<?php 

$sql="select * from allocation where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;

$allocationNM=$nm;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
	$allocation[$row['type']]["initial"]=$row['initial'];
	$total_allocation_a+=$allocation[$row['type']]["initial"];
	
	$allocation[$row['type']]["initial_loose"]=$row['initial_loose'];
	$total_allocation_a_loose+=$allocation[$row['type']]["initial_loose"];

	$allocation[$row['type']]["additional"]=$row['additional'];
	$allocation[$row['type']]["additional_loose"]=$row['additional_loose'];
	$allocation[$row['type']]["total"]=$row['initial']*1+$row['additional']*1+$row['initial_loose']*1+$row['additional_loose']*1;
	$total_allocation_b+=$allocation[$row['type']]["additional"];

	$total_allocation_b_loose+=$allocation[$row['type']]["additional_loose"];
}

$sql="select * from additional_allocation where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;


$total_allocation_b=0;


$total_allocation_b_loose=0;


for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$allocation['sjt']['additional']+=$row['sjt'];
	$allocation['svt']['additional']+=$row['svt'];
	$allocation['sjd']['additional']+=$row['sjd'];
	$allocation['svd']['additional']+=$row['svd'];

	$allocation['sjt']['additional_loose']+=$row['sjt_loose'];
	$allocation['svt']['additional_loose']+=$row['svt_loose'];
	$allocation['sjd']['additional_loose']+=$row['sjd_loose'];
	$allocation['svd']['additional_loose']+=$row['svd_loose'];

	$total_allocation_b+=$allocation['sjt']["additional"];
	$total_allocation_b+=$allocation['sjd']["additional"];
	$total_allocation_b+=$allocation['svt']["additional"];
	$total_allocation_b+=$allocation['svd']["additional"];

	$total_allocation_b_loose+=$allocation['sjd']["additional_loose"];
	$total_allocation_b_loose+=$allocation['sjt']["additional_loose"];
	$total_allocation_b_loose+=$allocation['svd']["additional_loose"];
	$total_allocation_b_loose+=$allocation['svt']["additional_loose"];
}






?>


<div id="control_allocation" name='control_allocation' class="customDialog" title="Initial Allocation">
<form action='test_control_slip.php' method='post' id='allocation_form2' name='allocation_form2'>

<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-4'>&nbsp;</td>
<td class='col-md-4'>Pieces</td>
<td class='col-md-4'>Loose</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' name='sjt_allocation_a' value='<?php echo $allocation['sjt']['initial']; ?>' />
<?php if($allocation['sjt']['additional']>0){ echo "<font color=green>(".$allocation['sjt']['additional'].")</font>"; } ?>

</td>
<td><input type='text' name='sjt_allocation_a_loose'  value='<?php echo $allocation['sjt']['initial_loose']; ?>'/>
<?php if($allocation['sjt']['additional_loose']>0){ echo "<font color=green>(".$allocation['sjt']['additional_loose'].")</font>"; } ?>



</td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' name='sjd_allocation_a'  value='<?php echo $allocation['sjd']['initial']; ?>' />
<?php if($allocation['sjd']['additional']>0){ echo "<font color=green>(".$allocation['sjd']['additional'].")</font>"; } ?>


</td>
<td><input type='text'  name='sjd_allocation_a_loose'  value='<?php echo $allocation['sjd']['initial_loose']; ?>' />
<?php if($allocation['sjd']['additional_loose']>0){ echo "<font color=green>(".$allocation['sjd']['additional_loose'].")</font>"; } ?>

</td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text'   name='svt_allocation_a'  value='<?php echo $allocation['svt']['initial']; ?>' />
<?php if($allocation['svt']['additional']>0){ echo "<font color=green>(".$allocation['svt']['additional'].")</font>"; } ?>

</td>
<td><input type='text'  name='svt_allocation_a_loose'  value='<?php echo $allocation['svt']['initial_loose']; ?>' />
<?php if($allocation['svt']['additional_loose']>0){ echo "<font color=green>(".$allocation['svt']['additional_loose'].")</font>"; } ?>


</td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text'  name='svd_allocation_a'  value='<?php echo $allocation['svd']['initial']; ?>' />
<?php if($allocation['svd']['additional']>0){ echo "<font color=green>(".$allocation['svd']['additional'].")"; } ?>

</td>
<td><input type='text'   name='svd_allocation_a_loose'  value='<?php echo $allocation['svd']['initial_loose']; ?>' /></td>
<?php if($allocation['svd']['additional']>0){ echo "<font color=green>(".$allocation['svd']['additional'].")</font>"; } ?>

</tr>


</table>
<input type=hidden name='allocation_id' value='<?php echo $control_id; ?>' />

</form>

</div>

<?php

$sql="select * from control_sold where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$row=$rs->fetch_assoc();

if($unsoldNM==0){
	$sold_tickets["sjt"]=$allocation["sjt"]["initial"]+$allocation["sjt"]["initial_loose"]+$allocation['sjt']["additional"]+$allocation['sjt']["additional_loose"];
	$sold_tickets["sjd"]=$allocation["sjd"]["initial"]+$allocation["sjd"]["initial_loose"]+$allocation['sjd']["additional"]+$allocation['sjd']["additional_loose"];
	$sold_tickets["svt"]=$allocation["svt"]["initial"]+$allocation["svt"]["initial_loose"]+$allocation['svt']["additional"]+$allocation['svt']["additional_loose"];
	$sold_tickets["svd"]=$allocation["svd"]["initial"]+$allocation["svd"]["initial_loose"]+$allocation['svd']["additional"]+$allocation['svd']["additional_loose"];
	
}
else {
	$sold_tickets["sjt"]=$row['sjt']*1;
	$sold_tickets["sjd"]=$row['sjd']*1;
	$sold_tickets["svt"]=$row['svt']*1;
	$sold_tickets["svd"]=$row['svd']*1;

}


$sql="select * from discrepancy_ticket where transaction_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$discrepancyLabel['sjt']="";
$discrepancyLabel['sjd']="";
$discrepancyLabel['svt']="";
$discrepancyLabel['svd']="";

$discrepancy_classification['sjt']="";
$discrepancy_classification['sjd']="";
$discrepancy_classification['svt']="";
$discrepancy_classification['svd']="";

$discrepancy['sjt']=0;
$discrepancy['sjd']=0;
$discrepancy['svt']=0;
$discrepancy['svd']=0;

if($nm>0){
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		if(($row['amount']*1)>0){
			$discrepancy_classification[$row['ticket_type']]=$row['type'];
			$discrepancy_price[$row['ticket_type']]=$row['price'];
			
			if($row['type']=="shortage"){
				$discrepancyLabel[$row['ticket_type']]="<font color=red>(-".$row['amount'].")</font>";		
				$sold_tickets[$row['ticket_type']]-=$row['amount'];
				$discrepancy[$row['ticket_type']]=$row['amount']*1;

				
			}
			else if($row['type']=="overage"){
				$discrepancyLabel[$row['ticket_type']]="<font color=green>(+".$row['amount'].")</font>";		
				$sold_tickets[$row['ticket_type']]+=$row['amount'];
				$discrepancy[$row['ticket_type']]=$row['amount']*1;

			}
		}	
	}		
}			


?>


<div id="control_discrepancy" name='control_discrepancy' class="customDialog" title="Discrepancy Report">
        <form action="test_control_slip.php" method='post' name='discrepancy_form' id='discrepancy_form' class="main">
            <fieldset>
                <div class="widget fluid grid3">
					<div class="formRow">
						<div class="grid3"><label>Reference ID</label></div>

                        <div class="grid9">
							<input type='text' name='reference_id' />
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
                        <div class="grid3"><label>Ticket Type</label></div>
                        <div class="grid3"><label>Discrepancy</label></div>
                        <div class="grid3"><label>Quantity</label></div>
                        <div class="grid3"><label>Amount</label></div>

                    </div>

					
                    <div class="formRow">
                        <div class="grid3"><label>SJT</label></div>
                        <div class="grid3 noSearch">
							<select name='sjt_classification' class='select' style='width:100%'>
								<option value='shortage' <?php if($discrepancy_classification['sjt']=="shortage"){ echo "selected"; } ?>>Shortage</option>
								<option value='overage' <?php if($discrepancy_classification['sjt']=="overage"){ echo "selected"; } ?>>Overage</option>
							</select>
						</div>
						<div class='grid3'>
						
						
							<input type='text' name='sjt_disc_amount' value='<?php echo $discrepancy['sjt']; ?>'/>

						</div>
						<div class='grid3'>
						
						
							<input type='text' name='sjt_price' value='<?php echo $discrepancy_price['sjt']; ?>'/>

						</div>

                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SJD</label></div>
                        <div class="grid3 noSearch">
							<select name='sjd_classification' class='select' style='width:100%'>
								<option value='shortage' <?php if($discrepancy_classification['sjd']=="shortage"){ echo "selected"; } ?>>Shortage</option>
								<option value='overage' <?php if($discrepancy_classification['sjd']=="overage"){ echo "selected"; } ?>>Overage</option>
							</select>
						</div>
						<div class='grid3'>

						<input type='text' name='sjd_disc_amount' value='<?php echo $discrepancy['sjd']; ?>' />

						</div>
						<div class='grid3'>
						
						
							<input type='text' name='sjd_price' value='<?php echo $discrepancy_price['sjd']; ?>'/>

						</div>

                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SVT</label></div>
                        <div class="grid3 noSearch">
						<select name='svt_classification' class='select' style='width:100%'>
							<option value='shortage' <?php if($discrepancy_classification['svt']=="shortage"){ echo "selected"; } ?>>Shortage</option>
							<option value='overage' <?php if($discrepancy_classification['svt']=="overage"){ echo "selected"; } ?>>Overage</option>
						</select>
						</div>
						<div class='grid3'>
						<input type='text' name='svt_disc_amount'  value='<?php echo $discrepancy['svt']; ?>' />

						</div>
						<div class='grid3'>
						
						
							<input type='text' name='svt_price' value='<?php echo $discrepancy_price['svt']; ?>'/>

						</div>

                        <div class="clear"></div>
                    </div>					
                    <div class="formRow">
                        <div class="grid3"><label>SVD</label></div>
                        <div class="grid3 noSearch">
						<select name='svd_classification' class='select' style='width:100%'>
							<option value='shortage' <?php if($discrepancy_classification['svd']=="shortage"){ echo "selected"; } ?>>Shortage</option>
							<option value='overage' <?php if($discrepancy_classification['svd']=="overage"){ echo "selected"; } ?>>Overage</option>
						</select>
						</div>
						<div class='grid3'>
							<input type='text' name='svd_disc_amount'  value='<?php echo $discrepancy['svd']; ?>' />
						</div>
						<div class='grid3'>
						
						
							<input type='text' name='svd_price' value='<?php echo $discrepancy_price['svd']; ?>'/>

						</div>

                        <div class="clear"></div>
                    </div>


				</div>	
							
				<input type=hidden name='discrepancy_id' value='<?php echo $control_id; ?>' />

			</fieldset>
		</form>

</div>



<div id="control_sold" name='control_sold' class="customDialog" title="Tickets Sold">
<form action='test_control_slip.php' method='post' id='sold_form' name='sold_form' >

<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-3'>&nbsp;</td>
<td class='col-md-9'>Pieces</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' name='sjt_sold' value='<?php echo $sold_tickets['sjt']; ?>' /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' name='sjd_sold'  value='<?php echo $sold_tickets['sjd']; ?>' /></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text' name='svt_sold'  value='<?php echo $sold_tickets['svt']; ?>' /></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text'  name='svd_sold' value='<?php echo $sold_tickets['svd']; ?>' /></td>

</tr>


</table>
<input type=hidden name='sold_id' value='<?php echo $control_id; ?>' />

</form>
 
</div>
<?php
$sql="select * from control_sales_amount where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$sjt_amount=$row['sjt'];
	$sjd_amount=$row['sjd'];
	$svt_amount=$row['svt'];
	$svd_amount=$row['svd'];
	
	$cash_revenue_1=$sjt_amount*1+$sjd_amount*1+$svt_amount*1+$svd_amount*1;

	$total_amount+=$row['sjt'];
	$total_amount+=$row['sjd'];
	$total_amount+=$row['svt'];
	$total_amount+=$row['svd'];
}
else {
	$svt_amount=$sold_tickets["svt"]*100;
	$svd_amount=$sold_tickets["svd"]*100;

	$total_amount+=$row['svt'];
	$total_amount+=$row['svd'];
	
}
?>


<div id="control_amount" name='control_amount' class="customDialog" title="Tickets Amount">
<form action='test_control_slip.php' method='post' name='amount_form' id='amount_form'>

<table width=100% class="tDefault checkAll tMedia" id="checkAll">
<tr>
<td class='col-md-3'>&nbsp;</td>
<td class='col-md-9'>Amount</td>
</tr>
<tr>
<td>SJT</td>
<td><input type='text' name='sjt_amount' value='<?php echo $sjt_amount; ?>' /></td>
</tr>
<tr>
<td>SJD</td>
<td><input type='text' name='sjd_amount'  value='<?php echo $sjd_amount; ?>'  /></td>

</tr>
<tr>
<td>SVT</td>
<td><input type='text'  name='svt_amount'  value='<?php echo $svt_amount; ?>' /></td>

</tr>
<tr>
<td>SVD</td>
<td><input type='text'  name='svd_amount'  value='<?php echo $svd_amount; ?>' /></td>

</tr>


</table>
<input type=hidden name='amount_id' value='<?php echo $control_id; ?>' />
</form>
</div>
