{% extends 'layout.tpl' %}

{% block meta_title %}Lifestream{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="/lib/plugins/summernote/js/summernote.js"></script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote-bs3.css" rel="stylesheet">
{% endblock %}

{% block modal %}
<div class="modal fade" id="modal-lifestream" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-lifestream-content">
            <p>&nbsp;</p>
        </div>
    </div>              
</div>
{% endblock %}

{% block main %}
    <div class="row clearfix transparentBlackBg rounded well ui-transition ui-shadow">
        <div class="col-md-2 column">
            <img src="/lib/img/apps/blog/icon.png" alt="App icon" />
        </div>
        <div class="col-md-10 column">
            <h1 class="showOnHover">
                Lifestream app <small class="targetToShow">1.0</small>
            </h1><br />
            <ul class="nav nav-pills transparentBlackBg rounded">
              <li class="active">
                <a href="#" class="ui-sendxhr" data-url="/backend/blog/dashboard/" data-selector="#dashboard" role="button">
                    <span class="glyphicon glyphicon-home"></span> <strong>Dashboard</strong>
                </a>
              </li>
              <li><a href="#"><span class="glyphicon glyphicon-bookmark"></span> <strong>Activities</strong></a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <span class="glyphicon glyphicon-cog"></span> <strong>Settings</strong> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" type="button" class="ui-sendxhr" data-url="/backend/lifestream/createFeed/" data-selector="#modal-lifestream-content" role="button" data-toggle="modal">
                            <span class="glyphicon glyphicon-file"></span> Bla bla
                        </a>
                    </li>
                    <li>
                        <a href="#" type="button" class="ui-sendxhr" data-url="/backend/lifestream/posts/" data-selector="#dashboard" role="button">
                            <span class="glyphicon glyphicon-file"></span> Gérer
                        </a>
                    </li>
                </ul>
              </li>              
            </ul>
        </div>

       {% for oFeed in oFeeds %}
        <div class="col-md-12 column">
            <div class="panel">
              <div class="panel-body">
                <h1>
                    <span class="glyphicon glyphicon-globe"></span> <a href="#" class="ui-editable" data-url="/backend/feeds/update/id/{{oFeed.idfeed}}" data-type="text" data-pk="{{oFeed.idfeed}}" data-name="title" title="{{tr['update']}}">{{oFeed.title}}</a>
                </h1>
                <h3>
                    <img src="{{oFeed.icon}}" alt="Feed icon" />&nbsp;
                    <a href="#"  class="btn btn-link btn-lg ui-editable" data-url="/backend/feeds/update/id/{{oFeed.idfeed}}" data-type="text" data-pk="{{oFeed.idfeed}}" data-name="domain" title="{{tr['update']}}">
                        {{oFeed.domain}}
                    </a>&nbsp;<a href="#" class="btn btn-lg btn-primary" title="Raffraichir les informations de ce feed"><span class="glyphicon glyphicon-refresh"></span> Parser ce flux</a>
                </h3>
              </div>
              <div class="panel-footer">
                <p>
                    <span class="glyphicon glyphicon-link"></span> <a href="#" class="ui-editable" data-url="/backend/feeds/update/id/{{oFeed.idfeed}}" data-type="text" data-pk="{{oFeed.idfeed}}" data-name="url" title="{{tr['update']}}">{{oFeed.url}}</a>
                </p>
              </div>
            </div>
        </div>
       {% endfor %}
    </div>
{% endblock %}