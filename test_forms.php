<?php
require("calculateInWords.php");
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

	calculateNumber(Math.round(total*100)/100,"total_in_pesos");	

	var rev=document.getElementById('revolving_remittance').value;
	if(rev>0){
		document.getElementById('for_deposit').value=Math.round((document.getElementById('cash_total').value*1-rev*1)*100)/100;
		
	}
	else {
		document.getElementById('revolving_remittance').value=document.getElementById('cash_total').value;	
		document.getElementById('for_deposit').value=0;
	
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


</script>

							
							
							<div id="cash_transfer_modal" name='cash_transfer_modal' title="Cash Transfer Form">
								<table class='tDefault' style='width:100%'>
								<tr >
									<?php
									$db=new mysqli("localhost","root","","finance");
									
									$sql="select control_slip.id as control_id,control_slip.*,ticket_seller.* from control_slip inner join ticket_seller on control_slip.ticket_seller=ticket_seller.id where control_slip.status='open' order by ticket_seller.last_name ";
									$rs=$db->query($sql);
									$nm=$rs->num_rows;	
									?>
								
									<td valign=top class='grid3'>Ticket Seller</td>
                                    <td class='grid3 searchDrop'><select name="cs_ticket_seller" class='select' style='width:200px;' >
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
                                    <td><select name="station" style='width:200px;'>
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
									</td>
                                </tr>
                                <tr>
									<td>Reference ID</td>
									<td><input type='text' name='reference_id'  /></td>
								</tr>	
                                <tr>
									<td>Date</td>
									<td align=left><input type="text" class="inlinedate" /></td>
								</tr>	
                                <tr>
									<td>Time</td>
									<td align=left><input type="text" class="timepicker"  size="10" /></td>
								</tr>
								</table>
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
									<td><input type="text" id='1000denom' name='1000denom' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1' /></td>
								</tr>

								<tr>
									<td><label>500</label></td>
									<td class="grid4"><input type="text" id='500denom' name='500denom' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2' /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>200</label></div>
									<td class="grid4"><input type="text" id='200denom' name='200denom' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')"  /></div>
									<td class="grid5"><input type="text" name="amount3" id='amount3' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>100</label></div>
									<td class="grid4"><input type="text" id='100denom' name='100denom' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')"  /></div>
									<td class="grid5"><input type="text" name="amount4" id='amount4' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>50</label></div>
									<td class="grid4"><input type="text" id='50denom' name='50denom' onkeyup="amountCalculate(this.value,'50','amount5',event,'20denom')"   /></div>
									<td class="grid5"><input type="text" name="amount5" id='amount5' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>20</label></div>
									<td class="grid4"><input type="text" id='20denom' name='20denom' onkeyup="amountCalculate(this.value,'20','amount6',event,'10denom')"  /></div>
									<td class="grid5"><input type="text" name="amount6" id='amount6' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>10</label></div>
									<td class="grid4"><input type="text" id='10denom' name='10denom'   onkeyup="amountCalculate(this.value,'10','amount7',event,'5denom')"  /></div>
									<td class="grid5"><input type="text" name="amount7" id='amount7' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>5</label></div>
									<td class="grid4"><input type="text" id='5denom' name='5denom'  onkeyup="amountCalculate(this.value,'5','amount8',event,'1denom')"   /></div>
									<td class="grid5"><input type="text" name="amount8" id='amount8' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>1</label></div>
									<td class="grid4"><input type="text"  id='1denom' name='1denom'  onkeyup="amountCalculate(this.value,'1','amount9',event,'25cdenom')"  /></div>
									<td class="grid5"><input type="text" name="amount9" id='amount9' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.25</label></div>
									<td class="grid4"><input type="text" id='25cdenom' name='25cdenom'  onkeyup="amountCalculate(this.value,'.25','amount10',event,'10cdenom')"   /></div>
									<td class="grid5"><input type="text" name="amount10" id='amount10' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.10</label></div>
									<td class="grid4"><input type="text" id='10cdenom' name='10cdenom'   onkeyup="amountCalculate(this.value,'.10','amount11',event,'5cdenom')"  /></div>
									<td class="grid5"><input type="text" name="amount11" id='amount11' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.05</label></div>
									<td class="grid4"><input type="text" id='5cdenom' name='5cdenom'  onkeyup="amountCalculate(this.value,'.05','amount12',event,'revolving_remittance')"  /></div>
									<td class="grid5"><input type="text" name="amount12" id='amount12' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
								
									<td class='grid4'>&nbsp;</td>
									<td class="grid3"><label>Total</label></td>
									<td class="grid5"><input type="text" name="regular" id='cash_total' name='cash_total'/></td>
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
									<td><input type="text" name="regular" id='revolving_remittance' name='revolving_remittance' /></td>
								</tr>
								<tr>
									<td><label>For Deposit/Net Revenue</label></td>
									<td><input type="text" name="regular" id='for_deposit' name='for_deposit' /></td>
								</tr>
							</table>	
</div>
		
							<div id="pnb_modal" name='pnb_modal' title="PNB Deposit">
								<table class='tDefault' style='width:100%'>
								<tr>
							
									<td valign=top class='grid3'>Cash Assistant</td>
                                    <td class='grid3 searchDrop'>
									<select name="pnb_ca" class='select' style='width:200px;' >
									<?php
									$db=new mysqli("localhost","root","","finance");
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
									<select name='deposit_type'>
									<option <?php if($depositType=="previous"){ echo "selected"; } ?> value='previous'>Previous</option>
									<option <?php if($depositType=="current"){ echo "selected"; } ?> value='current'>Current</option>
									</select>
									</td>
									
								
								</tr>
                                <tr>
									<td>Reference ID</td>
									<td><input type='text' name='reference_id'  /></td>
								</tr>	
                                <tr>
									<td>Date</td>
									<td align=left><input type="text" class="inlinedate" /></td>
								</tr>	
                                <tr>
									<td>Time</td>
									<td align=left><input type="text" class="timepicker"  size="10" /></td>
								</tr>
								</table>
								<a href='#' name='open_denomination2' id='open_denomination2'>Open Denomination</a>
							<div  name='denomination_modal2' id='denomination_modal2' title='Denomination'>

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
									<td><input type="text" id='1000denom' name='1000denom' onkeyup="amountCalculate2(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1' /></td>
								</tr>

								<tr>
									<td><label>500</label></td>
									<td class="grid4"><input type="text" id='500denom' name='500denom' onkeyup="amountCalculate2(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2' /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>200</label></div>
									<td class="grid4"><input type="text" id='200denom' name='200denom' onkeyup="amountCalculate2(this.value,'200','amount3',event,'100denom')"  /></div>
									<td class="grid5"><input type="text" name="amount3" id='amount3' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>100</label></div>
									<td class="grid4"><input type="text" id='100denom' name='100denom' onkeyup="amountCalculate2(this.value,'100','amount4',event,'50denom')"  /></div>
									<td class="grid5"><input type="text" name="amount4" id='amount4' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>50</label></div>
									<td class="grid4"><input type="text" id='50denom' name='50denom' onkeyup="amountCalculate2(this.value,'50','amount5',event,'20denom')"   /></div>
									<td class="grid5"><input type="text" name="amount5" id='amount5' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>20</label></div>
									<td class="grid4"><input type="text" id='20denom' name='20denom' onkeyup="amountCalculate2(this.value,'20','amount6',event,'10denom')"  /></div>
									<td class="grid5"><input type="text" name="amount6" id='amount6' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>10</label></div>
									<td class="grid4"><input type="text" id='10denom' name='10denom'   onkeyup="amountCalculate2(this.value,'10','amount7',event,'5denom')"  /></div>
									<td class="grid5"><input type="text" name="amount7" id='amount7' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>5</label></div>
									<td class="grid4"><input type="text" id='5denom' name='5denom'  onkeyup="amountCalculate2(this.value,'5','amount8',event,'1denom')"   /></div>
									<td class="grid5"><input type="text" name="amount8" id='amount8' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>1</label></div>
									<td class="grid4"><input type="text"  id='1denom' name='1denom'  onkeyup="amountCalculate2(this.value,'1','amount9',event,'25cdenom')"  /></div>
									<td class="grid5"><input type="text" name="amount9" id='amount9' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.25</label></div>
									<td class="grid4"><input type="text" id='25cdenom' name='25cdenom'  onkeyup="amountCalculate2(this.value,'.25','amount10',event,'10cdenom')"   /></div>
									<td class="grid5"><input type="text" name="amount10" id='amount10' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.10</label></div>
									<td class="grid4"><input type="text" id='10cdenom' name='10cdenom'   onkeyup="amountCalculate2(this.value,'.10','amount11',event,'5cdenom')"  /></div>
									<td class="grid5"><input type="text" name="amount11" id='amount11' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>.05</label></div>
									<td class="grid4"><input type="text" id='5cdenom' name='5cdenom'  onkeyup="amountCalculate2(this.value,'.05','amount12',event,'')"  /></div>
									<td class="grid5"><input type="text" name="amount12" id='amount12' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
								
									<td class='grid4'>&nbsp;</td>
									<td class="grid3"><label>Total</label></td>
									<td class="grid5"><input type="text" name="regular" id='cash_total' name='cash_total'/></td>
								</tr>
										
								</tbody>
								
								</table>
								</div>


						</div>
		


