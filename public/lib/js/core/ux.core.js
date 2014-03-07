(function($) {
        $.fn.userExperience = function(params) {

                var ux = {

                    initAppLayout: function() {
                        //*************************************************** @layout *************************************************

                        // @see init layout application level (aka BO)
                        if ($('body').hasClass('layout') && !$('body').data('ui-layout-loaded')) {

                            $('body').data('ui-layout-loaded', true);
                            
                            this.appLayout = $('body').layout({
                                useStateCookie: true,
                                cookie: {
                                    name:   "sociable.ux.layout", // optional
                                    expires: 90 // days
                                },
                                defaults: {
                                    applyDefaultStyles: false,
                                    fxName: 'drop',
                                    fxSpeed: 300,
                                    spacing_closed: 50,
                                    spacing_open: 10,
                                    resizerTip: 'Ouvrir/fermer',
                                    sliderTip: 'Redimmensionner',
                                    fxSettings_open: { easing: 'easeOutBounce' },
                                    fxSettings_close: { easing: 'easeOutBounce' },
                                    enableCursorHotkey: false
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
                                    initClosed: false
                                },
                                south: {
                                    applyDefaultStyles: false,                                
                                    spacing_closed: 0,
                                    spacing_open: 0,
                                    size: 90,
                                    togglerLength_closed: "100%",                                    
                                    togglerLength_open: 50,
                                    initClosed:    true,
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
                                        $('.ui-layout-east').toggleClass('ui-shadow');
                                    },
                                    onclose_end: function() {
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
                                        $('.ui-layout-west').toggleClass('ui-shadow');
                                    },
                                    onclose_end: function() {
                                    }                                    
                                }                                    
                            });
                            // toggle panes
                            if ( $('.ui-pane-toggle').size() != 0 ) {
                                $('.ui-pane-toggle').on('click', function() {
                                    
                                    if (typeof($(this).attr('data-pane')) !== 'undefined') {
                                        ux.layout.toggle($(this).attr('data-pane'));
                                    }
                                    
                                    return false;
                                });                           
                            }
                            // open panes
                            if ( $('.ui-pane-open').size() != 0 ) {
                                $('.ui-pane-open').on('click', function() {
                                    
                                    if (typeof($(this).attr('data-pane')) !== 'undefined') {
                                        ux.layout.open($(this).attr('data-pane'));
                                    }
                                    
                                    return false;
                                });                           
                            }
                            // close panes
                            if ( $('.ui-pane-close').size() != 0 ) {
                                $('.ui-pane-close').on('click', function() {
                                    
                                    if (typeof($(this).attr('data-pane')) !== 'undefined') {
                                        ux.layout.close($(this).attr('data-pane'));
                                    }
                                    
                                    return false;
                                });                           
                            }    
                            // show panes
                            if ( $('.ui-pane-show').size() != 0 ) {
                                $('.ui-pane-show').on('click', function() {
                                    
                                    if (typeof($(this).attr('data-pane')) !== 'undefined') {
                                        ux.layout.show($(this).attr('data-pane'), false); 
                                    }
                                    
                                    return false;
                                });                           
                            }
                            // Pin button
                            if ( $('.ui-pane-pin').size() != 0 ) {    
                                this.appLayout.addPinBtn('.ui-pane-pin', $('.ui-pane-pin').attr('data-pane'));
                            }

                            // Sauvegrder en cookie l etat du layout
                            $(window).unload(function() { 
                                    // Sauvegarder l'organisation du layout
                                this.appLayout.save('layout');  
                            }); 
                                                        

                        }   
                        
                },
                
                /**
                 * Send a notification
                 * 
                 * @param string sText        Notification content
                 * @param string sType        info|warning|error|success
                 * @param string sIcon        Icon class
                 * @param bCustom
                 */
                sendNotification: function(sText, sType, sIcon, bCustom) {
                    var sClass = '';
                    if (bCustom === true) {
                        sClass = 'ui-notification';
                    }
                    $.pnotify({
                        text : sText,
                        type : sType,
                        icon : sIcon,
                        opacity: 1,
                        nonblock: false,
                        nonblock_opacity: .2,
                        history: false,
                        addclass: "stack-bottomleft    " + ((sClass.length > 0) ? (' ' + sClass) : ''),
                        stack: {"dir1": "down", "dir2": "left", "push": "up"}
                    });                    
                },     
                
                hideNotifications: function() {
                    $.pnotify_remove_all();
                },
                
                /**
                 * Format timestamp to date dynamicaly
                 * @todo i18n support
                 */
                formatTimestamps: function() {
                    $('.ui-timestamp').each(function() {   
                        if (!$(this).data('formatTimestampFired')) {
                            
                            var iTimestamp = parseInt($(this).attr('data-timestamp'));
                            var oDate = new Date(iTimestamp*1000);
                            var sFormatedDateTime = '<span class="glyphicon glyphicon-calendar"></span> ' + oDate.toLocaleDateString() + ' <span class="glyphicon glyphicon-time"></span> ' + oDate.toLocaleTimeString();
                            $(this).empty().append(sFormatedDateTime).data('iTimestamp', iTimestamp);
                            
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
                        $.fn.editable.defaults.mode = 'popup';
                        $.fn.editable.inputs = '<form class="form-inline editableform margin">' +
                                                    '<div class="control-group">' +
                                                        '<div class="editable-input input-lg form-control"></div>' +
                                                        '<div class="editable-buttons"></div></div>'+
                                                        '<div class="editable-error-block"></div>' +
                                                   '</div>'+
                                               '</form>';
                        $.fn.editableform.buttons = '<button type="submit" class="btn btn-lg btn-primary editable-submit">Ok</button><button type="button" class="btn btn-lg btn-default editable-cancel">Annuler</button>';
                        
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
                                    title: 'Entrez la nouvelle valeur',
                                    placement: 'bottom',
                                    placeholder: $(this).data('placeholder'),
                                    inputclass: 'form-control input-lg',
                                    success: function(rep) {
                                        switch(rep.status) {
                                            case 1:
                                                ux.sendNotification(rep.content, 'success', 'glyphicon glyphicon-ok', false);
                                                break;
                                            case 2:
                                                ux.sendNotification(rep.content, 'error', 'glyphicon glyphicon-exclamation-sign', false);
                                                break;
                                            case 3:
                                                ux.sendNotification(rep.content, 'warning', 'glyphicon glyphicon-time', false);
                                                break;
                                            default:
                                                ux.sendNotification(rep.content, 'info', 'glyphicon glyphicon-info', true);
                                            break;                                                
                                        }
                                        ux.loadView();
                                    },
                                    error: function() {
                                        ux.sendNotification('Unable to reach server...', 'error', 'glyphicon glyphicon-exclamation-sign', false);
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
                
                /**
                 * Preload a dom node
                 * @param string sSelector
                 */
                preload: function(sSelector) {
                    if ($.fn.queryLoader2) {
                        if ($(sSelector).size() > 0) {
                            sSelector = 'body';
                        }
                        $(sSelector).queryLoader2({
                            barColor: "#000",
                            backgroundColor: "#fff",
                            percentage: true,
                            barHeight: 50,
                            completeAnimation: "grow",
                            deepSearch: true
                        });                              
                    }          
                },

                closeModal: function() {
                    $('.modal.in').modal('hide');
                },
                
                /**
                 * @todo
                 * @param repWrap
                 * @param request
                 */
                load: function(repWrap, request) {
                    $(repWrap).fadeOut(300).empty().load('/'+request['controller']+'/'+request['action']).fadeIn(150);
                    this.hideNotif();        
                },

                /**
                 * Load data as the user sroll down
                 * @param $obj            scrolled div dom node jQuery object
                 */
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

                /**
                 * A simple XHR request
                 * @param oHandler            scrolled div dom node jQuery object
                 */
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
                    
                    var oParams = oHandler.data();
                    
                    $.ajax({
                        type: 'POST',
                        data: oParams,
                        url: sUrl,
                        beforeSend : function(preload) {
                            // Mettre en cache et vider l'objet qui contiendra la reponse
                            $domTarget.data('initialContent', $domTarget.html());                                    
                            $domTarget.empty();    
                            ux.preload($domTarget.attr('id'));

                        },
                        success: function(rep){
                            if (!oHandler.hasClass('sendNotificationOnCallback')) {
                                $domTarget.append(rep.content);
                            } else {
                                switch(rep.status) {
                                    case 1:
                                        ux.sendNotification(rep.content, 'success', 'glyphicon glyphicon-check');
                                        break;
                                    case 2:
                                        ux.sendNotification(rep.content, 'error', 'glyphicon glyphicon-warning-sign');
                                        break;
                                    case 3:
                                        ux.sendNotification(rep.content, 'error', 'glyphicon glyphicon-warning-sign');
                                        break;
                                    case 4:
                                        ux.sendNotification(rep.content, 'info', 'glyphicon glyphicon-warning-sign');
                                        break;
                                        
                                }
                            }
                            if (oHandler.hasClass('refreshOnCallback')) {
                                ux.loadView();
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

                    // Serialiser le formulaire, ses attributs data et les contenteditable qu'il contient
                    var aFormInputs = $formTarget.serializeArray();
                    oParams = $.extend($formTarget.data(), obj.data());
                    if($(sFormSelector+' div[contenteditable=true]').size() != 0) {
                        $(sFormSelector+' .ui-editor').each(function() {
                            aFormInputs.push({'name' : $(this).attr('data-name'), 'value' : $(this).parent().find('div[contenteditable=true]:first').html()});
                        });
                    }    
 
                    oParams.parameters = JSON.stringify(aFormInputs);

                    $.ajax({
                        type: 'POST',
                        url: $formTarget.attr('action'),
                        dataType: 'json',
                        data: oParams,
                        beforeSend : function(preload) {
                            // Mettre en cache et vider l'objet qui contiendra la reponse
                            $domTarget.data('initialContent', $domTarget.html());    

                        },
                        success: function(rep){
                            if (!obj.hasClass('sendNotificationOnCallback')) {
                                $domTarget.append(rep.content);
                            } else {
                                switch(rep.status) {
                                    case 1:
                                        ux.sendNotification(rep.content, 'success', 'glyphicon glyphicon-check');
                                        break;
                                    case 2:
                                        ux.sendNotification(rep.content, 'error', 'glyphicon glyphicon-warning-sign');
                                        break;
                                    case 3:
                                        ux.sendNotification(rep.content, 'error', 'glyphicon glyphicon-warning-sign');
                                        break;
                                    case 4:
                                        ux.sendNotification(rep.content, 'info', 'glyphicon glyphicon-warning-sign');
                                        break;
                                        
                                }
                            }
                        },
                        error: function(err){                            
                            // Restore cached content
                            $domTarget.append($domTarget.data('initialContent'));                            
                        },
                        complete: function(){
                            $domTarget.removeData('initialContent');
                            
                            if (obj.hasClass('refreshOnCallback')) {
                                ux.loadView();
                            }
                            
                            if (obj.hasClass('closeModalOnCallback')) {
                                ux.closeModal();
                            }
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
                                ux.sendNotification('No url specified to load ui-loadable div #' + $(this).attr('id'), 'error', 'glyphicon glyphicon-warning', false);
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
                                    $(sSelector).append(rep.content);                                              
                                    $('#activityDebug').append(rep.debug);   // @todo selecteur en config                             
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
                        ux.sendNotification('No url specified to load ui-loadable div #' + oItem.attr('id'), 'error', 'glyphicon glyphicon-warning', false);
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
                            ux.sendNotification('Chargement en cours...', 'info', 'glyphicon glyphicon-info-sign');
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
                            ux.hideNotifications();
                        }
                    });                       
                },
                
                /**
                 * Vue en grid
                 * @todo Faire un plugin de ca
                 * @todo Ajouter un type de colonne hybride qui cale tout en largeur
                 */
                initGrids: function() {
                    var iColumnsIndex = 0;
                    var iColumnsCount = 4;
                    var iTwitterBootstrapGridClass = 3;
                    $('.ui-grid').each(function() {
                        var sGridSelector = '#' + $(this).attr('id');
                        if (!$(this).data('grid-loaded')) {
                            if (parseInt($(this).data('columns')) > 0) {
                                iColumnsCount  = parseInt($(this).data('columns'));
                                iTwitterBootstrapGridClass = 12 % iColumnsCount;
                            }
                            
                            var sColumnTemplate = '<div class="column ui-grid-column col-md-' + iTwitterBootstrapGridClass +'"></div>';
                            for (var i = 0; i < iColumnsCount; i++) {
                                $(this).append(sColumnTemplate);
                            }
                            
                            $(this).data('curCol',0);
                            $(this).data('grid-loaded', true);
                        }
                        $(sGridSelector + ' .item').each(function() {
                            
                            if ((iColumnsIndex + 1)  > iColumnsCount) {
                                iColumnsIndex = 0;
                            }
                            $(sGridSelector + ' .ui-grid-column').eq(iColumnsIndex).append($(this));
                            iColumnsIndex++;
                            $(this).data('curCol', iColumnsIndex);                            
                        });
                    });
                },
                

//                initGrids: function() {
//                    $('.ui-grid').each(function() {
////                        if (!$(this).data('grid-loaded')) {
//                            var iColumnsCount = 4;
//                            var iTwitterBootstrapGridClass = 3;
//                            if (parseInt($(this).data('columns')) > 0) {
//                                iColumnsCount  = parseInt($(this).data('columns'));
//                                iTwitterBootstrapGridClass = 12 % iColumnsCount;
//                            }
//                            
//                            var sColumnTemplate = '<div class="column ui-grid-column col-md-' + iTwitterBootstrapGridClass +'"></div>';
//                            for (var i = 0; i < iColumnsCount; i++) {
//                                $(this).append(sColumnTemplate);
//                            }
// 
//                            $(this).data('curCol', 0);
//                            $('#' + $(this).attr('id') + ' .item').each(function(index) {
//                                $(this).find('.ui-grid-column').eq($(this).data('curCol')).append($(this));
//                                console.log($(this));
//                                if ((($(this).data('curCol') + 1) > iColumnsCount)) {
//                                    index = 0;
//                                } else {
//                                    index++;
//                                }
//                                $(this).data('curCol', index);
//                                console.log(parseInt($(this).data('curCol')), parseInt(index));
//                            });
//                            
////                            $(this).data('grid-loaded', true);
////                        }
//                    });
//                },             
                
                /**
                 * Init forms element
                 */
                initForms: function() {
                    $('form').each(function() {
                        if (!$(this).data('HasChangesListnenerFired')) {
                            $(this).data('bHasChange', false);
                            $('body').on($(this).attr('id'), 'change', function() {
                               $(this).data('bHasChange', true); 
                            });
                            $(this).data('HasChangesListnenerFired', true);
                        } 
                     });
                    
                    // Init bootstrap editable elements
                    this.initEditableElements();
                    
                    // Init editors
                    this.initEditors();
                    
                    // Init toggle checkbox
                    this.initCheckbox();
                    
                    // Init Datepicker
                    this.initDatepicker();
                },
                
                /**
                 * Toggle button
                 */
                initCheckbox: function() {
                    $('.ui-checkbox').each(function() {
                        if (!$(this).data('ui-checkbox-fired')) {
                            $(this).data('ui-checkbox-fired', true);
                            $(this).bootstrapSwitch();
                        }
                    });
                },
                
                /**
                 * Init date picker
                 */
                initDatepicker: function() {
                    $('.ui-datetimepicker').each(function() {
                        if (!$(this).data('uiDateTimePickerFired')) {
                            $('.ui-datetimepicker').datetimepicker();
                            $(this).data('uiDateTimePickerFired', true);
                        }
                    });
                },
                
                /**
                 * Tooltip helper on title attribute
                 */
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
                
                /**
                 * Fire all ux components
                 */
                initUi: function() {

                    // Aide lors d'un focus sur input placehorder
                    $('[placeholder]').on('focus', function() {
                        ux.sendNotification($(this).attr('placeholder'), 'info', 'glyphicon glyphicon-info-sign');
                    });                                      
                    $('[placeholder]').on('blur', function() {
                        ux.hideNotifications();
                    });                                      
                    
                    // Fire app layout
                    this.initAppLayout();
                    
                    // Init grids layout
                    this.initGrids();
                    
                    // Init forms
                    this.initForms();
                    
                    // init timestamps to date
                    this.formatTimestamps();

                    // Init tooltip
                    this.initTooltip();

                    
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
            return ux;
        };
})(jQuery);
