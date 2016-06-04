/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Tags = (function($)
{
    return {
      cache: {},
      currentTags: '',
      init: function()
      {
		  if($('#tags-values').length == 0)
		  {
		  	 $('#main-content form').append('<input type="hidden" id="tags-values" name="tags"  />');
		  }
		  Tags.events();
		  

      },
      cacheThis: function(name, value)
      {
          Tags.cache[name] = value;
      },
      getCache: function(name)
      {
          return Tags.cache[name];
      },
      events: function()
      {

		  $('#ra-slide-panel').on('keyup', '.panel-item-tags', function(){
              vals = $(this).val();
			  console.log(vals);
              $('#tags-values').val(vals);
		  });
		  
          $('.panel-item-tags').blur(function()
          {
              vals = $(this).val();
              $('#tags-values').val(vals);
              
          });
      },
      onPanelLoaded: function()
      {
          val = $('#tags-values').val();
          $('.panel-item-tags').val(val);
      },
        parseData: function(data)
      {
          tags = '<div class="panel">';
          for(i in data)
          {
              tags += data[i];
          }
          tags += '</div>';
          Tags.cacheThis('body', tags);
          return tags;
      },
	  getPanelContent: function(id)
	  {
		$.ajax({
			url: '/admin/tags/panel/'+id,
			type: 'post',
			success: function(data)
			{
				Tags.success = true;
				Tags.data = data;
			}
		});
		
	  },
	  
	  setData: function(data)
	  {
		  Tags.data = data;
		  
	  },
	  getData: function()
	  {
		  if(typeof Tags.data !== 'null')
		  {
		  	return Tags.data;
		  }
		  else
		  {
			  return null;
		  }
	  }
     
    };
})(jQuery);
Tags.init();