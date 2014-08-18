		<?php
		$db=retrieveDb();
		?>
		
		
		<?php
		$control_id=$_SESSION['control_id'];

		$sql="select * from control_slip where id='".$control_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		
		$ticket_seller=$row['ticket_seller'];
		?>

	<?php

		$db=retrieveDb();
		if(isset($_POST['allocation_id'])){
			$log_id=$_SESSION['log_id'];	
			$control_id=$_POST['allocation_id'];
			
			$sql="select * from allocation where control_id='".$control_id."'";

			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$transaction_no=$row['id'];
				$transaction_id=$row['transaction_id'];			
			
			}
			else {
				$date=date("Y-m-d H:i");
				$date_id=date("Ymd");
				
				$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','allocation')";
				
				$rsInsert=$db->query($transactionInsert);
							
				$insert_id=$db->insert_id;
						
				$transaction_id=$date_id."_".$insert_id;
				$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
				$rs=$db->query($sql);					


				$transaction_no=$insert_id;		
			
			}
			
			$tickets[0]="sjt";
			$tickets[1]="sjd";
			$tickets[2]="svt";
			$tickets[3]="svd";

			for($i=0;$i<count($tickets);$i++){		
				if(($_POST[$tickets[$i]."_allocation_a"]=="")&&($_POST[$tickets[$i]."_allocation_a_loose"]=="")){
				}
				else {
					$initial=$_POST[$tickets[$i]."_allocation_a"];
					$additional=$_POST[$tickets[$i]."_allocation_b"];
					$initial_loose=$_POST[$tickets[$i]."_allocation_a_loose"];
					$additional_loose=$_POST[$tickets[$i]."_allocation_b_loose"];
					
					
					$sql="select * from allocation where control_id='".$control_id."' and type='".$tickets[$i]."'";

					$rs=$db->query($sql);
					$nm=$rs->num_rows;
					
					if($nm==0){
						$sql="insert into allocation(control_id,type,initial,additional,initial_loose,additional_loose,transaction_id) values ";
						$sql.="('".$control_id."','".$tickets[$i]."','".$initial."','".$additional."','".$initial_loose."','".$additional_loose."','".$transaction_id."')";
						$rs=$db->query($sql);
					
					}
					else {
						$sql="update allocation set initial='".$initial."',initial_loose='".$initial_loose."' where control_id='".$control_id."' and type='".$tickets[$i]."'";
						
						$rs=$db->query($sql);	
					
					}		
				}	
			}		
			
			
			//calculateTicketSold($control_id,$db);	
			
			
			
		}

		if(isset($_POST['unsold_id'])){
			$log_id=$_SESSION['log_id'];	
			$control_id=$_POST['unsold_id'];
			
			
			$sql="select * from control_unsold where control_id='".$control_id."'";
			
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$transaction_no=$row['id'];
				$transaction_id=$row['transaction_id'];			
			
			}
			else {
				$date=date("Y-m-d H:i");
				$date_id=date("Ymd");
				
				$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','remittance')";

				$rsInsert=$db->query($transactionInsert);
							
				$insert_id=$db->insert_id;
						
				$transaction_id=$date_id."_".$insert_id;
				$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
				$rs=$db->query($sql);					


				$transaction_no=$insert_id;		
			
			}
			
			$tickets[0]="sjt";
			$tickets[1]="sjd";
			$tickets[2]="svt";
			$tickets[3]="svd";
			
			
			for($i=0;$i<count($tickets);$i++){			
				if(($_POST[$tickets[$i]."_unsold_a"]=="")&&($_POST[$tickets[$i]."_unsold_b"]=="")&&($_POST[$tickets[$i]."_unsold_c"]=="")){
				}
				else {
				
					$sealed=$_POST[$tickets[$i]."_unsold_a"];
					$loose_good=$_POST[$tickets[$i]."_unsold_b"];
					$loose_defective=$_POST[$tickets[$i]."_unsold_c"];

					
					$sql="select * from control_unsold where control_id='".$control_id."' and type='".$tickets[$i]."'";
	
					
					$rs=$db->query($sql);
					$nm=$rs->num_rows;
					
					if($nm==0){
						$sql="insert into control_unsold(control_id,type,sealed,loose_good,loose_defective,transaction_id) values ";
						$sql.="('".$control_id."','".$tickets[$i]."','".$sealed."','".$loose_good."','".$loose_defective."','".$transaction_id."')";
						
						
						$rs=$db->query($sql);

					}
					else {
						$sql="update control_unsold set sealed='".$sealed."', loose_good='".$loose_good."', loose_defective='".$loose_defective."' where control_id='".$control_id."' and type='".$tickets[$i]."'";
						$rs=$db->query($sql);

						
						
					}
				
				}		

			}
			//calculateTicketSold($control_id,$db);	

		}
		
		if(isset($_POST['amount_id'])){
			$control_id=$_POST['amount_id'];
				
			$sjt_amount=$_POST['sjt_amount'];
			$sjd_amount=$_POST['sjd_amount'];
			$svt_amount=$_POST['svt_amount'];
			$svd_amount=$_POST['svd_amount'];
				
				
			$sql="select * from control_sales_amount where control_id='".$control_id."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			
			if($nm==0){
				$sql="insert into control_sales_amount(control_id,sjt,sjd,svt,svd) values ";
				$sql.="('".$control_id."','".$sjt_amount."','".$sjd_amount."','".$svt_amount."','".$svd_amount."')";
				$rs=$db->query($sql);
			}	
			else {
				$sql="update control_sales_amount set sjt='".$sjt_amount."',sjd='".$sjd_amount."',svt='".$svt_amount."',svd='".$svd_amount."' where control_id='".$control_id."'";
				$rs=$db->query($sql);	
			}
		}
			
		

		if(isset($_POST['discrepancy_id'])){
			
			$type=$_POST['type'];
			$classification="ticket";
			$transaction_id=$_POST['discrepancy_id'];
			$control_id=$_POST['discrepancy_id'];
			$reported=$_POST['reported'];
			$reference_id=$_POST['reference_id'];
			
			$log_id=$_SESSION['log_id'];

			$ticket[0]="sjt";
			$ticket[1]="sjd";
			$ticket[2]="svt";
			$ticket[3]="svd";
			
			$db=retrieveDb();
			
			for($i=0;$i<count($ticket);$i++){
				if(($_POST[$ticket[$i].'_disc_amount']=='')||($_POST[$ticket[$i].'_disc_amount']==0)){
					$sql="delete from discrepancy_ticket where transaction_id='".$transaction_id."' and ticket_type='".$ticket[$i]."'";
					$rs=$db->query($sql);
				}
				else {
					$sql="select * from discrepancy_ticket where transaction_id='".$transaction_id."' and ticket_type='".$ticket[$i]."'";
					$rs=$db->query($sql);
					$nm=$rs->num_rows;
					if($nm>0){
						$row=$rs->fetch_assoc();
						$update="update discrepancy_ticket set amount='".$_POST[$ticket[$i]."_disc_amount"]."',price='".$_POST[$ticket[$i]."_price"]."',type='".$_POST[$ticket[$i]."_classification"]."' where id='".$row['id']."'";
						$updateRS=$db->query($update);
					
					}
					else {
						$update="insert into discrepancy_ticket(reference_id,classification,reported,amount,type,transaction_id,log_id,ticket_seller,ticket_type,price,control_id)";
						$update.=" values ('".$reference_id."','".$classification."','".$reported."','".$_POST[$ticket[$i]."_disc_amount"]."','".$_POST[$ticket[$i]."_classification"]."','".$transaction_id."','".$log_id."','".$ticket_seller."','".$ticket[$i]."','".$_POST[$ticket[$i]."_price"]."','".$control_id."')";
						$updateRS=$db->query($update);	
					}			
				}
			}
			//calculateTicketSold($control_id,$db);	

		}

		if(isset($_POST['sold_id'])){
			$control_id=$_POST['sold_id'];

		
			$sjt_total=$_POST['sjt_sold'];
			$sjd_total=$_POST['sjd_sold'];
			$svt_total=$_POST['svt_sold'];
			$svd_total=$_POST['svd_sold'];
			
			$sql="select * from control_sold where control_id='".$control_id."'";

			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			
			if($nm==0){
				$sql="insert into control_sold(control_id,sjt,sjd,svt,svd) values ";
				$sql.="('".$control_id."','".$sjt_total."','".$sjd_total."','".$svt_total."','".$svd_total."')";
				$rs=$db->query($sql);
			}	
			else {
				$sql="update control_sold set sjt='".$sjt_total."',sjd='".$sjd_total."',svt='".$svt_total."',svd='".$svd_total."' where control_id='".$control_id."'";

				$rs=$db->query($sql);	
			}
			
			
			ticketDiscrepancy($control_id,$db);
		}
		
	
	?>
		<?php
		$db=retrieveDb();
		?>
		
		
		<?php
		$control_id=$_SESSION['control_id'];

		$sql="select * from control_slip where id='".$control_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();

		?>

        <div class="widget" style="width:100%;" id='control_panel'>
			
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
                <thead>
                    <tr>
						
                        <td style='text-align:center;'>&nbsp;</td>
                        <td style='text-align:center;'>Initial Allocation <span class='pull-right'><a href="#" title='Edit' id="allocation_open" name='allocation_open'><i class='icos-pencil'></i></a></span></td>
                        <td style='text-align:center;'>Additional Allocation<span class='pull-right'><a href="#" title='Add Ticket Order Form' id="additional_open" name='additional_open'><i class='icos-pencil'></i></a></span> </td>

                        <td style='text-align:center;'>Unsold <span class='pull-right'><a href="#" title='Edit' id="unsold_open" name='unsold_open'><i class='icos-pencil'></i></a></span></td>
                        <td style='text-align:center;'>Discrepancy<span class='pull-right'><a href="#" title='Edit' id="discrepancy_open" name='discrepancy_open'><i class='icos-pencil'></i></a></span></td>
                        <td style='text-align:center;'>Ticket Sold (from SCS data)<span class='pull-right'><a href="#" title='Edit' id="sold_open"><i class='icos-pencil'></i></a></span></td>
                        <td style='text-align:center;'>Amount<span class='pull-right'><a href="#" title='Edit' id="amount_open"><i class='icos-pencil'></i></a></span></td>
                    </tr>
				</thead>
				<tbody>
				<?php
				$total_allocation_a=0;
				$total_allocation_b=0;
				$total_allocation_a_loose=0;
				$total_allocation_b_loose=0;


				$total_unsold_a=0;
				$total_unsold_b=0;
				$total_unsold_c=0;


				$total_sold=0;
				$total_amount=0;					
				?>				
				<?php				
				$control_sql="select * from control_slip where id='".$control_id."'";
				$control_rs=$db->query($control_sql);
				$control_row=$control_rs->fetch_assoc();

				$control_log=$control_row['log_id'];
				$station=$control_row['station'];

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



				$sql="update allocation set additional='".$allocation['sjt']['additional']."',additional_loose='".$allocation['sjt']['additional_loose']."' where control_id='".$control_id."' and 'sjt'";
				$rs=$db->query($sql);

				$sql="update allocation set additional='".$allocation['svt']['additional']."',additional_loose='".$allocation['svt']['additional_loose']."' where control_id='".$control_id."' and 'svt'";
				$rs=$db->query($sql);

				$sql="update allocation set additional='".$allocation['sjd']['additional']."',additional_loose='".$allocation['sjd']['additional_loose']."' where control_id='".$control_id."' and 'sjd'";
				$rs=$db->query($sql);

				$sql="update allocation set additional='".$allocation['svd']['additional']."',additional_loose='".$allocation['svd']['additional_loose']."' where control_id='".$control_id."' and 'svd'";
				$rs=$db->query($sql);


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




				$sql="select * from control_sold where control_id='".$control_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				$row=$rs->fetch_assoc();

				if($nm==0){
				/*
					$sold_tickets["sjt"]=$allocation["sjt"]["initial"]+$allocation["sjt"]["initial_loose"]+$allocation['sjt']["additional"]+$allocation['sjt']["additional_loose"];
					$sold_tickets["sjd"]=$allocation["sjd"]["initial"]+$allocation["sjd"]["initial_loose"]+$allocation['sjd']["additional"]+$allocation['sjd']["additional_loose"];
					$sold_tickets["svt"]=$allocation["svt"]["initial"]+$allocation["svt"]["initial_loose"]+$allocation['svt']["additional"]+$allocation['svt']["additional_loose"];
					$sold_tickets["svd"]=$allocation["svd"]["initial"]+$allocation["svd"]["initial_loose"]+$allocation['svd']["additional"]+$allocation['svd']["additional_loose"];
					*/
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

				$discrepancyLabel['sjt']="--";
				$discrepancyLabel['sjd']="--";
				$discrepancyLabel['svt']="--";
				$discrepancyLabel['svd']="--";

				if($nm>0){
					for($i=0;$i<$nm;$i++){
						$row=$rs->fetch_assoc();
						if(($row['amount']*1)>0){
							if($row['type']=="shortage"){
								$discrepancyLabel[$row['ticket_type']]="<font color=red>(-".$row['amount'].")</font>";		
							//	$sold_tickets[$row['ticket_type']]-=$row['amount'];

							}
							else if($row['type']=="overage"){
								$discrepancyLabel[$row['ticket_type']]="<font color=green>(+".$row['amount'].")</font>";		
							//	$sold_tickets[$row['ticket_type']]+=$row['amount'];
							}
						}	
					}		
				}			
					
				$total_sold+=$sold_tickets["sjt"];
				$total_sold+=$sold_tickets["sjd"];
				$total_sold+=$sold_tickets["svt"];
				$total_sold+=$sold_tickets["svd"];

				$db=retrieveDb();
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
					$total_amount+=$svt_amount;
					$total_amount+=$svd_amount;
					$update="insert into control_sales_amount(control_id,svt,svd) values ('".$control_id."','".$svt_amount."','".$svd_amount."')";
					$updateRS=$db->query($update);
				}
				?>	
				<tr><td>SJT</td>
				<td align=center>
				<?php
				echo $allocation["sjt"]["initial"]+$allocation["sjt"]["initial_loose"]; 
				?>
				</td>
				<td align=center>
				<?php
					echo $allocation["sjt"]["additional"]+$allocation["sjt"]["additional_loose"]; 
				?>
				
				</td>
				<td align=center>
				<?php
				echo $unsold["sjt"]["sealed"];
				?>
				<font color=green>(+ 
				<?php
				echo $unsold["sjt"]["loose_good"]+$unsold["sjt"]["loose_defective"]; 
				?>
				)
				</font>
				</td>
				<td align=center>
				<?php
				echo $discrepancyLabel['sjt'];
				?>
				</td>
				<td align=center>
				<?php
				echo $sold_tickets['sjt']; 
				?>
				</td>
				<td align=center>
				<?php
				echo $sjt_amount;
				?>
				</td>
				
				</tr>
				<tr><td >SJD</td>
				<td  align=center>
				<?php
				echo $allocation["sjd"]["initial"]+$allocation["sjd"]["initial_loose"]; 
				?>
				</td>
				<td align=center>
				<?php
				echo $allocation["sjd"]["additional"]+$allocation["sjd"]["additional_loose"]; 
				?>
				</td>
				<td align=center>
				<?php
				echo $unsold["sjd"]["sealed"];
				?>
				<font color=green>(+ 
				<?php
				echo $unsold["sjd"]["loose_good"]+$unsold["sjd"]["loose_defective"]; 
				?>
				)
				</td>
				<td align=center>
				<?php
				echo $discrepancyLabel['sjd'];
				?>
				</td>
				<td align=center>
				<?php
				echo $sold_tickets['sjd']; 
				?>
				</td>
				<td align=center>
				<?php
				echo $sjd_amount;
				?>
				</td>

				</tr>
				<tr><td>SVT</td>
				<td  align=center>
				<?php
				echo $allocation["svt"]["initial"]+$allocation["svt"]["initial_loose"]; 
				?>
				</td>
				<td align=center>
				<?php
				echo $allocation["svt"]["additional"]+$allocation["svt"]["additional_loose"]; 
				?>
				
				</td>
				<td align=center>
				<?php
				echo $unsold["svt"]["sealed"];
				?>
				<font color=green>(+ 
				<?php
				echo $unsold["svt"]["loose_good"]+$unsold["svt"]["loose_defective"]; 
				?>
				)
				</td>
				<td align=center>
				<?php
				echo $discrepancyLabel['svt'];
				?>
				</td>
				<td align=center>
				<?php
				echo $sold_tickets['svt']; 
				?>
				</td>
				<td align=center>
				<?php
				echo $svt_amount;
				?>
				</td>

				</tr>
				<tr><td>SVD</td>
				<td  align=center>
				<?php
				echo $allocation["svd"]["initial"]+$allocation["svd"]["initial_loose"]; 
				?>
				</td>
				<td align=center>
				<?php
				echo $allocation["svd"]["additional"]+$allocation["svd"]["additional_loose"]; 
				?>
				</font> 
				</td>
				<td align=center>
				<?php
				echo $unsold["svd"]["sealed"];
				?>
				<font color=green>(+ 
				<?php
				echo $unsold["svd"]["loose_good"]+$unsold["svd"]["loose_defective"]; 
				?>
				)
				</td>
				<td align=center>
				<?php
				echo $discrepancyLabel['svd'];
				?>
				</td>
				<td align=center>
				<?php
				echo $sold_tickets['svd']; 
				?>
				</td>
				
				<td align=center>
				<?php
				echo $svd_amount;
				?>
				</td>
				
				
				
				</tr>

				<tr>
				<td>Total</td>
				<td align=center>
				<?php
				echo $total_allocation_a+$total_allocation_a_loose;
				?>
				</td>
				<td align=center>
				<?php
				echo $total_allocation_b+$total_allocation_b_loose;
				?>
				</td>
				<td align=center>
				<?php echo $total_unsold_a; ?>
				<font color=green>
				(+
				<?php echo $total_unsold_b+$total_unsold_c; ?>
				)
				</font>
				</td>
				<td align=center>&nbsp;</td>
				<td align=center><?php echo $total_sold; ?></td>

				<td align=center><?php echo $total_amount; ?></td>
				</tr>
				
				</tbody>
			</table>
		</div>	
		
		
		
		
