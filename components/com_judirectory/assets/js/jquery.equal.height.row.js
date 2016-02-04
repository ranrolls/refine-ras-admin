(function ($) {
	$.fn.equalHeightRow = function () {
		return this.each (function () {
			var e = $(this);

            var rowCount = 0;
            e.find("table").each(function(){
                rowCount = Math.max ($("tr",$(this)).length, rowCount);
            });

            for(var i = 0;i < rowCount; i++){
                var tallest = 0;
                e.find("table").each(function(){
                    tallest =  Math.max ($("tr",$(this)).eq(i).height (), tallest);
                });
                e.find("table").each(function(){
                    $("tr",$(this)).eq(i).height(tallest);
                });
            }

		});
	};
})(jQuery);
