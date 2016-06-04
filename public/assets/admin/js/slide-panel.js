/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

SlidePanel = (function($)
{
    
    return {
		
        init: function() {
          this.activePanel = $('#ra-slide-panel .slide-toggle:first').data('menu-id');
          var slidePanelSize = (typeof Cookies.get('slide-panel-size') === 'undefined') ? this.slidePanelSize : Cookies.get('slide-panel-size');
        },
		
		openSlidePanel: function(panel) {
            this.slidePanelSize = 'max';
			$(panel).css('display', 'block');
			
		},
        closeSlidePanel: function(){
            this.slidePanelSize = 'min';
        },
        getSlidePanelSize: function()
        {
            return this.slidePanelSize;
        },
		closeAllPanels: function()
		{
			$('#ra-slide-panel-tabs').find('.ra-content').css('display', 'none')
		}
		
    };
})(jQuery);
