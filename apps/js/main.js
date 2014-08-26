$(document).ready(function() {
	$('.tMenu').horizontalNav({});
	$('.eMenu').horizontalNav({});
	$('img').lazyLoad();
	$('.eMenu').corner('bottom');
	$('.notSoc').corner('br tr');
	$('.tit').corner('tr');
		 
	var $container = $('#listApps');
	// initialize
	$container.masonry({
		columnWidth: 10,
		itemSelector: '.item'
	});    
	var altHeader = $('.header').outerHeight();

	$(window).scroll(function(){
		if ($(window).scrollTop() > altHeader){
		$('.eMenu').addClass('fixed').css('top','0').next().css('padding-top','40px');
		} else {
		$('.eMenu').removeClass('fixed').next().css('padding-top','0');
		}
	});

	$("input[name$='developer']").click(function() {
		var dev = $(this).val();
		if (dev == "O"){
			$("#person").hide();
			$("#organization").show();
		} else {
			$("#person").show();
			$("#organization").hide();		
		}
	});

	$("input[id$='platform']").click(function() {
		var platform = $(this).val();
		var checked = $(this).prop('checked'); 
		if (platform == "M"){
			if (checked == true) {
				$("#mobile").show();
			} else {
				$("#mobile").hide();
			}			
		} else if (platform == "W")  {
			if (checked == true) {
				$("#web").show();
			} else {
				$("#web").hide();
			}	
		} else {
			if (checked == true) {
				$("#desktop").show();
			} else {
				$("#desktop").hide();
			}			
		}
	});

	$("input[id$='platformType']").click(function() {
		var platformType = $(this).val();
		var checkedP = $(this).prop('checked'); 
		if (checkedP == true) {
			$("#" + platformType).show();
		} else {
			$("#" + platformType).hide();
		}			
	});

	//


	





});