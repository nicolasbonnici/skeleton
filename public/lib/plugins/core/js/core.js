
(function($) {
        $.fn.core = function(params) {
            var ui = $.fn.userInterface();
            
            var core = {        
                
                preload: function(selector) {
                    if ($.fn.queryLoader2) {
                        $(selector).queryLoader2({
                                barColor: "#48acde",
                                backgroundColor: "#fff",
                                percentage: true,
                                barHeight: 50,
                                completeAnimation: "grow",
                                deepSearch: true
                        });                              
                    }          
                },

                load: function(repWrap, request) {
                    $(repWrap).fadeOut(300).empty().load('/'+request['controller']+'/'+request['action']).fadeIn(150);
                    core.hideNotif();		
                },

                loadScroll: function($obj) {

            		var sSelector = '#'+$obj.attr('id');                			
            		var iOffSet = $(sSelector).data('ioffset', $(sSelector + ' .item').length);           		         		            		
            		var aData = $(sSelector).data();
                    $.ajax({
                        type: 'POST',
                        url: '/'+$obj.attr('data-module')+'/'+$obj.attr('data-controller')+'/'+$obj.attr('data-action'),
                        data: aData,
                        beforeSend : function(preload) {
                        	$obj.data('initialContent', $obj.html());
                        },
                        success: function(rep){
                        	if (rep.status === 1) { // @see if XHR_STATUS_OK                                               		                                    		
                        		$obj.append(rep.content);
                        		$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		
                        	}
                        },
                        error: function(rep){                            
                            // Restore cached content
                        	$obj.append($(sSelector).data('initialContent'));  
                    		$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		                        	
                        },
                        complete: function(){
                        	$.pnotify_remove_all();
                        	$obj.removeData('initialContent');
                        }
                    });    
                },

                sendXHR: function(oHandler) {

                    var sUrl = '';
                    if (
                		typeof(oHandler.data('module')) !== 'undefined' && 
                		typeof(oHandler.data('controller')) !== 'undefined' && 
                		typeof(oHandler.data('action')) !== 'undefined' 
                    ) {
                    	sUrl = '/'+oHandler.data('module')+'/'+oHandler.data('controller')+'/'+oHandler.data('action');
                    } else if (typeof(oHandler.data('url')) !== 'undefined') {
                    	sUrl = oHandler.data('url');
                    } else {
                    	sUrl = oHandler.attr('href');
                    }

                    var $domTarget = $(oHandler.attr('data-selector'));
                    if (typeof($domTarget) === 'undefined') {
                    	$domTarget = $(oHandler.attr('href'));
                    }
                    
                    $.ajax({
                        type: 'POST',
                        url: sUrl,
                        beforeSend : function(preload) {
                            // Mettre en cache et vider l'objet qui contiendra la reponse
                            $domTarget.data('initialContent', $domTarget.html());		                        	
                        	$domTarget.empty();                                                            
                        },
                        success: function(rep){
                        	if (rep.status == 1) { // @see if XHR_STATUS_OK
                            	$domTarget.append(rep.content);            
                        		$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		                            	
                        	}
                        },
                        error: function(err){                            
                            // Restore cached content
                        	$domTarget.append($domTarget.data('initialContent'));    
                    		$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		
                        	
                        },
                        complete: function(){
                        	$domTarget.removeData('initialContent');
                        }
                    });
                },
                
                // Envoyer un formulaire en asynchrone
                sendForm: function(obj) {

                    var sFormSelector = obj.data('form');
                    if (typeof(sFormSelector) === 'undefined') {
                    	sFormSelector = obj.attr('href');
                    }
                    var $formTarget = $(sFormSelector);
                    var $domTarget = $(sFormSelector).parent();

                    // Serialiser les forms inputs
                    var data = $formTarget.serialize();
                    if($(sFormSelector+' div[contenteditable=true]').size() != 0) {
                        $(sFormSelector+' .ui-editor').each(function() {
                            data += '&'+oHandler.data('name')+'='+oHandler.parent().find('div[contenteditable=true]:first').html();
                        });
                    }					

                    $.ajax({
                        type: 'POST',
                        url: $formTarget.attr('action'),
                        data: data,
                        beforeSend : function(preload) {
                        	// Mettre en cache et vider l'objet qui contiendra la reponse
                        	$domTarget.data('initialContent', $domTarget.html());			
                        	$domTarget.empty();                                                            
                        },
                        success: function(rep){
                        	if (rep.status == 1) { // @see if XHR_STATUS_OK
                            	$domTarget.append(rep.content);                        		
                        	}
                        	if (obj.hasClass('refreshOnCallback')) {
                        		core.loadView();
                        	}
                        },
                        error: function(err){                            
                            // Restore cached content
                        	$domTarget.append($domTarget.data('initialContent'));                            
                        },
                        complete: function(){
                        	$domTarget.removeData('initialContent');
                        }
                    });
                },
                
                // Load some part of the view asynch to boost loadtime and simplify view refresh process
                loadView: function() {
                	if ($('.ui-loadable').size() > 0) {
                		
                		$('.ui-loadable').each(function() {

                            var sUrlTarget = '';
                            if (
                        		typeof($(this).data('module')) !== 'undefined' && 
                        		typeof($(this).data('controller')) !== 'undefined' && 
                        		typeof($(this).data('action')) !== 'undefined' 
                            ) {
                            	sUrlTarget = '/'+$(this).data('module')+'/'+$(this).data('controller')+'/'+$(this).data('action');
                            } else if (typeof($(this).data('url')) !== 'undefined') {
                            	sUrlTarget = $(this).data('url');
                            } else {
                            	ui.sendNotification('Error', 'No url specified to load ui-loadable div #' + $(this).attr('id'), 'error', 'glyphicon glyphicon-warning', false);
                            	return false;
                            }

                            var sSelector = '#'+$(this).attr('id');

                            var aData = $(sSelector).data();
                            $.ajax({
                                type: 'POST',
                                url: sUrlTarget,
                                data: aData,
                                beforeSend : function(preload) {
                                	// Mettre en cache et vider l'objet qui contiendra la reponse
                                	$(this).data('initialContent', $(this).html());	                                 
                                	$(sSelector).empty();                                                            
                                },
                                success: function(rep){
                                	if (rep.status === 1) { // @see if XHR_STATUS_OK                                               		                                    		
                                		$(sSelector).append(rep.content);                                              
                                		$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		
                                	}
                                },
                                error: function(err){                            
                                    // Restore cached content
                                	$(sSelector).append($(sSelector).data('initialContent'));                            
                                },
                                complete: function(){
                                	$(sSelector).removeData('initialContent');
                                }
                            });             				
                		});
                		
                	}
                },             
                
                reload: function(oItem) {             			
                	
                	var sUrlTarget = '';
                	if (
                			typeof(oItem.data('module')) !== 'undefined' && 
                			typeof(oItem.data('controller')) !== 'undefined' && 
                			typeof(oItem.data('action')) !== 'undefined' 
                	) {
                		sUrlTarget = '/'+oItem.data('module')+'/'+oItem.data('controller')+'/'+oItem.data('action');
                	} else if (typeof(oItem.data('url')) !== 'undefined') {
                		sUrlTarget = oItem.data('url');
                	} else {
                		ui.sendNotification('Error', 'No url specified to load ui-loadable div #' + oItem.attr('id'), 'error', 'glyphicon glyphicon-warning', false);
                		return false;
                	}
                	
                	var sSelector = '#'+oItem.attr('id');
                	
                    var aData = $(sSelector).data();                	
                	
                	$.ajax({
                		type: 'POST',
                		url: sUrlTarget,
                        data: aData,                		
                		beforeSend : function(preload) {
                        	// Mettre en cache et vider l'objet qui contiendra la reponse
                        	oItem.data('initialContent', oItem.html());	                   			
                			$(sSelector).empty();
                			$container.data('grid-loaded', false);
                			ui.sendNotification('Information', 'Chargement en cours...', 'info', 'glyphicon glyphicon-info-sign');
                		},
                		success: function(rep){
                			if (rep.status === 1) { // @see if XHR_STATUS_OK                                               		                                    		
                				$(sSelector).append(rep.content);                                              
                				$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		
                			}
                		},
                		error: function(err){                            
                			// Restore cached content
                			$(sSelector).append($(sSelector).data('initialContent'));                            
                		},
                		complete: function(){
                			$(sSelector).removeData('initialContent');
                			ui.hideNotifications();
                		}
                	});                   	
                }
            }
            

            //~ // Permettre le chaînage par jQuery
            return core;
        };
})(jQuery);


