/**
Bootstrap wysiwyg editor. Based on [bootstrap-wysiwyg](https://github.com/jhollingworth/bootstrap-wysiwyg).  
You should include **manually** distributives of `wysiwyg` and `bootstrap-wysiwyg`:

    <link href="js/inputs-ext/wysiwyg/bootstrap-wysiwyg-0.0.2/bootstrap-wysiwyg-0.0.2.css" rel="stylesheet" type="text/css"></link>  
    <script src="js/inputs-ext/wysiwyg/bootstrap-wysiwyg-0.0.2/wysiwyg-0.3.0.min.js"></script>  
    <script src="js/inputs-ext/wysiwyg/bootstrap-wysiwyg-0.0.2/bootstrap-wysiwyg-0.0.2.min.js"></script>
    
And also include `wysiwyg.js` from `inputs-ext` directory of x-editable:
      
    <script src="js/inputs-ext/wysiwyg/wysiwyg.js"></script>  

**Note:** It's better to use fresh bootstrap-wysiwyg from it's [master branch](https://github.com/jhollingworth/bootstrap-wysiwyg/tree/master/src) as there is update for correct image insertion.    
    
@class wysiwyg
@extends abstractinput
@final
@since 1.4.0
@example
<div id="comments" data-type="wysiwyg" data-pk="1"><h2>awesome</h2> comment!</div>
<script>
$(function(){
    $('#comments').editable({
        url: '/post',
        title: 'Enter comments'
    });
});
</script>
**/
(function ($) {
    "use strict";
    
    var wysiwyg = function (options) {
        this.init('wysiwyg', options, wysiwyg.defaults);
        
        //extend wysiwyg manually as $.extend not recursive 
        this.options.wysiwyg = $.extend({}, wysiwyg.defaults.wysiwyg, options.wysiwyg);
    };

    $.fn.editableutils.inherit(wysiwyg, $.fn.editabletypes.abstractinput);



    wysiwyg.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        /**
        @property tpl
        @default <textarea></textarea>
        **/
        tpl:'<div class="ui-editor"></div>',
        /**
        @property inputclass
        @default editable-wysiwyg
        **/
        inputclass: 'editable-wysiwyg ui-editor',
        /**
        Placeholder attribute of input. Shown when input is empty.

        @property placeholder
        @type string
        @default null
        **/
        placeholder: null
    });

    $.fn.editabletypes.wysiwyg = wysiwyg;

}(window.jQuery));
