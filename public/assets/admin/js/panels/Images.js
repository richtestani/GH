/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Images = (function($)
{
    
    return {
      cache: {},
		data: null,
      init: function()
      {
      },
      cacheThis: function(name, value)
      {
          Images.cache[name] = value;
      },
      getCache: function(name)
      {
          return Images.cache[name];
      },
      events: function()
        {
            $('#ra-slide-panel-content').on('click', '.panel-item-image', function(e)
            {
                //$this = e.target;
                //Images.insertIntoBody($this);
            });
			
            $('#ra-slide-panel-content').on('click', '.thumbnail', function(e)
            {
				$('.insert-image-links').css('display', 'none');
                $(this).find('.insert-image-links').css('display', 'block');
            });
			
			$('#ra-slide-panel-content').on('click', '.insert-image-link', function(){
				domain = $(this).closest('.thumbnail').data('image-domain');
				size = $(this).data('image-size');
				width = $(this).data('image-width');
				src = domain+$(this).data('image-src');
				id = $(this).closest('.thumbnail').attr('id');
				
				
				Images.insertIntoBody(size, src, width, id);
			});
			
			$('body').click(function(e){
				$target = $(e.target);
				if( !$target.hasClass('insert-image-link'))
				{
					$('.insert-image-links').css('display', 'none');
				}
			});
			
			$('#textbody').on('hover', '.page-image-inline', function(){
				$(this).wrap('<div class="inline-img-wrap" />').prepend('<a href="#" class="inline-edit-image">Edit Image</a>');
			});
			
			$('#textbody').on('click', '.page-image-inline', function(){
				Images.openAviary(this);
			});
			
			//initialize Aviary
			Images.featherEditor = new Aviary.Feather({
			        apiKey: '6fddc51b-6e3c-4563-8475-b11aa7f05b1d',
			        onSave: function(imageID, newURL) {
			            var img = document.getElementById(imageID);
			            img.src = newURL;
						$.ajax({
							url: "/admin/images/saveImageFromURL",
							data: {"src":img.src},
							type: "post"
						})
			        },
		            onError: function(errorObj) {
		              alert(errorObj.message);
		            }
			    });
			
        },
      insertIntoBody: function(size, src, width, id)
      {
          
          var imgSize = width;
          if(typeof imgSize !== 'undefined')
          {
              sizeClass = 'w-'+imgSize;
          }
          else
          {
              sizeClass = 'large';
          }
          var imgSrc = src;
          //var targetSize 	= $(el).data('img-src-size');
		  //var imgPath 		= $(el).closest('.thumbnail').data('image-path');
		  //var imgName 		= $(el).closest('.thumbnail').data('image-filename');
		  //var targetImg		= imgPath + '/large/' + imgName;
		  id="editor-"+id
          var img = '<img id="'+id+'" class="page-image-inline '+sizeClass+'" src="'+imgSrc+'" />';

        
          text = $('#textbody').html();
          $('#textbody').html(text+img);
      },
      parseData: function(data)
      {
          imgs = '<a href="#" class="image-uploader">Upload an Image</a>';
          imgs += data.images;
          Images.cacheThis('body', imgs);
          return imgs;
      },
      showImages: function()
      {
          $.ajax({
               url: '/admin/images/imageGrid',
               success: function(html)
               {
                   $('#overlay').html(html);
               }
            });
      },
	  
	  getPanelContent: function(id)
	  {
		$.ajax({
			url: '/admin/images/panel/'+id,
			type: 'post',
			success: function(data)
			{
				Images.success = true;
				Images.data = data;
			}
		});
		
	  },
	  
	  openAviary: function(el)
	  {
		  
		  id = $(el).attr('id');
		  src = $(el).attr('src');

        Images.featherEditor.launch({
            image: id,
            url: src
        });
		
		return false;
	  },
	  
	  setData: function(data)
	  {
		  Images.data = data;
		  
	  },
	  
	  getData: function()
	  {
		  if(typeof Images.data !== 'null')
		  {
		  	return Images.data;
		  }
		  else
		  {
			  return null;
		  }
	  }
        
    };
    
})(jQuery);