$(document).ready(function() {
    
    var ui = $.fn.userInterface();
    var core = $.fn.core();   
    
    // load asynch content
    core.loadView();    
    
    // Fire UI
    ui.initUi();    
    $(document).ajaxStop(function() {
        ui.initUi();        
    });

    
    // ************* Bindings ****************
    
    // ---------------------------- Asynchrone request

    // Envoyer une requete XHR lors d'un clic ou d'un change sur un select
    $('body').on('click', '.ui-sendxhr', function(){
        core.sendXHR($(this));
    });
    $('body').on('change', '.sendXHROnChange', function(){
        core.sendXHR($(this));
    });
    
    // Envoyer des formulaires en asynchrone
    $('body').on('click', '.ui-sendform', function() {		
    	core.sendForm($(this));		
    	return false;	
    });        
	
    // Envoyer des formulaires en asynchrone
    $('.ui-reload').on('click', function() {	
    	if (typeof($(this).data('sreloadtarget')) !== 'undefined') {
    		core.reload($($(this).data('sreloadtarget')));		
    	}
    });        
    
    // load on scroll
    $('.ui-loadscroll').scroll(function(){
        if ($(this).scrollTop() === ($(this).prop('scrollHeight') - $(this).outerHeight())){
        	if ($(this).find('.ui-scroll-loadable')) {
        		$('.ui-scroll-loadable').each(function() {
        			ui.sendNotification('Information', 'loading', 'info', 'glyphicon glyphicon-cog');
        			core.loadScroll($(this));        		        			
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