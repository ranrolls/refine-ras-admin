function addToCompare(listing_id) {
	jQuery.ajax({
		url: 'index.php?option=com_judirectory&task=listing.compare',
		type: 'post',
		data: 'listing_id=' + listing_id
	}).done(function (result) {
		var result = jQuery.parseJSON(result);

		if(result){
			if (result.success) {
				jQuery('#judir-comparison-notification').html('<div class="alert alert-success">' + result.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				jQuery('#judir-total-compared-listings').html(result.total);

				jQuery('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
}