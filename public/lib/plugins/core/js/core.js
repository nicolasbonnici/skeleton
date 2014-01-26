
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
                    core.showNotif('Chargement en cours...', 'info');			
                    $(repWrap).fadeOut(300).empty().load('/'+request['controller']+'/'+request['action']).fadeIn(150);
                    core.hideNotif();		
                },


                // @todo
                sendXHR: function(obj) {

                    var sUrl = obj.attr('data-url');
                    var $domTarget = $(obj.attr('data-selector'));			

                    // Mettre en cache et vider l'objet qui contiendra la reponse
                    $domTarget.data('initialContent', $domTarget.html());			

                    $.ajax({
                        type: 'POST',
                        url: sUrl,
                        beforeSend : function(preload) {
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

                        }
                    });
                },

                loadScroll: function($obj) {

            		var sSelector = '#'+$obj.attr('id');                			
            		var iStep = $(sSelector + ' .item').length;           		         		            		

                    $.ajax({
                        type: 'POST',
                        url: '/'+$obj.attr('data-module')+'/'+$obj.attr('data-controller')+'/'+$obj.attr('data-action'),
                        data: 'iStep=' + iStep,
                        beforeSend : function(preload) {
                        	
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
                    		$('#activityDebug').append(rep.debug);   // @todo selecteur en config                     		                        	
                        },
                        complete: function(){
                        	$.pnotify_remove_all();
                        }
                    });    

                },
                
                // Envoyer un formulaire en asynchrone
                sendForm: function(obj) {

                    var sFormSelector = obj.attr('data-form');
                    var $formTarget = $(sFormSelector);
                    var $domTarget = $(sFormSelector).parent();

                    // Serialiser les forms inputs
                    var data = $formTarget.serialize();
                    if($(sFormSelector+' .ui-editor').size() != 0) {
                        $(sFormSelector+' .ui-editor').each(function() {
                            data += '&'+$(this).attr('data-name')+'='+$(this).html();
                        });
                    }					

                    // Mettre en cache et vider l'objet qui contiendra la reponse
                    $domTarget.data('initialContent', $domTarget.html());			

                    $.ajax({
                        type: 'POST',
                        url: $formTarget.attr('action'),
                        data: data,
                        beforeSend : function(preload) {
                        	$domTarget.empty();                                                            
                        },
                        success: function(rep){
                        	if (rep.status == 1) { // @see if XHR_STATUS_OK
                            	$domTarget.append(rep.content);                        		
                        	}
                        },
                        error: function(err){                            
                            // Restore cached content
                        	$domTarget.append($domTarget.data('initialContent'));                            
                        },
                        complete: function(){

                        }
                    });
                },
                
                // Load some part of the view asynch to boost loadtime and simplify view refresh process
                loadView: function() {
                	if ($('.ui-loadable').size() > 0) {
                		
                		$('.ui-loadable').each(function() {
                			
                			if (!$(this).data('ui-loaded')) {
                				
                				$(this).data('ui-loaded', true);
                				
                                // Mettre en cache et vider l'objet qui contiendra la reponse
                                $(this).data('initialContent', $(this).html());	                				
                				
                                var sSelector = '#'+$(this).attr('id');
                                
                                $.ajax({
                                    type: 'POST',
                                    url: '/'+$(this).attr('data-module')+'/'+$(this).attr('data-controller')+'/'+$(this).attr('data-action'),
                                    beforeSend : function(preload) {
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

                                    }
                                });             				
                			}
                			
                		});
                		
                	}
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
        return false;	
    });
    $('body').on('change', '.sendXHROnChange',  function(){
        core.sendXHR($(this));
        return false;	
    });
	
    // Envoyer des formulaires en asynchrone
    $('body').on('click', '.ui-sendform', function() {		
        core.sendForm($(this));		
        return false;	
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

	$('.ui-login-popover').popover({
		container: 'body', 
        placement : 'auto', 
        title : '<h4>Login</h4>',
        html: 'true', 
        animation: true,
        
        content : $('#login-popover').html()
	});
    
});