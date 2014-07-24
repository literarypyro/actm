$(function() {	
	//===== Modal =====//
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
	
    $('#unsold_open').click(function () {
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
        $('#reference_modal').dialog('open');
    });	


	
});