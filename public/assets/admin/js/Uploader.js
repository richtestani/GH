/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Uploader = (function($)
{
    
    return {
        
        showForm: function()
        {
            if($('#overlay').length == 0)
                $('<div id="overlay" />').insertBefore('#upload-form');
            
            $("#overlay").fadeIn().css('display', 'table');
            Uploader.processImages();
        },
        
        processImages: function()
        {
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

