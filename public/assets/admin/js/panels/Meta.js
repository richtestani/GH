/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Meta = (function($)
{
    return {
        cache: {},
		data: null,
        init: function()
        {
			Meta.setData( {data:["numwords", "numimages", "numletters"]} );

        },
        cacheThis: function(name, value)
        {
            Meta.cache[name] = value;
        },
        getCache: function(name)
        {
            return Meta.cache[name];
        },
        events: function()
        {
            $('#textbody').keyup(function()
            {
                Meta.updatePanel();
            })
        },
        numletters: function()
        {
            var text = $('#textbody').text();
            var chars = text.length;
            
            return chars;
        },
        
        numwords: function()
        {
            var text = $('#textbody').text();
            var words = text.split(" ");
            var numWords = words.length;
            
            return numWords;
        },
        numimages: function()
        {
           return $('#textbody').find('img').length;
        },
        numsnippets: function()
        {
            return 0;
        },
        parseData: function(data)
        {
			//$('form #textbody').val()
            output = '<ul id="meta-list" class="list-unstyled list-group panel-listing">';
            for(i in data.data)
            {
				method = data.data[i];
               value = Meta[method].call(this);
               output += '<li><span class="list-group-item panel-name" href="#" data-add-to-body="false"><span data-meta-name="'+i+'">'+data.data[i]+':</span><span class="data-meta-value="'+i+'"> '+value+'</span></span></li>'
            }
            output += '<ul>';
            return output;
        },
        
        updatePanel: function(data)
        {
			RichApp.push(Meta.data);
        },
		
  	  getPanelContent: function(id)
  	  {
		  //no data to fetch
  	  },
	  
  	  setData: function(data)
  	  {
  		  Meta.data = data;
		  
  	  },
  	  getData: function()
  	  {
  		  if(typeof Meta.data !== 'null')
  		  {
  		  	return Meta.data;
  		  }
  		  else
  		  {
  			return null;
  		  }
  	  }
    };
})(jQuery);
Meta.init();