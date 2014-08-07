<?php
require("calculateInWords.php");
?>
<?php
$db=retrieveDb();
?>
<?php


?>
<script language='javascript'>
function amountCalculate(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	
	$('#ctf_denom #'+textAmount).val(amount);
//	document.getElementById().value=amount;	

	calculateTotal();
	
	if(e.keyCode==13){
//		document.getElementById(nextField).focus();
		$('#ctf_denom #'+nextField).focus();	
			
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	
}

function calculateTotal(){
	var total=0;
	for(i=1;i<13;i++){
		total+=($('#ctf_denom #amount'+i).val())*1;

	}
	$('#ctf_denom #cash_total').val(Math.round(total*100)/100);
	var cash_total=$('#ctf_denom #cash_total').val();
	calculateNumber(Math.round(total*100)/100,"total_in_pesos");	
	
	var rev=$('#revolving_remittance').val();
	var type=$('#type').val();
	if((type=="partial_remittance")||(type=='remittance')){
		getCashAdvance($('#cs_ticket_seller').val());
	
	}
	else if((type=="allocation")){
		document.getElementById('revolving_remittance').value=document.getElementById('cash_total').value;	
		document.getElementById('for_deposit').value=0;
		
	}
	else if(type=='shortage'){
		document.getElementById('for_deposit').value=document.getElementById('cash_total').value;	
		document.getElementById('revolving_remittance').value=0;
	
	}
	else {
		document.getElementById('revolving_remittance').value="";
		document.getElementById('for_deposit').value="";
	}
}

function amountCalculate2(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	
	$('#pnb_denom #'+textAmount).val(amount);
//	document.getElementById().value=amount;	

//	calculateTotal();
	var total=0;
	for(i=1;i<13;i++){
		total+=($('#pnb_denom #amount'+i).val())*1;

	}
	$('#pnb_denom #cash_total').val(Math.round(total*100)/100);
	
	if(e.keyCode==13){
//		document.getElementById(nextField).focus();
		$('#pnb_denom #'+nextField).focus();	
	}	
}

function amountCalculate3(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	
	$('#shortage_denom #'+textAmount).val(amount);
//	document.getElementById().value=amount;	

//	calculateTotal();
	var total=0;
	for(i=1;i<13;i++){
		total+=($('#shortage_denom #amount'+i).val())*1;

	}
	$('#shortage_total').val(Math.round(total*100)/100);
	
	if(e.keyCode==13){
//		document.getElementById(nextField).focus();
		$('#shortage_denom #'+nextField).focus();	
	}	
}

function calculateTotal2(){
	var total=0;
	for(i=1;i<13;i++){
		total+=($('#pnb_denom #amount'+i).val())*1;

	}
	$('#pnb_denom #cash_total').val(Math.round(total*100)/100);

}

</script>

<?php
if(isset($_GET['edit_control'])){
	$control_id=$_GET['edit_control'];
	$control_post=$control_id;
	$db=retrieveDb();
	$sql="select * from control_slip where id='".$control_id."'";
	
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$ticket_seller=$row['ticket_seller'];
	$unit=$row['unit'];
	$transactType="remittance";
	$ticketsellerpost=$ticket_seller;
	$station=$row['station'];
	
	$sql="select sum(total) as total from cash_transfer where log_id in (select log_id from control_tracking where control_id='".$control_id."') and ticket_seller='".$ticket_seller."' and unit='".$unit."' and type in ('allocation')";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cash_advance=$row['total'];
	}
	
	$sql="select sum(total) as revolving from cash_transfer where control_id='".$control_id."' and type in ('partial_remittance')";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$revolving=$row['revolving'];
		
		$cash_advance-=$revolving*1;
	}	
	
	
	
	$revolvingpost=$cash_advance;
}	
?>
					<div id="cash_transfer_modal" name='cash_transfer_modal' title="Cash Transfer Form" style='display:none;'>
								<form autocomplete='off'  action='<?php echo $_SERVER['PHP_SELF'];  ?>' method='post' name='ctf_form' id='ctf_form'>
								<input type='hidden' name='form_action' id='form_action' value='new' class='form_action'/>
								<input type='hidden' name='ctf_transaction_id' id='ctf_transaction_id' />
								
								<table class='tDefault' style='width:100%'>
								<tr>
							
									<td valign=top class='grid3'>Cash Assistant</td>
                                    <td class='grid3 searchDrop'>
									<select id='cash_assistant' name="cash_assistant" class='select' style='width:200px;' >
									<?php
									$db=retrieveDb();
									$sql="select * from login order by lastName";
									$rs=$db->query($sql);
									$nm=$rs->num_rows;
									for($i=0;$i<$nm;$i++){
									$row=$rs->fetch_assoc();
									?>
									<option value='<?php echo $row['username']; ?>' 
									<?php 
									if($cash_assist==""){
										if($row['username']==$_SESSION['username']){
											echo "selected";
										}
									}
									else {
										if($row['username']==$cash_assist){
										
											echo "selected";
										}

									}
									?> 
									>
									<?php
									echo strtoupper($row['lastName']).", ".$row['firstName'];
									?>
									</option>
									<?php
									}
									?>


                                    </select>
									</td>
                                </tr>

								<tr>
									<?php
									$db=retrieveDb();
									
									$sql="select control_slip.id as control_id,control_slip.*,ticket_seller.* from control_slip inner join ticket_seller on control_slip.ticket_seller=ticket_seller.id where control_slip.status='open' order by ticket_seller.last_name ";
									$rs=$db->query($sql);
									$nm=$rs->num_rows;	
									?>
								
									<td valign=top class='grid3'>Ticket Seller</td>
                                    <td class='grid3 searchDrop'><select id='cs_ticket_seller' name="cs_ticket_seller" class='select' style='width:200px;' onchange="checkRemittance(document.getElementById('type').value);">
										<?php 
										for($i=0;$i<$nm;$i++){
											$row=$rs->fetch_assoc();
										?>
											<option value='<?php echo $row['control_id']; ?>' <?php if($control_post==$row['control_id']){ echo "selected"; } ?>><?php echo strtoupper($row['last_name']).", ".$row['first_name']."--".$row['unit']; ?></option>
										<?php
										}
										?>
                                    </select>
									</td>
                                </tr>
                                <tr>
                                    <td>Station</td>
                                    <td><select name="station" id='station' style='width:200px;'>
										<?php
										$db=retrieveDb();
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
									</td>
                                </tr>
                                <tr>
									<td>Reference ID</td>
									<td><input type='text' name='reference_id' id='reference_id'  /></td>
								</tr>	
                                <tr>
									<td>Date</td>
									<td align=left><input type="text" class="inlinedate" name='receive_date' id='receive_date' value='<?php echo date("m/d/Y"); ?>' /></td>
								</tr>	
                                <tr>
									<td>Time</td>
									<td align=left><input type="text" class="timepicker"  size="10" name='receive_time' value='<?php echo date("H:i:s"); ?>' /></td>
								</tr>
								<tr class='category'><td>Transaction</td>
								<td>
								<select name='type' id='type' onchange='checkRemittance(this)'>
								<option <?php if($transactType=="allocation"){ echo "selected"; } ?> value='allocation'>Allocation</option>

								<?php 
								if(isset($_GET['edit_control'])){
								?>
								<option <?php if($transactType=="remittance"){ echo "selected"; } ?> value='remittance'>Final Remittance</option>
								<?php
								}
								else {
								?>
								<option <?php if($transactType=="partial_remittance"){ echo "selected"; } ?> value='partial_remittance'>Partial Remittance</option>
								<?php
								}



								if($_GET['shortage_payment']=="Y"){
									$transactType="shortage";
								}
								?>
								<option <?php if($transactType=="shortage"){ echo "selected"; } ?> value='shortage'>Shortage Payment</option>
								<option <?php if($transactType=="catransfer"){ echo "selected"; } ?> value='catransfer'>Turnover to CA</option>

								</select>
								</td>
								</tr>
								
								<tr>
							
									<td valign=top class='grid3'>Destination Cash Assistant</td>
                                    <td class='grid3 searchDrop'>
									<select name="destination_ca" id='destination_ca' class='select' style='width:200px;' >
									<?php
									$db=retrieveDb();
									$sql="select * from login order by lastName";
									$rs=$db->query($sql);
									$nm=$rs->num_rows;
									for($i=0;$i<$nm;$i++){
									$row=$rs->fetch_assoc();
									?>
									<option value='<?php echo $row['username']; ?>' 
									<?php 
									if($cash_assist==""){
										if($row['username']==$_SESSION['username']){
											echo "selected";
										}
									}
									else {
										if($row['username']==$cash_assist){
										
											echo "selected";
										}

									}

									?> 
									>
									<?php
									echo strtoupper($row['lastName']).", ".$row['firstName'];
									?>
									</option>
									<?php
									}
									?>


                                    </select>
									</td>
                                </tr>
								
								</table>
								<div id="beforesubmit" style="display:none"></div>
								<div id="beforeshortage" style="display:none"></div>

								<a href='#' name='open_denomination' id='open_denomination'>Open Denomination</a>
							<div  name='denomination_modal' id='denomination_modal' title='Denomination'>
						
							<table style='width:100%' class='tDefault2' id='ctf_denom' name='ctf_denom' >
								<thead>
								<tr>
									<th>Denomination</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								
								</tr>
								</thead>
								<tbody>
								<tr>
									<td><label>1000</label></td>
									<td><input type="text"  autocomplete="off"  id='1000denom' name='1000denom' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1' class='1000denom' /></td>
								</tr>

								<tr>
									<td><label>500</label></td>
									<td class="grid4"><input  autocomplete="off" type="text" id='500denom' name='500denom' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2' class='500denom'  /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>200</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='200denom' name='200denom' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')"  /></div>
									<td class="grid5"><input type="text" name="amount3" id='amount3' class='200denom'  /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>100</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='100denom' name='100denom' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')"  /></div>
									<td class="grid5"><input type="text" name="amount4" id='amount4' class='100denom'  /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>50</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='50denom' name='50denom' onkeyup="amountCalculate(this.value,'50','amount5',event,'20denom')"   /></div>
									<td class="grid5"><input type="text" name="amount5" id='amount5' class='50denom'  /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>20</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='20denom' name='20denom' onkeyup="amountCalculate(this.value,'20','amount6',event,'10denom')"  /></div>
									<td class="grid5"><input type="text" name="amount6" id='amount6' class='20denom'  /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>10</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='10denom' name='10denom'   onkeyup="amountCalculate(this.value,'10','amount7',event,'5denom')"  /></div>
									<td class="grid5"><input type="text" name="amount7" id='amount7' class='10denom'  /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>5</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='5denom' name='5denom'  onkeyup="amountCalculate(this.value,'5','amount8',event,'1denom')"   /></div>
									<td class="grid5"><input type="text" name="amount8" id='amount8' class='5denom' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>1</label></div>
									<td class="grid4"><input  autocomplete="off" type="text"  id='1denom' name='1denom'  onkeyup="amountCalculate(this.value,'1','amount9',event,'25cdenom')"  /></div>
									<td class="grid5"><input type="text" name="amount9" id='amount9' class='1denom'  /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.25</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='25cdenom' name='25cdenom'  onkeyup="amountCalculate(this.value,'.25','amount10',event,'10cdenom')"   /></div>
									<td class="grid5"><input type="text" name="amount10" id='amount10' class='25cdenom' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.10</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='10cdenom' name='10cdenom'   onkeyup="amountCalculate(this.value,'.10','amount11',event,'5cdenom')"  /></div>
									<td class="grid5"><input type="text" name="amount11" id='amount11' class='10cdenom' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.05</label></div>
									<td class="grid4"><input  autocomplete="off" type="text" id='5cdenom' name='5cdenom'  onkeyup="amountCalculate(this.value,'.05','amount12',event,'revolving_remittance')"  /></div>
									<td class="grid5"><input type="text" name="amount12" id='amount12' class='5cdenom' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
								
									<td class='grid4'>&nbsp;</td>
									<td class="grid3"><label>Total</label></td>
									<td class="grid5"><input type="text" id='cash_total' name='cash_total'/></td>
								</tr>
										
								</tbody>
								
								</table>
								</div>

								<table class='tDefault' style='width:100%'>
								<tr>	
									<td><label>Total In Words</label></td>
									<td><textarea id='total_in_pesos' name='total_in_pesos' rows="7" cols="" name="textarea" class="auto"></textarea></td>

								</tr>
								<tr>
									<td><label>Revolving Fund</label></td>
									<td><input type="text" id='revolving_remittance' name='revolving_remittance' value='<?php echo $cash_advance; ?>' /></td>
								</tr>
								<tr>
									<td><label>For Deposit/Net Revenue</label></td>
									<td><input type="text" id='for_deposit' name='for_deposit' /></td>
								</tr>
							</table>	
							</form>
							</div>
							
							
							
							
							
							<div id="pnb_modal" name='pnb_modal' title="PNB Deposit" style='display:none;'>
								
								<form autocomplete='off'  action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' name='pnb_submit_form' id='pnb_submit_form'>
								<input type='hidden' name='form_action' id='form_action' value='new' class='form_action2'>
								<input type='hidden' name='pnb_transaction_id' id='pnb_transaction_id' />

								<table class='tDefault' id='pnb_table' style='width:100%'>
								<tr>
							
									<td valign=top class='grid3'>Cash Assistant</td>
                                    <td class='grid3 searchDrop'>
									<select name="pnb_ca" id='pnb_ca' class='select' style='width:200px;' >
									<?php
									$db=retrieveDb();
									$sql="select * from login order by lastName";
									$rs=$db->query($sql);
									$nm=$rs->num_rows;
									for($i=0;$i<$nm;$i++){
									$row=$rs->fetch_assoc();
									?>
									<option value='<?php echo $row['username']; ?>' 
									<?php 
									if($cash_assist==""){
										if($row['username']==$_SESSION['username']){
											echo "selected";
										}
									}
									else {
										if($row['username']==$cash_assist){
										
											echo "selected";
										}

									}

									?> 
									>
									<?php
									echo strtoupper($row['lastName']).", ".$row['firstName'];
									?>
									</option>
									<?php
									}
									?>


                                    </select>
									</td>
                                </tr>
								
								<tr>
									<td>Type</td>
									<td>
									<select name='deposit_type' id='deposit_type'>
									<option <?php if($depositType=="previous"){ echo "selected"; } ?> value='previous'>Previous</option>
									<option <?php if($depositType=="current"){ echo "selected"; } ?> value='current'>Current</option>
									</select>
									</td>
									
								
								</tr>
                                <tr>
									<td>Reference ID</td>
									<td><input type='text' name='reference_id_2' id='reference_id_2' /></td>
								</tr>	
                                <tr>
									<td>Date</td>
									<td align=left><input type="text" class="inlinedate" name='receive_date_2' id='receive_date_2' value='<?php echo date("m/d/Y"); ?>'/></td>
								</tr>	
                                <tr>
									<td>Time</td>
									<td align=left><input type="text" class="timepicker"  size="10" name='receive_time_2' id='receive_time_2' value='<?php echo date("H:i:s"); ?>'/></td>
								</tr>
								</table>
								<div id='beforesubmit2' name='beforesubmit2' style='display:none;'></div>
								
								<a href='#' name='open_denomination2' id='open_denomination2'>Open Denomination</a>
							<div name='denomination_modal2' id='denomination_modal2' title='Denomination'>

								<table style='width:100%' class='tDefault2' id='pnb_denom' name='pnb_denom' >
								<thead>
								<tr>
									<th>Denomination</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								
								</tr>
								</thead>
								<tbody>
								<tr>
									<td><label>1000</label></td>
									<td><input type="text" autocomplete="off"  id='1000denom_2' name='1000denom_2' onkeyup="amountCalculate2(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1'  class='1000denom'  /></td>
								</tr>

								<tr>
									<td><label>500</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='500denom_2' name='500denom_2' onkeyup="amountCalculate2(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2'  class='500denom'  /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>200</label></div>
									<td class="grid4"><input type="text" autocomplete="off"  id='200denom_2' name='200denom_2' onkeyup="amountCalculate2(this.value,'200','amount3',event,'100denom')"  /></td>
									<td class="grid5"><input type="text" name="amount3" id='amount3'  class='200denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>100</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='100denom_2' name='100denom_2' onkeyup="amountCalculate2(this.value,'100','amount4',event,'50denom')"  /></td>
									<td class="grid5"><input type="text" name="amount4" id='amount4' class='100denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>50</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='50denom_2' name='50denom_2' onkeyup="amountCalculate2(this.value,'50','amount5',event,'20denom')"   /></td>
									<td class="grid5"><input type="text" name="amount5" id='amount5' class='50denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>20</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='20denom_2' name='20denom_2' onkeyup="amountCalculate2(this.value,'20','amount6',event,'10denom')"  /></td>
									<td class="grid5"><input type="text" name="amount6" id='amount6' class='20denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>10</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='10denom_2' name='10denom_2'   onkeyup="amountCalculate2(this.value,'10','amount7',event,'5denom')"  /></td>
									<td class="grid5"><input type="text" name="amount7" id='amount7' class='10denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>5</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='5denom_2' name='5denom_2'  onkeyup="amountCalculate2(this.value,'5','amount8',event,'1denom')"   /></td>
									<td class="grid5"><input type="text" name="amount8" id='amount8' class='5denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>1</label></td>
									<td class="grid4"><input type="text" autocomplete="off"   id='1denom_2' name='1denom_2'  onkeyup="amountCalculate2(this.value,'1','amount9',event,'25cdenom')"  /></td>
									<td class="grid5"><input type="text" name="amount9" id='amount9'  class='1denom' /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>.25</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='25cdenom_2' name='25cdenom_2'  onkeyup="amountCalculate2(this.value,'.25','amount10',event,'10cdenom')"   /></td>
									<td class="grid5"><input type="text" name="amount10" id='amount10'  class='25cdenom' /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>.10</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='10cdenom_2' name='10cdenom_2'   onkeyup="amountCalculate2(this.value,'.10','amount11',event,'5cdenom')"  /></td>
									<td class="grid5"><input type="text" name="amount11" id='amount11'  class='10cdenom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>.05</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='5cdenom_2' name='5cdenom_2'  onkeyup="amountCalculate2(this.value,'.05','amount12',event,'')"  /></td>
									<td class="grid5"><input type="text" name="amount12" id='amount12'  class='5cdenom' /></td>
									<td class="clear"></td>
								</tr>
								<tr>
								
									<td class='grid4'>&nbsp;</td>
									<td class="grid3"><label>Total</label></td>
									<td class="grid5"><input type="text" id='cash_total' name='cash_total'/></td>
								</tr>
										
								</tbody>
								
								</table>
								</form>
								</div>


						</div>

						
						
						
						
						
							<div name='shortage_modal' id='shortage_modal' title='Payment Shortage' style='display:none;'>

								<table style='width:100%' class='tDefault2' id='shortage_denom' name='shortage_denom' >
								<thead>
								<tr>
									<th>Denomination</th>
									<th style='text-align:center'>Quantity</th>
									<th  style='text-align:center'>Amount</th>
								
								</tr>
								</thead>
								<tbody>
								<tr>
									<td><label>1000</label></td>
									<td><input type="text" autocomplete="off"  id='1000denom_3' name='1000denom_3' onkeyup="amountCalculate3(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1'  class='1000denom'  /></td>
								</tr>

								<tr>
									<td><label>500</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='500denom_3' name='500denom_3' onkeyup="amountCalculate3(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2'  class='500denom'  /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>200</label></div>
									<td class="grid4"><input type="text" autocomplete="off"  id='200denom_3' name='200denom_3' onkeyup="amountCalculate3(this.value,'200','amount3',event,'100denom')"  /></td>
									<td class="grid5"><input type="text" name="amount3" id='amount3'  class='200denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>100</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='100denom_3' name='100denom_3' onkeyup="amountCalculate3(this.value,'100','amount4',event,'50denom')"  /></td>
									<td class="grid5"><input type="text" name="amount4" id='amount4' class='100denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>50</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='50denom_3' name='50denom_3' onkeyup="amountCalculate3(this.value,'50','amount5',event,'20denom')"   /></td>
									<td class="grid5"><input type="text" name="amount5" id='amount5' class='50denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>20</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='20denom_3' name='20denom_3' onkeyup="amountCalculate3(this.value,'20','amount6',event,'10denom')"  /></td>
									<td class="grid5"><input type="text" name="amount6" id='amount6' class='20denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>10</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='10denom_3' name='10denom_3'   onkeyup="amountCalculate3(this.value,'10','amount7',event,'5denom')"  /></td>
									<td class="grid5"><input type="text" name="amount7" id='amount7' class='10denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>5</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='5denom_3' name='5denom_3'  onkeyup="amountCalculate3(this.value,'5','amount8',event,'1denom')"   /></td>
									<td class="grid5"><input type="text" name="amount8" id='amount8' class='5denom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>1</label></td>
									<td class="grid4"><input type="text" autocomplete="off"   id='1denom_3' name='1denom_3'  onkeyup="amountCalculate3(this.value,'1','amount9',event,'25cdenom')"  /></td>
									<td class="grid5"><input type="text" name="amount9" id='amount9'  class='1denom' /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>.25</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='25cdenom_3' name='25cdenom_3'  onkeyup="amountCalculate3(this.value,'.25','amount10',event,'10cdenom')"   /></td>
									<td class="grid5"><input type="text" name="amount10" id='amount10'  class='25cdenom' /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>.10</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='10cdenom_3' name='10cdenom_3'   onkeyup="amountCalculate3(this.value,'.10','amount11',event,'5cdenom')"  /></td>
									<td class="grid5"><input type="text" name="amount11" id='amount11'  class='10cdenom'  /></td>
									<td class="clear"></td>
								</tr>
								<tr>
									<td class="grid3"><label>.05</label></td>
									<td class="grid4"><input type="text" autocomplete="off"  id='5cdenom_3' name='5cdenom_3'  onkeyup="amountCalculate3(this.value,'.05','amount12',event,'')"  /></td>
									<td class="grid5"><input type="text" name="amount12" id='amount12'  class='5cdenom' /></td>
									<td class="clear"></td>
								</tr>
								<tr>
								
									<td class='grid4'>&nbsp;</td>
									<td class="grid3"><label>Total</label></td>
									<td class="grid5"><input type="text" id='shortage_total' name='shortage_total'/></td>
								</tr>
										
								</tbody>
								
								</table>
								</form>
								</div>
						
						
						
						
						
						
						
						
						
						
						
						
						
							<div id="begin_balance_cash" name='begin_balance_cash' title="Beginning Balance Entry" style='display:none'>
								
							<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' name='bb_cash_form' id='bb_cash_form'>
								<input type=hidden name='begin_log_id' id='begin_log_id' value='<?php echo $log_id; ?>'	/>
								<input type=hidden name='beginning_type' id='beginning_type' value='cash' />

								<table class='tDefault' style='width:100%'>
								<tr>
									<td valign=top class='grid3'>Revolving Fund</td>
                                    <td class='grid3 searchDrop'>
									<input type='text' name='revolving'>
									</td>
                                </tr>
								
								<tr>
									<td>For Deposit</td>
									<td>
									<input type='text' name='deposit' />
									</td>
									
								
								</tr>
								</table>
								</form>		


						</div>

						