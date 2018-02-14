jQuery(document).ready(function() {
    /*jQuery('#tour_id option').hide();
    jQuery('#tour_id option[data-vendor=0]').show();*/
    jQuery('#vendor_id').change(function() {
        var vendor = jQuery(this).val();
        jQuery('#tour_id option').hide();
        jQuery('#tour_id option[data-vendor=0]').show();
        if(vendor) {
            jQuery('#tour_id').val("");
            jQuery('#tour_id option[data-vendor='+vendor+']').show();
            jQuery('#vendor_name').val($('#vendor_id option:selected').text());
        }else{
            jQuery('#vendor_name').val('');
        }
    });
});
