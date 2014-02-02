{% extends 'layout.tpl' %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block js %}

{% endblock %}

{% block css %}

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