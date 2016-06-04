/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Menus = (function($)
{
    return {
        menuBoxSize: 'max',
        menuBoxId: '#ra-nav',
        mainNavState: 'open',
        subNavState: 'closed',
        subNavName: '',
        subNavMenu: ['add', 'edit', 'delete'],
        currentMenuChoice: 'pages',
        init: function()
        {
          openState = (typeof Cookies.get('menu-open-state') === 'undefined') ? this.subNavState : Cookies.get('menu-open-state');
          menuBoxSize = (typeof Cookies.get('menu-box-size') === 'undefined') ? 'max' : Cookies.get('menu-box-size');
          currentMenuChoice = (typeof Cookies.get('currentMenuChoice') === 'undefined') ? this.currentMenuChoice : Cookies.get('currentMenuChoice');
          if(openState == 'closed')
          {
              
              this.subNavState = 'open';
              this.menuBoxSize = 'max';
          }
          else
          {
              this.subNavState = 'closed';
              this.menuBoxSize = 'max';
          }
          this.events();
          this.toggleSubNav(currentMenuChoice);
          this.updateMenus();
          
        },
        events: function()
        {
          $('.sidebox-item a').click(function()
          {
              $('.sidebox-item').removeClass('active');
              var item = $(this).closest('.sidebox-item').data('menu-name');
			  $(this).closest('.sidebox-item').addClass('active');
              
              Menus.currentMenuChoice = item;
              Cookies.set('currentMenuChoice', item);
              this.menuBoxSize = 'med';
              Menus.toggleMenuData(item);
              return false;
              
          });
        },
        toggleSubNav: function(item)
        {
            this.subNavState == 'open';
            $('#action-menu').removeClass('open-state-closed')
                            .addClass('open-state-open')
                            .attr('data-open-state', 'open')
                            .css('display', 'block')
                            .animate('width', '5%');
            
            $('#action-menu').find('#nav-sub').find('ul').removeClass('active').addClass('hidden');
            $('#action-menu').find('#nav-sub').find('.'+item).removeClass('hidden').addClass('active');
            //Cookies.set('menu-open-state', 'open');
            this.updateMenus();
            
        },
        updateMenus: function()
        {
            this.updateNavBox();
            this.updateMainNavBox();
        },
        getSubNavOpenState: function()
        {
            return this.subNavState;
        },
        updateMainNavBox: function()
        {
            if(this.subNavState == 'closed')
            {
                //$('#main-menu').css('width', '100%');
            }
            else
            {
                //$('#main-menu').css('width', '50%');
            }
        },
        updateNavBox: function()
        {
            Cookies.set('menu-box-size', this.menuBoxSize);
            $(this.menuBoxId).attr('data-menu-size', this.menuBoxSize);
            switch(this.menuBoxSize)
            {
                case 'max':
                    //$(this.menuBoxId).css('width', '14%');
                    break;
                    
                case 'med':
                    //$(this.menuBoxId).css('width', '7%');
                    break;
            }
           
        },
        toggleMenuData: function(item)
        {
            $('#action-menu ul#nav-sub ul').removeClass('active').addClass('hidden');
            $('#action-menu ul#nav-sub').find('ul.'+item).addClass('active').removeClass('hidden');
            
        }
    };
})(jQuery);