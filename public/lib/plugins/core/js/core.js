$(document).ready(function() {
    
    var ux = $.fn.userExperience();
    
    // load asynch content
    ux.loadView();    
    
    // Fire UI
    ux.initUi();    
    $(document).ajaxStop(function() {
        ux.initUi();        
    });

    
    // ************* Bindings ****************
    
    // ---------------------------- Asynchrone request

    // Envoyer une requete XHR lors d'un clic ou d'un change sur un select
    $('body').on('click', '.ui-sendxhr', function(){
        ux.sendXHR($(this));
    });
    $('body').on('change', '.sendXHROnChange', function(){
        ux.sendXHR($(this));
    });
    
    // Envoyer des formulaires en asynchrone
    $('body').on('click', '.ui-sendform', function() {		
    	ux.sendForm($(this));		
    	return false;	
    });        
	
    // Envoyer des formulaires en asynchrone
    $('.ui-reload').on('click', function() {	
    	if (typeof($(this).data('sreloadtarget')) !== 'undefined') {
    		ux.reload($($(this).data('sreloadtarget')));		
    	}
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
    
    // ui confirm @todo afficher une modale au lieu des dialogs natifs
    $('body').on('click', '.ui-confirm', function() {
    	if (!confirm('Etes vous sure?')) {
    		return false;
    	}
    });
    
    // @todo bug incomprehensible!!!!!!!!!!!!!!!
    $('body').on('click', '.ui-select-all', function() {
    	var sContainerSelector = $(this).data('container');
    	if (typeof(sContainerSelector) !== 'undefined') {
     		$(sContainerSelector + ' .ui-select').each(function(index, bChecked) {
    			$(this).attr('checked', $(sContainerSelector).find('.ui-select-all').is(':checked'));
    			console.log($(sContainerSelector).find('.ui-select-all').is(':checked'));
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