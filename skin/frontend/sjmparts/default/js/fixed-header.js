(function ($) {
	"use strict";
	// Top Menu Sticky
	$(window).on('scroll', function () {
		var scroll = $(window).scrollTop();
		if (scroll < 300) {
		$(".header-container").removeClass("isFixed");
		$(".header",".col-xs-12 .col-sm-6 .col-md-4").removeClass("col-md-4")
		$(".header",".col-xs-12 .col-sm-6").addClass("col-md-6");
		$(".header",".col-xs-12 .col-md-4 .col-sm-6").removeClass("col-md-4")
		$(".header",".col-xs-12 .col-sm-6").addClass("col-md-6");
		$('#back-top').fadeIn(500);
		} else {
		$(".header-container").addClass("isFixed");
		$(".header",".col-xs-12 .col-sm-6 .col-md-6").removeClass("col-md-6")
		$(".header",".col-xs-12 .col-sm-6").addClass("col-md-4");
		$(".header",".col-xs-12 .col-md-6 .col-sm-6").removeClass("col-md-6")
		$(".header",".col-xs-12 .col-sm-6").addClass("col-md-4");
		$('#back-top').fadeIn(500);
		}
	});
});