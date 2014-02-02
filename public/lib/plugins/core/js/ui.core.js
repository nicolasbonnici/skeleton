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
                                            initClosed:	false,
                                            onopen_end: function() {
                                                return false;
                                            },
                                            onclose_end: function() {
                                                return false;
                                            }                                    
                                    },
                                    east: {
                                            size:  '50%',
                                            applyDefaultStyles: false,
                                            initClosed: true,
                                            togglerLength_closed: 0,
                                            togglerLength_open: 0,
                                            spacing_open: 0,
                                            spacing_closed: 0,
                                            onopen_end: function() {
                                                return false;
                                            },
                                            onclose_end: function() {
                                                return false;
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
                                            	$('.ui-layout-west').addClass('ui-transition ui-shadow');
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
                 */
                initEditableElements: function() {
                    if ($('.ui-editable').size() > 0 && typeof($.fn.editable) !== 'undefined') {
                    	
                    	// @see setup editable plugin
                    	$.fn.editable.defaults.mode = 'inline';
                    	
                    	$('.ui-editable').each(function() {
                    		if (!$(this).data('ui-editable-fired')) {
                    			$(this).data('ui-editable-fired', true);                            	
                    			$(this).editable({
                    				params: function(aParams) {
                    					aParams.entity = $(this).data('entity');
                    			        return aParams;
                    			    },
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
                    				}
                    			});
                    		}
                    		
                    	});
                    }
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
                    
                    // init tooltip
                    $('[title]').tooltip({container:'body'});
                    
                    // Init bootstrap editable elements
                    this.initEditableElements();
                                                           
                    // Move all modals directly on the body (bugfix pour le layout)
                    $('.ui-modal').appendTo("body");  
                    
                    // init carousel
                    $('.ui-carousel').carousel({
                    	  interval: 2000,
                    	  duration: 50
                    });

                }
            }

            //~ // Permettre le chainage par jQuery
            return ui;
        };
})(jQuery);

