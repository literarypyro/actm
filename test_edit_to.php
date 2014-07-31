<script language='javascript'>
function editTransact(transact_id,transact_type){
 	$('#'+transact_id+"_spinner").show();
	
	$.getJSON("processing.php?transaction_id2="+transact_id+"&type="+transact_type, function(data) {
		

		if(data.type=='ticket_order'){
			
			$('#form_action').val('edit');
			$('#'+data.tID+"_spinner").hide();
			$('#ticket_transaction_id').val(data.tID);

			$('#classification').val(data.classification);	
			$('#to_ticket_seller').val(data.control_id);	
			$('#station').val(data.station);	
			$('#reference_id').val(data.reference_id);
			$('#receive_date').val(data.receive_date);
			$('#receive_time').val(data.receive_time);
			
			
			
			$('#sjt').val(data.sjt);
			$('#sjt_loose').val(data.sjt_loose);

			$('#sjd').val(data.sjd);
			$('#sjd_loose').val(data.sjd_loose);

			$('#svt').val(data.svt);
			$('#svt_loose').val(data.svt_loose);

			$('#svd').val(data.svd);
			$('#svd_loose').val(data.svd_loose);


			$('#control_id').val(data.control_id);

			$('#ticket_order_modal').show();
			$('#ticket_order_modal').dialog('open');
			
		}	
    });
 }
</script>



</script>