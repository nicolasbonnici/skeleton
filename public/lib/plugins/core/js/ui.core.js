(function($) {
        $.fn.userInterface = function(params) {

                var ui = {

                    initLayout: function() {
                        //*************************************************** @layout *************************************************

                        // @see init layout application level (aka BO)
                        if ($('body').hasClass('layout') && !$('body').data('ui-layout-loaded')) {

                        	$('body').data('ui-layout-loaded', true);
                        	
                            this.layout = $('body').layout({
                                    defaults: {
                                            applyDefaultStyles: false,
                                            fxName: 'drop',
                                            fxSpeed: 300,
                                            spacing_closed: 50,
                                            spacing_open: 10,
                                            resizerTip: 'Ouvrir/fermer',
                                            sliderTip: 'Redimmensionner',
                                            fxSettings_open: { easing: 'easeOutBounce' },
                                            fxSettings_close: { easing: 'easeOutBounce' }
                                    },
                                    center: {
                                            applyDefaultStyles: false,
                                            spacing_closed: 0,
                                            spacing_open: 0,
                                            size: '100%'
                                    },
                                    north: {
                                            applyDefaultStyles: true,
                                            showOverflowOnHover: true,
                                            spacing_closed: 0,
                                            spacing_open: 0,
                                            togglerLength_open: 0,
                                            size: 52,
                    						initClosed: false,
                                            togglerTip_open: 'Consulter la liste des applications disponibles',
                                            togglerTip_close: 'Fermer la liste des applications disponibles'
                                    },
                                    south: {
                                            applyDefaultStyles: false,                                
                                            spacing_closed: 0,
                                            spacing_open: 0,
                                            size: 90,
                                            togglerLength_closed: "100%",                                    
                                            togglerLength_open: 50,
                                            initClosed:	true,
                                            onopen_end: function() {
                                                return false;
                                            },
                                            onclose_end: function() {
                                                return false;
                                            }                                    
                                    },
                                    east: {
                                            size:  150,
                                            applyDefaultStyles: false,
                                            initClosed: true,
                                            togglerLength_closed: 0,
                                            togglerLength_open: 0,
                                            spacing_open: 0,
                                            spacing_closed: 0,
                                            onopen_end: function() {
                                            	$('.ui-layout-east').addClass('ui-shadow');
                                            },
                                            onclose_end: function() {
                                            	$(this).removeClass('ui-shadow');                                            	
                                            }
                                    },
                                    west: {
                                            size: 320,
                                            applyDefaultStyles: false,
                                            spacing_open: 0,
                                            spacing_closed: 0,
                                            togglerLength_closed: 0,
                                            initClosed: true,
                                            slideTrigger_close: 'mouseout',
                                            slideTrigger_open: 'mouseover',                                             
                                            onopen_end: function() {
                                            	$('.ui-layout-west').addClass('ui-shadow');
                                            },
                                            onclose_end: function() {
                                            	$(this).removeClass('ui-shadow');                                            	
                                            }                                    
                                    }	                                
                            });
                            // toggle panes
                            if ( $('.ui-pane-toggle').size() != 0 ) {
                            	$('.ui-pane-toggle').on('click', function() {
                            		
                            		if (typeof($(this).attr('data-pane')) !== 'undefined') {
                            			ui.layout.toggle($(this).attr('data-pane'));
                            		}
                            		
                            		return false;
                            	});                           
                            }
                            // open panes
                            if ( $('.ui-pane-open').size() != 0 ) {
                            	$('.ui-pane-open').on('click', function() {
                            		
                            		if (typeof($(this).attr('data-pane')) !== 'undefined') {
                            			ui.layout.open($(this).attr('data-pane'));
                            		}
                            		
                            		return false;
                            	});                           
                            }
                            // close panes
                            if ( $('.ui-pane-close').size() != 0 ) {
                            	$('.ui-pane-close').on('click', function() {
                            		
                            		if (typeof($(this).attr('data-pane')) !== 'undefined') {
                            			ui.layout.close($(this).attr('data-pane'));
                            		}
                            		
                            		return false;
                            	});                           
                            }    
                            // show panes
                            if ( $('.ui-pane-show').size() != 0 ) {
                            	$('.ui-pane-show').on('click', function() {
                            		
                            		if (typeof($(this).attr('data-pane')) !== 'undefined') {
                            			ui.layout.show($(this).attr('data-pane'), false); //@see second parameter to just slide but not open
                            		}
                            		
                            		return false;
                            	});                           
                            }                                
                            // pin btn @todo decliner et factoriser les autres type de boutons
                            if ( $('.ui-pane-pin').size() != 0 ) {	
                                this.layout.addPinBtn('.ui-pane-pin', $('.ui-pane-pin').attr('data-pane'));
                            }

                            // Sauvegrder en cookie l etat du layout
                            $(window).unload(function() { 
                                    // Sauvegarder l'organisation du layout
                                    this.layoutState.save('layout'); // @see Bug sous certains navigateur contournÃ© ca 
                            }); 
                                                        

                        }   
                        
                },
                
                sendNotification: function(sTitle, sText, sType, sIcon, bCustom) {
                	var sClass = '';
                	if (bCustom === true) {
                		sClass = 'ui-notification';
                	}
                	$.pnotify({
                	    title: sTitle,
                	    text : sText,
                	    type : sType,
                	    icon : sIcon,
                		opacity: 1,
                		nonblock: false,
                		nonblock_opacity: .2,
                		history: false,
                		addclass: "stack-bottomleft	" + ((sClass.length > 0) ? (' ' + sClass) : ''),
                		stack: {"dir1": "down", "dir2": "left", "push": "up"}
                	});                	
                },     
                
                hideNotifications: function() {
                	$.pnotify_remove_all();
                },
                
                formatTimestamps: function() {
                	$('.ui-timestamp').each(function() {   
                		if (!$(this).data('formatTimestampFired')) {
                			
                			var iTimestamp = parseInt($(this).attr('data-timestamp'));
                			var oDate = new Date(iTimestamp*1000);                	
                			$(this).empty().append(oDate.toLocaleDateString() + ' ' + oDate.toLocaleTimeString() + ' <span class="glyphicon glyphicon-time"></span>');
                			
	                		$(this).data('formatTimestampFired', true);
                		}
                	});
                },                
                
                /**
                 * wysiwyg editor
                 * @todo test $.fn.summernote first
                 */ 
                initEditors: function() {
                	if ($('.ui-editor').size() > 0) {
                		$('.ui-editor').summernote({focus: true});                		
                	}
                },                              
                
                /**
                 * @dependancy bootstrap-editable plugin
                 * @todo debugger et passer proprement les params en fonctions des differents types d'instance du plugin
                 */
                initEditableElements: function() {
                    if ($('.ui-editable').size() > 0 && typeof($.fn.editable) !== 'undefined') {
                    	
                    	// @see setup editable plugin
                    	$.fn.editable.defaults.mode = 'inline';
                    	
                    	$('.ui-editable').each(function() {
                            var sUrl = '';
                            if (
                        		typeof($(this).data('module')) !== 'undefined' && 
                        		typeof($(this).data('controller')) !== 'undefined' && 
                        		typeof($(this).data('action')) !== 'undefined' 
                            ) {
                            	sUrl = '/'+$(this).data('module')+'/'+$(this).data('controller')+'/'+$(this).data('action');
                            } else if (typeof($(this).data('url')) !== 'undefined') {
                            	sUrl = $(this).data('url');
                            } else {
                            	sUrl = $(this).attr('href');
                            }
                            
                    		var oOpts = {
			                        ajaxOptions: {
			                            dataType: 'json'
			                        },
			                        params: function (params) {
			                            params.entity = $(this).data('entity');
			                            return params;
			                        },
                    				url: sUrl,
                    				title: 'Editer ce champs',
                    				placeholder: $(this).attr('placeholder'),
                    				success: function(rep) {
                    					switch(rep.status) {
	                    					case 1:
	                    						ui.sendNotification('Success', rep.content, 'success', 'glyphicon glyphicon-ok', false);
	                    						break;
	                    					case 2:
	                    						ui.sendNotification('Error', rep.content, 'error', 'glyphicon glyphicon-exclamation-sign', false);
	                    						break;
	                    					case 3:
	                    						ui.sendNotification('Warning', rep.content, 'warning', 'glyphicon glyphicon-time', false);
	                    						break;
	                    					default:
	                    						ui.sendNotification('Info', rep.content, 'info', 'glyphicon glyphicon-info', true);
	                    					break;	                            				
                    					}
                    					ui.loadView();
                    				},
                                    error: function() {
                						ui.sendNotification('Error', 'Unable to reach server...', 'error', 'glyphicon glyphicon-exclamation-sign', false);
                                    }
                    		};
                    		
                    		// @todo
							if ($(this).hasClass('ui-editable-date')) {
								var oOpts = {
									format: 'yyyy-mm-dd',    
							        viewformat: 'dd/mm/yyyy',    
							        datepicker: {
						               weekStart: 1
						            }
						        };
							}                    		
                    		
                			$(this).editable(oOpts);
                    		
                    	});
                    }
                },
                
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
                            data += '&'+$(this).data('name')+'='+$(this).parent().find('div[contenteditable=true]:first').html();
                        });
                    }					

                    $.ajax({
                        type: 'POST',
                        url: $formTarget.attr('action'),
                        data: data,
                        beforeSend : function(preload) {
                        	// Mettre en cache et vider l'objet qui contiendra la reponse
                        	if (!obj.hasClass('sendNotificationOnCallback')) {
                        		$domTarget.data('initialContent', $domTarget.html());	
                        		$domTarget.empty();
                        	}
                        },
                        success: function(rep){
                    		if (!obj.hasClass('sendNotificationOnCallback')) {
                    			$domTarget.append(rep.content);
                    		} else {
                    			switch(rep.status) {
                    				case 1:
                    					ui.sendNotification('Success!', rep.content, 'success', 'glyphicon glyphicon-check');
                    					break;
                    				case 2:
                    					ui.sendNotification('Error...', rep.content, 'error', 'glyphicon glyphicon-warning-sign');
                    					break;
                    				case 3:
                    					ui.sendNotification('Access denied!', rep.content, 'error', 'glyphicon glyphicon-warning-sign');
                    					break;
                    				case 4:
                    					ui.sendNotification('Session expired', rep.content, 'info', 'glyphicon glyphicon-warning-sign');
                    					break;
                    					
                    			}
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
                },
                
                /**
                 * Faire un plugin de ca
                 */
                initGrids: function() {
                	$('.ui-grid').each(function() {
                		$container = $(this);
                        if (!$container.data('grid-loaded')) {
                        	
                        	$container.data('grid-loaded', true);      
                        	// On ajoute 4 colonnes @todo dynamiser avec un param
    	                    $container.append('<div class="column ui-grid-column col-md-3"></div><div class="column ui-grid-column col-md-3"></div><div class="column ui-grid-column col-md-3"></div><div class="column ui-grid-column col-md-3"></div>');
                        }
                        
                        $container.data('curCol',0);
                        $('#' + $container.attr('id') + ' .item').each(function(index) {
                        	$container.find('.ui-grid-column').eq($container.data('curCol')).append($(this));
                        	var iCurrentColumnIndex = (($container.data('curCol') + 1) > 3) ? 0 : ($container.data('curCol') + 1); // @see reset @ > 3           	
                        	$container.data('curCol', iCurrentColumnIndex);
                        	//console.log($container.data('curCol'), $('#ui-grid .item').length);
                        });                		
                	});
                },
                
                initCheckbox: function() {
                	$('.ui-checkbox').each(function() {
                		if (!$(this).data('ui-checkbox-fired')) {
                			$(this).data('ui-checkbox-fired', true);
                			$(this).bootstrapSwitch();
                		}
                	});
                },
                
                initTooltip: function() {
                	if (! $('body').data('tooltip-fired')) {
                		$('body').data('tooltip-fired', true);
	                    // Tooltip
	                    if ($('#ui-tip').size() === 0) {
	                    	$('body').append('<div id="ui-tip" class="transparentBlackBg blackTextShadow GPUrender ui-shadow"></div>');
	                    }
	                    $('body').on('mouseenter', '[title]', function() {
	                    	$('#ui-tip').append('<p><span class="glyphicon glyphicon-info-sign"></span> ' + $(this).attr('title') + '</p>').show();
	                    	$(this).data('sTooltip', $(this).attr('title')).attr('title', '');
	                    });
	                    $('body').on('mouseleave', '[title]', function() {
	                    	$('#ui-tip').empty().hide();
	                    	$(this).attr('title', $(this).data('sTooltip'));
	                    });
	                    $(document).mousemove(function(event) {
	                    	if (!$('#ui-tip').hasClass('ui-tip-top') && event.pageY >= $('#ui-tip').offset().top) {
	                    		$('#ui-tip').addClass('ui-tip-top');
	                    	} else {
	                    		$('#ui-tip').removeClass('ui-tip-top');
	                    	}
	                	});
                	}
                },
                
                initUi: function() {

                	// Aide lors d'un focus sur input placehorder
                	$('[placeholder]').on('focus', function() {
                		ui.sendNotification('Information', $(this).attr('placeholder'), 'info', 'glyphicon glyphicon-info-sign');
                	});              	                	
                	$('[placeholder]').on('blur', function() {
                		ui.hideNotifications();
                	});              	                	
                	
                    // Fire app layout
                    this.initLayout();                    
                	
                    // Init grids layout with Masonry
                    this.initGrids();
                    
                    // Init editors
                    this.initEditors();
                    
                    // init timestamps to date
                    this.formatTimestamps();
                    
                    // Init bootstrap editable elements
                    this.initEditableElements();

                    // Init tooltip
                    this.initTooltip();
                    
                    // Move all modals directly on the body (bugfix pour le layout)
                    $('.ui-modal').appendTo("body");  
                    
                    this.initCheckbox();
                    
                    // init carousel
                    if ($('.ui-carousel').size() > 0) {
                    	$('.ui-carousel').carousel({
                    		interval: 2000,
                    		duration: 50
                    	});
                    }

                }
            }

            //~ // Permettre le chainage par jQuery
            return ui;
        };
})(jQuery);

