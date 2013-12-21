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
                                            size:  100,
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
                	var sClass;
                	if (bCustom === true) {
                		sClass = 'ui-notification';
                	}
                	
                	$.pnotify({
                	    title: sTitle,
                	    text : sText,
                	    type : sType,
                	    icon : sIcon,
                		addclass: sClass + '',
                		opacity: 1,
                		nonblock: true,
                		nonblock_opacity: .2
                	});                	
                },     
                
                hideNotifications: function() {
                	$.pnotify_remove_all();
                },
                
                formatTimestamps: function() {
                	$('.timestamp2Date').each(function() {                		
                		var iTimestamp = $(this).attr('data-timestamp');
                		var sDate = new Date(iTimestamp*1000);                	
                		$(this).append(sDate);
                	});
                },                
                
                // @todo cleaner tout ce paquet de merde!
                initEditors: function() {
                    if ($('.ui-editor').size() > 0) {

                        $('.ui-editor').each(function() {
                            
                            if (!$(this).data('ui-editor-fired')) {

                                // flag item
                                $(this).data('ui-editor-fired', true);
                                var sItemId = $(this).attr('id');                                

            // @todo move en helper de vue ou .tpl
            var toolbar = '<div id="editor-toolbar-'+sItemId+'" class="btn-toolbar hidden" data-role="editor-toolbar" data-target="#'+sItemId+'">'+
                              '<div class="btn-group">'+
                                '<a class="btn btn-large dropdown-toggle" data-toggle="dropdown" title="Font"><i class="icon-font"></i><b class="caret"></b></a>'+
                                  '<ul class="dropdown-menu">'+
                                  '</ul>'+
                                '</div>'+
                              '<div class="btn-group">'+
                                '<a class="btn btn-large dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>'+
                                  '<ul class="dropdown-menu">'+
                                    '<li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>'+
                                    '<li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>'+
                                    '<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>'+
                                  '</ul>'+
                              '</div>'+
                              '<div class="btn-group">'+
                                '<a class="btn btn-large"  data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>'+
                                '<a class="btn btn-large"  data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>'+
                                '<a class="btn btn-large"  data-edit="strikethrough" title="Strikethrough"><del>Delete</del></a>'+
                                '<a class="btn btn-large"  data-edit="underline" title="Underline (Ctrl/Cmd+U)"><u>Underline</u></a>'+
                              '</div>'+
                              '<div class="btn-group">'+
                                '<a class="btn btn-large"  data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list"></i></a>'+
                                '<a class="btn btn-large"  data-edit="insertorderedlist" title="Number list"><i class="icon-list"></i></a>'+
                                '<a class="btn btn-large"  data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>'+
                                '<a class="btn btn-large"  data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>'+
                              '</div>'+
                              '<div class="btn-group">'+
                                '<a class="btn btn-large"  data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>'+
                                '<a class="btn btn-large"  data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>'+
                                '<a class="btn btn-large"  data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>'+
                                '<a class="btn btn-large"  data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>'+
                              '</div>'+
                              '<div class="btn-group">'+
                                          '<a class="btn btn-large dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>'+
                                            '<div class="dropdown-menu input-append">'+
                                                    '<input class="span2" placeholder="URL" type="text" data-edit="createLink"/>'+
                                                    '<button class="btn btn-large"  type="button">Add</button>'+
                                '</div>'+
                                '<a class="btn btn-large"  data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>'+

                              '</div>'+

                              '<div class="btn-group">'+
                                '<a class="btn btn-large"  title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>'+
                                '<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />'+
                              '</div>'+
                              '<div class="btn-group">'+
                                '<a class="btn btn-large"  data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>'+
                                '<a class="btn btn-large"  data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>'+
                              '</div>'+
                              '<input type="text" data-edit="inserttext" class="ui-voiceBtn" x-webkit-speech="">'+
                            '</div>';                         
                                
                                // load toolbars
                                $(this).parent().prepend(toolbar);
                                
                                var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
                                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                                'Times New Roman', 'Verdana'],
                                fontTarget = $('[title=Font]').siblings('.dropdown-menu');
                                $.each(fonts, function (idx, fontName) {
                                    fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
                                });
                                
                                $(this).parent('.dropdown-menu').css('z-index',9999);
                                $('.dropdown-menu input').click(function() {
                                    return false;
                                })
                                .change(function () {
                                    $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                                })
                                .keydown('esc', function () {
                                    this.value='';
                                    $(this).change();
                                });

                                $('[data-role=magic-overlay]').each(function () { 
                                    var overlay = $(this), target = $(overlay.data('target')); 
                                    overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
                                });
                                if ("onwebkitspeechchange"  in document.createElement("input")) {

                                } else {
                                    $('#voiceBtn').hide();
                                }

                                $('#'+sItemId).wysiwyg(); 
                                
                                // Bindings
                                $(this).on('click', function() {
                                	$('#editor-toolbar-'+sItemId).toggleClass('hidden');
                                });
                            }     
                            
                        });
                    }
                },                              
                
                /**
                 * @dependancy bootstrap-editable plugin
                 */
                initEditableElements: function() {
                    if ($('.ui-editable').size() > 0) {
                    	
                    	// @see setup editable plugin
                    	$.fn.editable.defaults.mode = 'inline';
                    	
                        $('.ui-editable').each(function() {
                            
                            if (!$(this).data('ui-editable-fired')) {
                            	$(this).data('ui-editable-fired', true);                            	
                            	$(this).editable({
                            		params: {entity: $(this).attr('data-entity')},
                            		success: function(rep) {
                            			switch(rep.status) {
	                            			case 1:
	                            				ui.sendNotification('Success', rep.content, 'success', 'glyphicon glyphicon-ok', false);
	                            				break;
	                            			case 2:
	                            				ui.sendNotification('Error', rep.content, 'error', 'glyphicon glyphicon-exclamation-sign', false);
	                            				break;
	                            			case 3:
	                            				ui.sendNotification('Success', rep.content, 'warning', 'glyphicon glyphicon-time', false);
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
                    var $container = $('#ui-grid');
                    if (!$container.data('grid-loaded')) {
                    	
                    	$container.data('grid-loaded', true);      
                    	// On ajoute 4 colonnes @todo dynamiser avec un param
	                    $container.append('<div class="ui-grid-column col-md-3"></div><div class="ui-grid-column col-md-3"></div><div class="ui-grid-column col-md-3"></div><div class="ui-grid-column col-md-3"></div>');
                    }
                    
                    $container.data('curCol',0);
                    $('#ui-grid .item').each(function(index) {
                    	$container.find('.ui-grid-column').eq($container.data('curCol')).append($(this));
                    	var iCurrentColumnIndex = (($container.data('curCol') + 1) > 3) ? 0 : ($container.data('curCol') + 1); // @see reset @ > 3           	
                    	$container.data('curCol', iCurrentColumnIndex);
                    	//console.log($container.data('curCol'), $('#ui-grid .item').length);
                    });
                    // @see bugfix for bootstrap with .row-fluid [class*=span]]:first-child left margin rule 
                    $container.find('.ui-grid-column').prepend('<div class="hidden col-md-12"></div>');                    
//	                    for(var i = 0, l = $('.item').size(); i < l; i += 4) {
//	                    	$('.item').slice(i, i+4).wrapAll('<div class="row-fluid"></div>');            	
//	                    }                       	
                 
                },
                
                initUi: function() {

                	// Aide lors d'un focus sur input placehorder
                	$('[placeholder]').on('focus', function() {
                		ui.sendNotification('Information', $(this).attr('placeholder'), 'info', 'glyphicon glyphicon-info-sign', true);
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

                }
            }

            //~ // Permettre le chainage par jQuery
            return ui;
        };
})(jQuery);

