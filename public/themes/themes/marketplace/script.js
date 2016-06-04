

if($('.slider').length > 0)
{
	$('.slider').slick({
	  dots: true,
	  speed: 500,
		centerMode: true,
		nextArrow: '<button type="button" class="slick-next fa fa-chevron-circle-right"></button>',
		prevArrow: '<button type="button" class="slick-prev fa fa-chevron-circle-left"></button>'
	});
}



//category menu
$('.menu-trigger').click(function(e){
	e.stopPropagation();
	$menu = $('.dropmenu');
	$('.dropmenu').slideToggle({
		duration: 300
	});
	return false;
		
});
$(".dropmenu").click(function(e) {
    e.stopPropagation();
});
$(document).click(function(e) {
    if($('.dropmenu').css('display') == 'block')
	{
		$('.dropmenu').toggle({
			
		});
	}
});

$('#cart').on('keyup', 'input.qty', function(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if(code == 13) { //Enter keycode
	    var id = $(this).closest('tr').data('item-id');
		$('#update-cart').submit();
	}
});


