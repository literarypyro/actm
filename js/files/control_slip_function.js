$(function() {	
	//===== Modal =====//
	$('#control_unsold').dialog({
		autoOpen:false,
		width:550,
		modal: true,
		buttons: {
			"Submit": function() {
				//$('#refund_form').submit();
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
				//$('#refund_form').submit();
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
				//$('#refund_form').submit();
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
				//$('#refund_form').submit();
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
				//$('#refund_form').submit();
			}
		}
		
		
	});
	
    $('#amount_open').click(function () {
        $('#control_amount').dialog('open');
        return false;
    });		
	
	
	
});