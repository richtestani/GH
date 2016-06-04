/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Settings = (function($)
{
    
    return {
      cache: {},
		data: null,
      pageCategory: 0,
      pageStatus: 0,
      pageTemplate: '',
      defaultImage: '',
      defaultImagePath: '',
      init: function()
      {
         if($('#page_category').length == 0)
        {
            $('#main-content form').append('<input type="hidden" name="page_category"  id="page_category" />');
        }
        
        if($('#page_template').length ==0)
        {
            $('#main-content form').append('<input type="hidden" name="template"  id="page_template" />');
        }
        
        if($('#publish_status').length ==0)
        {
            $('#main-content form').append('<input type="hidden" name="publish_status"  id="publish_status" />');
        }
      },
      cacheThis: function(name, value)
      {
          Settings.cache[name] = value;
      },
      getCache: function(name)
      {
          return Settings.cache[name];
      },
      events: function()
      {
            $('#ra-slide-panel-content').on('change', 'select.panel-item-changable', function()
            {
                menu = $(this).val();
                itemId = $(this).attr('name');
                
                if(itemId == 'published')
                {
                    Settings.publishStatus = menu;
                    $('#publish_status').val(menu);
                }
                
                if(itemId == 'template')
                {
                    Settings.pageTemplate = menu;
                    $('#page_template').val(menu);
                }
                
                if(itemId == 'category')
                {
                    Settings.pageCategory = menu;
                    $('#page_category').val(menu);
                }
                
                Settings.updateCache();
            });
            
            $('#ra-slide-panel-content').on('click', '.page-status-checked', function()
            {
				//$('.page-status-checked').removeAttr('checked');
                var status = $(this).val();
                $('#publish_status').val(status);
				//$(this).attr('checked', 'checked');
                Settings.pageStatus = status;
            });
            
            $('#ra-slide-panel-content').on('click', 'a.add-image-default', function()
            {
                Settings.showImages();
            });
            
            $('body').on('click', 'a.set-default-image', function()
          	{
              uid = $(this).data('uid');
			  
              imgsrc = '<img src="'+$(this).data('image-src')+'" class="default-image-img" />';
              $('#default_image_show').html('').html(imgsrc);
              Settings.defaultImage = uid;
              Settings.defaultImagePath = $(this).data('image-src');
              if($('#default_image').length == 0)
              {
                  $('#main-content form').append('<input type="hidden" name="default_image" id="default_image" />');
                  $('#default_image').val(Settings.defaultImage);
              }
              else
              {
                  $('#default_image').val(Settings.defaultImage);
              }
              $('#overlay').remove();
              return false;
          });
		  
		  $('body').on('click', '.close-grid', function()
	  		{
	  			$('#overlay').fadeOut();
	  		})
            
      },
      
      onPanelLoaded: function()
      {
          if($('#page_category').length == 1)
          {
              Settings.pageCategory = $('#page_category').val();
          }
          
           if($('#page_template').length == 1)
          {
              Settings.pageTemplate = $('#page_template').val();
          }
          $('select.panel-item-changable[name="category"]').val(Settings.pageCategory);
          $('select.panel-item-changable[name="template"]').val(Settings.pageTemplate);
          
          if($('#publish_status').length == 1)
          {
              Settings.pageStatus = $('#publish_status').val();
          }
          if($('#default_image').length == 1)
          {
              Settings.defaultImage = $('#default_image').val();
              $('#default_image_show').html('<img src="'+Settings.defaultImagePath+'" />')
          }
          
           //$('.page-status-checked[value="'+Settings.pageStatus+'"]').attr('checked', 'checked');
      },
      
      updateCache: function(el)
      {
          data = $('div[data-content-id="settings"]').html();
          this.cacheThis('body', data);
      },
      parseData: function(data)
      {
          settings = '<div class="panel">';
          for(i in data)
          {
              settings += data[i];
          }
          settings += '</div>';
          Settings.cacheThis('body', settings);
          return settings;
      },
      showImages: function()
      {
          if($('#overlay').length == 0)
                $('<div id="overlay" />').insertBefore('#upload-form');
          
          $("#overlay").fadeIn().css('display', 'table');
          $.ajax({
               url: '/admin/images/imageGrid/default',
               success: function(html)
               {
                   $('#overlay').html(html);
               }
            });
      },
	  getPanelContent: function(id)
	  {
		$.ajax({
			url: '/admin/settings/panel/'+id,
			type: 'post',
			success: function(data)
			{
				Settings.success = true;
				Settings.data = data;
			}
		});
		
	  },
	  
	  setData: function(data)
	  {
		  Settings.data = data;
		  
	  },
	  getData: function()
	  {
		  if(typeof Settings.data !== 'null')
		  {
		  	return Settings.data;
		  }
		  else
		  {
			  return null;
		  }
	  }
      
        
    };
    
})(jQuery);