(function ( $ ) {
	"use strict";

	$(function () {

		// Place your public-facing JavaScript here
		$('.mareon-anomalies-gallery .gallery-full .gallery-content:not(.current), .mareon-anomalies-gallery .gallery-info .gallery-meta:not(.current)').hide();
		
		// bind image click event for switching image
		$('body').on('click', '.mareon-anomalies-gallery .gallery-content-thumbnail:not(.current) a', function(event) {
			var gallery = $(".mareon-anomalies-gallery");
			// get currents
			var currents = $(".mareon-anomalies-gallery .current");
			var thumb = currents.filter(".gallery-content-thumbnail");
			currents = currents.not(thumb);
			
			//gallery.find(".current").remove("current removing")
			// get clicked
			var click_index = gallery.find("a").index(this);
			var large_image = gallery.find(".gallery-full .gallery-content").eq(click_index);
			var text = gallery.find(".gallery-meta").eq(click_index);
			var clicked = gallery.find(".gallery-content-thumbnail").eq(click_index).add(large_image).add(text);
			// animations
			//		remove current class
			clicked.addClass("current").slideDown('slow');
			
			$(this).parent("li").addClass("current");
				
			currents.removeClass("current").slideUp("slow");
			
			//		add current class
			/*
currents.addClass("removing")
					.delay(1000)
					.removeClass("current removing");
*/
			thumb.removeClass("current");
		});
	});
	
	

}(jQuery));