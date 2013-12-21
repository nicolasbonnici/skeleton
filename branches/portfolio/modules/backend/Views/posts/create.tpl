{% extends 'layout.tpl' %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}
<link href="/lib/plugins/bootstrap-wysiwyg/css/bootstrap-wysiwyg.css" rel="stylesheet">
<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
{% endblock %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/hotkeys/js/jquery.hotkeys.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-wysiwyg/js/bootstrap-wysiwyg.js"></script>

<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.js"></script>
{% endblock %}

{% block main %}
	<div class="span12">
		<form>
			<fieldset>
				 <legend>Legend</legend> 
				 
				 <label>{{tr['title']}}</label>
				 <input type="text" placeholder="" /> 
				 <span class="help-block">Example block-level help text here.</span> 
				 
				<div class="ui-editor" data-name="content" id="ui-editor-create-post">

				</div>                                
		          			 
				 
				 <label class="checkbox"><input type="checkbox" /> Check me out</label> 
				 <button type="submit" class="btn">Submit</button>
			</fieldset>
		</form>
	</div>
{% endblock %}