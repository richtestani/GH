Categories = (function($){
    return {
	    showImages: function()
	    {
	        if($('#overlay').length == 0)
	            $('<div id="overlay" />').insertBefore('#upload-form');
	        	$("#overlay").fadeIn().css('display', 'table');
	        $.ajax({
	             url: '/admin/images/imageGrid',
	             success: function(html)
	             {
	                 $('#overlay').html(html);
	             }
	          });
	    }
    }
})(jQuery);

$('#image-link').on('click', 'a#set-default-image', function()
{
    Categories.showImages();
});

$('body').on('click', '#overlay', function(){
		$('#overlay').fadeOut(300, function()
	{
		$(this).remove();
	});
});

  $('body').on('click', 'a.set-image', function()
{
    uid = $(this).data('uid');
    imgsrc = '<img src="'+$(this).data('image-src')+'" />';
    $('#default_image_show').html('').html(imgsrc);
    //Settings.defaultImage = uid;
    //Settings.defaultImagePath = $(this).data('image-src');
    if($('#default_image').length == 0)
    {
        $('#main-content form').append('<input type="hidden" name="default_image" id="default_image" />');
        $('#default_image').val(uid);
		$('#default_image_show').html(imgsrc);
    }
    else
    {
        $('#default_image').val(uid);
    }
    $('#overlay').remove();
    return false;
});