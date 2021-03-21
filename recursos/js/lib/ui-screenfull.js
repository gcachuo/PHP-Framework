(function ($) {
	"use strict";
  	
	uiLoad.load('../admin/libs/screenfull/dist/screenfull.min.js');
	$(document).on('click', '[ui-fullscreen]', function (e) {
		e.preventDefault();
		if (screenfull.enabled) {
		  screenfull.toggle();
		}
	});
})(jQuery);
