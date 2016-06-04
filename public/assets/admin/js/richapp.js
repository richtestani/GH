/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


RichApp = (function($, MENUS)
{
    
    return {
		
		//properties
		activePanel: null,
        activatePanelContent: "",
		activePanelName: "",
		panels: [],
		//application initilizations
		init: function()
		{
			//left admin menu toggle script
			Menus.init();
			
			//turn on text editor
            
			
            //Editor
            if($('#textbody').length == 1)
            {
		        var editor = new MediumEditor('#textbody', {
		                toolbar: {
		                    buttons: [
								'bold', 
								'italic', 
								'underline', 
								'strikethrough', 
								'quote', 
								'anchor', 
								'image', 
								'justifyLeft', 
								'justifyCenter', 
								'justifyRight', 
								'justifyFull', 
								'orderedlist', 
								'unorderedlist', 
								'pre',
								'h1', 
								'h2', 
								'h3', 
								'h4',
								'h5',
								'removeFormat'],
		                },
		                buttonLabels: 'fontawesome',
		                anchor: {
		                    targetCheckbox: true
		                }
		            });
            }
			
			//handles popout panel and loads panel content
            $('#ra-slide-panel-tabs').on('click', 'li', function()
            {
				var $el = $(this);
				if($el.hasClass('active') || !$el.hasClass('slide-toggle'))
				{
					return false;
				}
				$('#ra-slide-panel .loader').css('display', 'block');
                var activePanel = $(this).data('menu-id');
                $('#ra-slide-panel-tabs li').removeClass('active');
                //check if its minimized
                if(SlidePanel.getSlidePanelSize() == 'min')
                {
                    SlidePanel.openSlidePanel(this);
                }
                
                
                //set active panel
                RichApp.activePanel = activePanel;
                //make tab active
                $(this).addClass('active');
                SlidePanel.closeAllPanels();
                RichApp.activatePanelContent($(this).data('menu-id'));
                //RichApp.updateWorkingArea();
                return false;
            });
			
			
            //Upload form
            $('body').on('click', '.image-uploader', function()
            {
               Uploader.showForm();
            });
		},
		
        activatePanelContent: function(p)
        {
			
			//set active panel
			var first = p[0].toUpperCase();
			RichApp.activePanelName = first + p.substr(1, p.length);
			
			//set the object
			RichApp.activePanel = window[RichApp.activePanelName];
			
            //add panel to list and run events
			//happens only once
			if($.inArray(p, RichApp.panels) == -1)
			{
				RichApp.panels.push(p);
				RichApp.activePanel.events();
			}
            $('.ra-content').removeClass('active');
            $('div[data-content-id="'+RichApp.activePanel+'"]').addClass('active');
            if($('#ra-slide-panel-content').css('display') == 'block')
            {
				
                this.loadPanelContent();
            }
        },
		
		loadPanelContent: function()
		{
			id = ($('#page_id').length == 1) ? $('#page_id').val() : 0;
			
			//fetch data
			RichApp.activePanel.getPanelContent(id);
			RichApp.interval = window.setInterval(function(){
				if( RichApp.activePanel.getData() )
				{
					data = RichApp.activePanel.getData();
					RichApp.parseData(data);
				}
			}, 2000);
			
		},
		
		parseData: function(data)
		{
			//parse data
			var segment = RichApp.activePanelName.toLowerCase();
			
			parsed = (typeof data === 'object') ? data: $.parseJSON(data);
			var result = RichApp.activePanel.parseData(parsed);
			dataname = 'div[data-content-id="'+segment+'"]';

			$(dataname).html(result).fadeIn();
			$('#ra-slide-panel .loader').css('display', 'none');
			window.clearInterval(RichApp.interval);
		},
		
		push: function(data)
		{
			var segment = RichApp.activePanelName.toLowerCase();
			var result = RichApp.activePanel.parseData(parsed);
			dataname = 'div[data-content-id="'+segment+'"]';
			$(dataname).html(result).fadeIn();
		}
        
    };
    
})(jQuery, Menus);

RichApp.init();

