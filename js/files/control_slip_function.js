$(function() {	
	//===== Modal =====//
    $('#open_ctf2').click(function () {
		$('#cash_transfer_modal').show();
		$('#cash_transfer_modal').dialog('open');
        return false;
    });
	
	$('#control_unsold').dialog({
		autoOpen:false,
		width:550,
		modal: true,
		buttons: {
			"Submit": function() {
				$('#unsold_form2').submit();
			}
		}
	});

    $('#fare_adjustment_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#fare_adjustment_form').submit();
				}
			}
		});
		
    $('#fa_open').click(function () {
		$('#fare_adjustment_modal').show();
        $('#fare_adjustment_modal').dialog('open');
        return false;
    });


   $('#unreg_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#unreg_form').submit();
				}
			}
		});
		
    $('#unreg_open').click(function () {
		$('#unreg_modal').show();
        $('#unreg_modal').dialog('open');
        return false;
    });

	$('#refund_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#refund_form').submit();
				}
			}
		});
		
    $('#refund_open').click(function () {
		$('#refund_modal').show();
        $('#refund_modal').dialog('open');
        return false;
    });
	

    $('#discount_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#discount_form').submit();
				}
			}
		});
		
    $('#discount_open').click(function () {
		$('#discount_modal').show();
        $('#discount_modal').dialog('open');
        return false;
    });
	
	
    $('#unsold_open').click(function () {
        $('#control_unsold').show();
		$('#control_unsold').dialog('open');
        return false;
    });
	
	$('#control_discrepancy').dialog({
		autoOpen:false,
		width:550,
		modal: true,
		buttons: {
			"Submit": function() {
				$('#discrepancy_form').submit();
			}
		}
	});
	
    $('#discrepancy_open').click(function () {
		$('#control_discrepancy').show();
        $('#control_discrepancy').dialog('open');
        return false;
    });

	$('#control_allocation').dialog({
		autoOpen:false,
		width:550,
		modal: true,
		buttons: {
			"Submit": function() {
				$('#allocation_form2').submit();
			}
		}
	});
	
    $('#allocation_open').click(function () {
		$('#control_allocation').show();
        $('#control_allocation').dialog('open');
        return false;
    });	
	$('#control_sold').dialog({
		autoOpen:false,
		width:550,
		modal: true,
		buttons: {
			"Submit": function() {
				$('#sold_form').submit();
			}
		}
		
		
	});
	
    $('#sold_open').click(function () {
		$('#control_sold').show();
        $('#control_sold').dialog('open');
        return false;
    });	
	
	$('#control_amount').dialog({
		autoOpen:false,
		width:550,
		modal: true,
		buttons: {
			"Submit": function() {
				$('#amount_form').submit();
			}
		}
		
		
	});
	
    $('#amount_open').click(function () {
		$('#control_amount').show();
        $('#control_amount').dialog('open');
        return false;
    });		
	

    $('#control_user_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		height:500,
		buttons: {
				"Submit": function() {
					$('#change_user_form').submit();
				}
			}
		});
		
    $('#open_change').click(function () {
		$('#control_user_modal').show();
        $('#control_user_modal').dialog('open');
        return false;
    });	
    $('#control_slip_status').change(function () {
        $('#status_form').submit();
        return false;
    });	
	
	
        $('#reference_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#ref_form').submit();
				}
			}
		});	
	
    $('#open_reference').click(function () {
		$('#reference_modal').show();
		$('#reference_modal').dialog('open');
    });	

    $('#ticket_order_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#ticket_order_form').submit();
				}
			}
		});


    $('#additional_open').click(function () {
        $('#ticket_order_modal').show();
		$('#ticket_order_modal').dialog('open');
        return false;
    });	

	
});