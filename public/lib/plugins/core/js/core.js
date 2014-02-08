
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


                // @todo
                sendXHR: function(obj) {

                    var sUrl = obj.attr('data-url');
                    var $domTarget = $(obj.attr('data-selector'));				

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
                			
                			if (!$(this).data('ui-loaded')) {
                				
                				$(this).data('ui-loaded', true);
                				
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
                			}
                			
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
    $('.ui-sendxhr').on('click', function(){
        core.sendXHR($(this));
        return false;	
    });
    $('.sendXHROnChange').on('change',  function(){
        core.sendXHR($(this));
        return false;	
    });
    
    // Envoyer des formulaires en asynchrone
    $('.ui-sendform').on('click', function() {		
    	core.sendForm($(this));		
    	return false;	
    });        
	
    // Envoyer des formulaires en asynchrone
    $('.ui-reload').on('click', function() {	
    	if (typeof($(this).data('sreloadtarget')) !== 'undefined') {
    		core.reload($($(this).data('sreloadtarget')));		
    	}
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
    
    // Tooltip
    $('body').on('mouseenter', '[title]', function() {
    	$('#ui-tip').append('<p><span class="glyphicon glyphicon-info-sign"></span> ' + $(this).attr('title') + '</p>').show();
    	$(this).data('sTooltip', $(this).attr('title')).attr('title', '');
    });
    $('body').on('mouseleave', '[title]', function() {
    	$('#ui-tip').empty().hide();
    	$(this).attr('title', $(this).data('sTooltip'));
    });
    $(document).mousemove(function(event) {
    	console.log($('#ui-tip').offset().top);
    	if (!$('#ui-tip').hasClass('ui-tip-top') && event.pageY >= $('#ui-tip').offset().top) {
    		$('#ui-tip').addClass('ui-tip-top');
    	} else {
    		$('#ui-tip').removeClass('ui-tip-top');
    	}
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