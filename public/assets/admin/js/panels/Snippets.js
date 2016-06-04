/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Snippets = (function($)
{
    return {
        cache: {},
        init: function()
        {
            
        },
        cacheThis: function(name, value)
      {
          Snippets.cache[name] = value;
      },
      getCache: function(name)
      {
          return Snippets.cache[name];
      },
        events: function()
        {
            $('#ra-slide-panel').on('click', '.panel-item-snippet', function()
            {
                Snippets.insertIntoBody(this)
            });
        },        
        parseData: function(data)
        {
          var snippets = '<ul id="snippets-list" class="list-unstyled list-group panel-listing">';
          for(i in data)
          {
              snippets += '<li><a class="list-group-item panel-item-snippet panel-name" href="#" data-add-to-body="true"><span class="snippet_name">'+data[i].name+'</span>';
              snippets += '<p><small class="description panel-text">'+data[i].description+'</small></p>';
              snippets +='</a></li>';
          }
          snippets + '</ul>';
          this.cacheThis('body', snippets)
          return snippets;
        },
        
        insertIntoBody: function(el)
        {
            var name = $(el).find('.snippet_name').text();
            var snippet = '[['+name+']]';
            text = $('#textbody').html();
            $('#textbody').html(text+snippet);
        }
        
    }
})(jQuery);