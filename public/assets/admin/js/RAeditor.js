/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the RAEditor.
 */


RAEditor = (function($)
{
    return {
      
        init: function()
        {
            var bodyText = $('textarea#body').val();
            $('<div id="textbody" />').attr('contenteditable', '').html(bodyText).insertBefore('textarea#body');
            $('textarea#body').css('display', 'none');
			
			$('#main-content').on('click', '#htmlview', function()
			{
				RAEditor.htmlView();
			});
			$('#main-content').on('click', '#textview', function()
			{
				RAEditor.textView();
			});
			$('#submit').click(function(){
				
				text = $('#textbody').html();
				$('#body').val(text);
				
			});
		},
        hasRAEditor: function()
        {
            return ($('textarea#body').length == 1) ? true : false;
        },
		
		textView: function()
		{
            text = $('#textbody').html();
			$('.btn-group  button').removeClass('active');
			$('#textview').addClass('active');
			$('#textbody').css('display', 'none');
            $('textarea#body').text(text).css('display', 'block').css('height', '500px');
		},
		htmlView: function()
		{
            text = $('#body').text();
			$('.btn-group  button').removeClass('active');
			$('#htmlview').addClass('active');
			$('#body').css('display', 'none');
            $('#textbody').html(text).css('display', 'block');
		}
        
    };
})(jQuery);
RAEditor.init();