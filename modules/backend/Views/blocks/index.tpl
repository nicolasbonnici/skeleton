{% extends 'layout.tpl' %}

{% block meta_title %}Blocks{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/hotkeys/js/jquery.hotkeys.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-wysiwyg/js/bootstrap-wysiwyg.js"></script>

<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/bootstrap-wysiwyg/css/bootstrap-wysiwyg.css" rel="stylesheet">

<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
{% endblock %}

{% block main %}
	<div class="row-fluid">
		<div class="span12">
			<div class="page-header">
				<h1>
					Blocks <small>1.0</small>
				</h1>
			</div>
		</div>
	</div>
    
	<div class="row-fluid">
		<div class="span12 text-right">
			<div class="btn-group">
				 <a href="#new-feed-modal" role="button" class="btn" data-toggle="modal"><em class="icon-file"></em>&nbsp;{{tr['new']}}</a>
			</div>		
			
			<div id="new-feed-modal" class="modal ui-modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">
						Modal header
					</h3>
				</div>
				<div class="modal-body">
					<p>
						One fine body…
					</p>
				</div>
				<div class="modal-footer">
					 <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button> <button class="btn btn-primary">Save changes</button>
				</div>
			</div>				
								
		</div>
	</div> 

	<div class="row-fluid">	    
		{% for oBlock in oBlocks %}
		<div class="span3 well">
			<h3>
				{{oBlock.title}}
			</h3>
			<div>
				{{oBlock.content|safe}}
			</div>
			<span>{{oBlock.created}}</span>
		</div>	
		{% endfor %}	
	</div>		

{% endblock %}