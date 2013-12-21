{% extends 'layout.tpl' %}

{% block meta_title %}Test{% endblock meta_title %}
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
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>
					Example page header <small>Subtext for header</small>
				</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="ui-grid" class="ui-loadable ui-loadscroll ui-grid" data-module="frontend" data-controller="home" data-action="list">

		</div>    				
	</div>
{% endblock %}