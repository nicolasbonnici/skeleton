$(document).ready(function() {
    
    var ui = $.fn.userInterface();
    
    // load asynch content
    ui.loadView();    
    
    // Fire UI
    ui.initUi();    
    $(document).ajaxStop(function() {
        ui.initUi();        
    });

    
    // ************* Bindings ****************
    
    // ---------------------------- Asynchrone request

    // Envoyer une requete XHR lors d'un clic ou d'un change sur un select
    $('body').on('click', '.ui-sendxhr', function(){
        ui.sendXHR($(this));
    });
    $('body').on('change', '.sendXHROnChange', function(){
        ui.sendXHR($(this));
    });
    
    // Envoyer des formulaires en asynchrone
    $('body').on('click', '.ui-sendform', function() {		
    	ui.sendForm($(this));		
    	return false;	
    });        
	
    // Envoyer des formulaires en asynchrone
    $('.ui-reload').on('click', function() {	
    	if (typeof($(this).data('sreloadtarget')) !== 'undefined') {
    		ui.reload($($(this).data('sreloadtarget')));		
    	}
    });        
    
    // load on scroll
    $('.ui-loadscroll').scroll(function(){
        if ($(this).scrollTop() === ($(this).prop('scrollHeight') - $(this).outerHeight())){
        	if ($(this).find('.ui-scroll-loadable')) {
        		$('.ui-scroll-loadable').each(function() {
        			ui.sendNotification('Information', 'loading', 'info', 'glyphicon glyphicon-cog');
        			ui.loadScroll($(this));        		        			
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