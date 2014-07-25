

							
							<div id="ticket_order_modal" name='ticket_order_modal' title="Ticket Order">




								<table class='tDefault' style='width:100%'>
								<tr>
									<td>Classification</td>
									<td>
									<select name='classification' id='classification'>
										<option value='ticket_seller' <?php if($classification=='ticket_seller'){ echo "selected"; } ?> >To Ticket Seller</option>
										<option value='finance' <?php if($classification=='finance'){ echo "selected"; } ?>>From Finance Train</option>
										<option value='annex' <?php if($classification=='annex'){ echo "selected"; } ?>>From Annex</option>
										<option value='catransfer' <?php if($classification=='catransfer'){ echo "selected"; } ?>>Turnover to CA</option>
									</select>

									</td>
								</tr>


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
                                <div class="divider"><span></span></div>
								<br>
								<table style='width:100%' class='tDefault2' id='ctf_denom' name='ctf_denom' >
								<thead>
								<tr>
									<th>Ticket Type</th>
									<th style='text-align:center'>Pieces</th>
									<th  style='text-align:center'>Loose</th>
								
								</tr>
								</thead>
								<tbody>
								<tr>
									<td><label>SJT</label></td>
									<td><input type="text" id='1000denom' name='1000denom' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1' /></td>
								</tr>

								<tr>
									<td><label>SJD</label></td>
									<td class="grid4"><input type="text" id='500denom' name='500denom' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2' /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>SVT</label></div>
									<td class="grid4"><input type="text" id='200denom' name='200denom' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')"  /></div>
									<td class="grid5"><input type="text" name="amount3" id='amount3' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>SVD</label></div>
									<td class="grid4"><input type="text" id='100denom' name='100denom' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')"  /></div>
									<td class="grid5"><input type="text" name="amount4" id='amount4' /></div>
									<td class="clear"></div>
								</tr>
										
								</tbody>
								
								</table>

							</div>
		

							<div id="physically_defective_modal" name='physically_defective_modal' title="Physically Defective">




								<table class='tDefault' style='width:100%'>
								<tr>
									<td class="grid3"><label>Ticket Seller</label></td>
									<td class="grid9  searchDrop">
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
								</table>
                                <div class="divider"><span></span></div>
								<br>
								<table style='width:100%' class='tDefault2' id='ctf_denom' name='ctf_denom' >
								<thead>
								<tr>
									<th>Ticket Type</th>
									<th style='text-align:center'>Pieces</th>
									<th  style='text-align:center'>Loose</th>
								
								</tr>
								</thead>
								<tbody>
								<tr>
									<td><label>SJT</label></td>
									<td><input type="text" id='1000denom' name='1000denom' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')"  /></td>
									<td><input type="text" name="amount1" id='amount1' /></td>
								</tr>

								<tr>
									<td><label>SJD</label></td>
									<td class="grid4"><input type="text" id='500denom' name='500denom' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')"  /></td>
									<td class="grid5"><input type="text" name="amount2" id='amount2' /></td>

								</tr>
								
								<tr>
									<td class="grid3"><label>SVT</label></div>
									<td class="grid4"><input type="text" id='200denom' name='200denom' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')"  /></div>
									<td class="grid5"><input type="text" name="amount3" id='amount3' /></div>
									<td class="clear"></div>
								</tr>
								<tr>
									<td class="grid3"><label>SVD</label></div>
									<td class="grid4"><input type="text" id='100denom' name='100denom' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')"  /></div>
									<td class="grid5"><input type="text" name="amount4" id='amount4' /></div>
									<td class="clear"></div>
								</tr>
										
								</tbody>
								
								</table>

							</div>
		
		