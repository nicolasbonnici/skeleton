$(document).ready(function() {
    
    var ux = $.fn.userExperience();
    
    // load asynch content
    ux.loadView();    
    
    // Fire UI
    ux.initUi();    
    $(document).ajaxStop(function() {
    	// Also after asynch call
        ux.initUi();        
    });

    
    /**
     * ************* Events  ****************
     * Asynchrone request
     */ 
    
    // Envoyer une requete XHR lors d'un clic ou d'un change sur un select
    $('body').on('click', '.ui-sendxhr', function(){
        ux.sendXHR($(this));
    });
    $('body').on('change', '.ui-sendxhronchange', function(){
        ux.sendXHR($(this));
    });
    
    // Envoyer des formulaires en asynchrone
    $('body').on('click', '.ui-sendform', function() {		
    	ux.sendForm($(this));		
    });        
	
    // Envoyer des formulaires en asynchrone
    $('.ui-reload').on('click', function() {	
    	if (typeof($(this).data('sreloadtarget')) !== 'undefined') {
    		ux.loadView($($(this).data('sreloadtarget')));		
    	} else {
    		ux.loadView();
    	}
    	return false;
    });        
    
    // load on scroll
    $('.ui-loadscroll').scroll(function(){
        if ($(this).scrollTop() === ($(this).prop('scrollHeight') - $(this).outerHeight())){
        	if ($(this).find('.ui-scroll-loadable')) {
        		$('.ui-scroll-loadable').each(function() {
        			ux.sendNotification('Information', 'loading', 'info', 'glyphicon glyphicon-cog');
        			ux.loadScroll($(this));        		        			
        		});
        	}
        }
        return false;
    });    

    /**
     * Ux bindings
     */
    
    // ui confirm @todo afficher une modale au lieu des dialogs natifs
    $('body').on('click', '.ui-confirm', function() {
    	if (!confirm('Etes vous sure?')) {
    		return false;
    	}
    });
    
    // selectAll
    $('body').on('click', '.ui-select-all', function() {
    	var sCheckboxSelector = $(this).data('checkbox-class');
    	var bCheckState = $(this).is(':checked');
    	if (typeof(sCheckboxSelector) !== 'undefined') {
     		$('.ui-select.' + sCheckboxSelector).prop('checked', bCheckState);
    	}
    });
    
    // toggle elements
    $('body').on('click', '.ui-toggle', function() {
    	if ($($(this).data('toggle-selector')).size() !== 0) {
	    	$($(this).data('toggle-selector')).toggleClass('hide');
    	}  	
    });
    
    // Modal close event with a updated form on it
    $('.modal').on('hide', function() {
        var sSelector = $(this).attr('id');
        if ($(sSelector + ' form').size() !== 0) {
            $.each($(sSelector + ' form'), function() {
                if ($(this).data('bHasChange')) {
                    alert('Warning!');
                }
            });
        }
    });

    
    // popover
	$('.ui-toggle-popover').popover({
		container: 'body', 
        placement : 'auto', 
        html: true, 
        animation: true,
        content : function() {
        	return $($(this).data('popover')).html();
        }
	});
    
    // Login popover
	$('.ui-login-popover').popover({
		container: 'body', 
        placement : 'auto', 
        title : '<h4>Login</h4>',
        html: true, 
        animation: true,
        
        content : $('#login-popover').html()
	});
    
});