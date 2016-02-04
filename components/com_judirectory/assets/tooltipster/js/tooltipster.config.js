jQuery(document).ready(function ($) {
	$('.judir-tooltip').tooltipster({
		animation    : 'fade',
		interactive  : 'true',
		contentAsHTML: true,
		delay        : 200,
		position     : 'top',
		speed        : 300,
		theme        : 'tooltipster-shadow',
		functionInit : function (origin, content) {
			return origin.next('.judir-tooltip-content').children().addClass('jubootstrap').end().html();
		}
	});
});