
var producturl;

function geturlrewrite(){
	var mypath = arguments[0];
	var patt = /\/[^\/]{0,}jQuery/ig;
	if(mypath){
		if(mypath[mypath.length-1]=="/"){
			mypath = mypath.substring(0,mypath.length-1);
			return (mypath.match(patt)+"/");
		}
		return mypath.match(patt);
	}
	return '';	
}

function urltrim(){
	return arguments[0].replace(/^\s+|\s+jQuery/g,"");
}

function installquickview(){
	if (typeof CMSMART == 'undefined') return;
	var argInput = arguments[0];
	var productlistBlocks = jQuery(argInput.productlistClassArr);
	var datasaved = [];
	var mypath = 'cmsmquickview/index/index';
	if(CMSMART.QuickView.BASE_URL.indexOf('index.php') == -1){
		mypath = 'cmsmquickview/index/index';
	}else{
		mypath = 'cmsmquickview/index/index';
	}
	var baseUrl = CMSMART.QuickView.BASE_URL + mypath;
	
	var _quickviewbutton = '<a id="cmsmart_quickview_button" href="#">' + CMSMART.QuickView.BOTTON_LABEL + '</a>';
	var _quickform = '<div id="csmm_quickform">' + 
		'<div id = "quickviewshow" ></div>' + 
	'</div>';

	jQuery(document.body).append(_quickform);
	jQuery(document.body).append(_quickviewbutton);

	var quickviewButton = jQuery('#cmsmart_quickview_button');
	//alert(encodeURIComponent(CMSMART.QuickView.BASE_URL + 'ab=3dfd&ddfdfd=234'));
	jQuery.each(productlistBlocks, function(i, vl){
	var productlist = jQuery(vl);
	jQuery.each(productlist, function(index, value) {
		var reloadurl = '';
		
		var aClass = argInput.aClass[i]?argInput.aClass[i]:argInput.aClass[0];
		producturl = jQuery(aClass, value);

		if(producturl.attr('href')){
			var producturlpath = producturl.attr('href').replace(CMSMART.QuickView.BASE_URL,"");
			//var producturlpath = geturlrewrite(producturl.attr('href'))[0];
			//producturlpath[0] == "\/" ? producturlpath = producturlpath.substring(1,producturlpath.length) : producturlpath;
			//producturlpath = urltrim(producturlpath);
			
			reloadurl += baseUrl+ ("/path/"+producturlpath).replace(/\/\//g,"/");
			//alert(reloadurl);
			var imgClass = argInput.imgClass[i]?argInput.imgClass[i]:argInput.imgClass[0];
			jQuery(this).bind('mouseover', function() {
				//var o = jQuery(this).offset();
				//var o = jQuery(this);
				var o = jQuery(imgClass+':eq(0)', this);

				jQuery('#cmsmart_quickview_button').attr('href',reloadurl).show()
					.css({
						'top': o.offset().top+(o.height() - quickviewButton.height())/2+'px',
						'left': o.offset().left+(o.width() - quickviewButton.outerWidth())/2+'px',
						'visibility': 'visible'
					});
			});
			jQuery(value).bind('mouseout', function() {
				jQuery('#cmsmart_quickview_button').hide();
			});
		}
	});
	});

	if(CMSMART.QuickView.CENTER)
	{ 
		jQuery("#quickviewshow").css('margin', (jQuery(window).height() / 2 - jQuery("#quickviewshow").height() / 2) + "px auto auto auto"); 
	}
		
	jQuery('#cmsmart_quickview_button')
		.bind('mouseover', function() {
			jQuery(this).show();
		})
		.bind('click', function() {
			idbyurl = (jQuery(this).attr('href')).replace(/\W/g,"");
			showqv();
			jQuery("#quickviewshow").html('<a id="cmsmart_quickview_button_close" title="Close Quick View"> </a><a class="quickviewloading"><a>');
			jQuery("#cmsmart_quickview_button_close").on( "click", function() {
				closeqv();
				jQuery("div.zoomContainer").remove();
			});
			
			jQuery(this).hide();			
			if(datasaved[idbyurl]){
				jQuery("#quickviewshow").html('<a id="cmsmart_quickview_button_close" title="Close Quick View"> </a>');
				jQuery("#cmsmart_quickview_button_close").on( "click", function() {
					closeqv();
				});
				jQuery("#quickviewshow").append(datasaved[idbyurl]);				
				showqv();				
				relimg();
				return false;				
			}			
			else{
				jQuery.ajax({
					url: jQuery(this).attr('href'),
					cache: false
				}).done(function( html ) {
					
					jQuery("#quickviewshow").html('<a id="cmsmart_quickview_button_close" title="Close Quick View"> </a>');
					jQuery("#cmsmart_quickview_button_close").on( "click", function() {
						closeqv();
						jQuery("div.zoomContainer").remove();
					});													
					jQuery("#quickviewshow").append(html);					
					showqv();						
					datasaved[idbyurl] = html;
					relimg();
				});
			}

			return false;
		});
		
		jQuery('#csmm_quickform').click(function(e) {
			if(jQuery(e.target).is('#quickviewshow, #quickviewshow *')) return;
			jQuery('#csmm_quickform').hide();
			jQuery("div.zoomContainer").remove();
		});
}

jQuery(window).resize(function() {
	jQuery("#quickviewshow").css('margin', (jQuery(window).height() / 2 - jQuery("#quickviewshow").height() / 2) + "px auto auto auto");
});


function relimg(){
	maxh = jQuery('div.product-quickview').outerHeight() - 45;
	if(jQuery('div.qvtabhead')) maxh = maxh - jQuery('div.qvtabhead').outerHeight();
	if(jQuery('div.qvformaddtocart')) maxh = maxh - jQuery('div.qvformaddtocart').outerHeight();
	if(jQuery('div.tabquickshow')) jQuery('div.tabquickshow').css('max-height', maxh + "px");	
	
	//submitbqv();
	jQuery('#showlargeimg').elevateZoom({ zoomWindowWidth:300, zoomWindowHeight:300, borderSize: 2, zoomWindowOffetx:15, cursor:'move' });
	 if(jQuery(".tumbSlider").length){ 
		jQuery('.tumbSlider').carouFredSel({
			prev: '.tumbSlider-prev',
			next: '.tumbSlider-next',
			scroll: 1,
			auto	:false,
			items: {
				visible: {
					min: 1,
					max: 3
				},
				width:97,
			},
			mousewheel: true,
			swipe: {
				onMouse: false,
				onTouch: true
			}
		});
		 jQuery(".tumbSlider").swipe({
            excludedElements: "button, input, select, textarea, .noSwipe",
            swipeLeft: function() {
                jQuery('.tumbSlider').trigger('next', true);
            },
            swipeRight: function() {
                jQuery('.tumbSlider').trigger('prev', true);
            },
            tap: function(event, target) {
                // in case of an image wrapped by a link click on image will fire parent link
                jQuery(target).parent().trigger('click');
            }
        });
	};

	jQuery("li img.p_image_hover").click(
		function () {
			smallImage = jQuery(this).attr('src');
			largeImage = jQuery(this).attr('data-zoom-image');
			jQuery('img#showlargeimg').attr('src', smallImage);
			var ez = jQuery('#showlargeimg').data('elevateZoom');
			ez.swaptheimage(smallImage, largeImage);
		}
	);

	jQuery('a.tabquickviewcontrol').click(
		function(){		
			jQuery('a.tabquickviewcontrol').removeClass("highlight");
			jQuery(this).addClass("highlight");
			var divsl = jQuery(this).attr('href');
			jQuery('.tabquickshow').css('display', 'none');
			jQuery(divsl).css('display', 'block');
			return false;
		}
	)
}

function showqv(){ jQuery("#csmm_quickform").css("display", "block"); }
function closeqv(){ jQuery("#csmm_quickform").css("display", "none"); }

function btcloseqv(){
	jQuery("#cmsmart_quickview_button_close").on( "click", function() {
		jQuery("#csmm_quickform").css("display", "none");
	});
}

function submitbqv(){
	var fr = jQuery('#product_addtocart_form');
	var btc = jQuery('.btn-cart', fr);
	
	btc.attr('onclick', '');
	btc.click(function(){
		var cansubmit = true;
		jQuery('select.required-entry', fr).each(function(){
			if(jQuery(this).val() == ''){
				jQuery(this).addClass('validation-failed');
				jQuery(this).focus();
				cansubmit = false;
				return false;
			}else { jQuery(this).removeClass('validation-failed'); }
		});
		if(cansubmit) fr.submit();
	});
}
btcloseqv();
