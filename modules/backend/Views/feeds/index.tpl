{% extends 'layout.tpl' %}

{% block meta_title %}Feeds{% endblock meta_title %}
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
		<div class="col-md-12 column">
			<div class="page-header">
				<h1>
					Feeds <small>1.0</small>
				</h1>
			</div>
		</div>
	</div>
    
    <div class="row">
	       {% for oFeed in oFeeds %}
    		<div class="col-md-4 column well transparentBlackBg">
				<h3>
					<img src="{{oFeed.icon}}" alt="Feed icon" />
					&nbsp;<a href="#"  class="ui-editable" data-url="/backend/feeds/update/id/{{oFeed.idfeed}}" data-type="text" data-pk="{{oFeed.idfeed}}" data-name="domain" title="{{tr['update']}}">{{oFeed.domain}}</a>
				</h3>
				<p>
					<a href="#" class="ui-editable" data-url="/backend/feeds/update/id/{{oFeed.idfeed}}" data-type="text" data-pk="{{oFeed.idfeed}}" data-name="title" title="{{tr['update']}}">{{oFeed.title}}</a>
					&nbsp;<a href="#" class="btn btn-primary" title="Raffraichir les informations de ce feed"><span class="glyphicon glyphicon-refresh"></span> {{tr['refresh']}}</a>				
				</p>
				<p>
				    <a href="#" class="ui-editable" data-url="/backend/feeds/update/id/{{oFeed.idfeed}}" data-type="text" data-pk="{{oFeed.idfeed}}" data-name="url" title="{{tr['update']}}">{{oFeed.url}}</a>
				</p>
			</div>
	       {% endfor %}
    </div>
    
	<div class="row">
         <div id="latestActivities" class="ui-loadable col-md-12 column ui-grid" data-module="frontend" data-controller="home" data-action="list">

         </div> 
	</div> 
{% endblock %}