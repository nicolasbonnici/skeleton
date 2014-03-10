{% extends 'layout.tpl' %}

{% block favicon %}/lib/img/aps/post/icon.png{% endblock favicon %}
{% block meta_title %}Blogging app{% endblock meta_title %}
{% block meta_description %}A simple blogging application{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="/lib/plugins/summernote/js/summernote.js"></script>
<script type="text/javascript" src="/lib/plugins/moment/js/moment.min.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    // Show the delete btn for several checked checkbox
    $('body').on('.ui-select.posts', 'click', function() {
       alert($('.ui-select.posts:checked').size());
       if ($('.ui-select.posts:checked').size() > 1) {
           $('.ui-delete-posts').removeClass('hide');
       } else {
           $('.ui-delete-posts').addClass('hide');
       }
    });
});
</script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote-bs3.css" rel="stylesheet">
{% endblock %}

{% block modal %}
<div class="modal fade" id="modal-post" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-post-content">
            <p>&nbsp;</p>
		</div>
	</div>				
</div>
{% endblock %}

{% block main %}

	<div class="row clearfix transparentBlackBg rounded well ui-transition ui-shadow">
		<div class="col-md-12 column">
    		<h1 class="showOnHover">
    			<img src="/lib/img/apps/post/icon.png" alt="App icon" />Blogging app <small class="targetToShow">1.0</small>
    		</h1>
            <ul class="nav nav-pills">
              <li class="active"><a href="#" class="ui-sendxhr" data-url="/backend/post/dashboard/" data-selector="#dashboard" role="button">Dashboard</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  Posts <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#modal-post" type="button" class="ui-sendxhr" data-url="/backend/post/createPost/" data-selector="#modal-post-content" role="button" data-toggle="modal">
                            <span class="glyphicon glyphicon-file"></span> Nouveau
                        </a>
                    </li>
                    <li>
                        <a href="#modal-post" type="button" class="ui-sendxhr" data-url="/backend/post/posts/" data-selector="#dashboard" role="button">
                            <span class="glyphicon glyphicon-file"></span> Gérer
                        </a>
                    </li>
                </ul>
              </li>              
              <li><a href="#">Comments</a></li>
              <li><a href="#">Settings</a></li>
            </ul>
		</div>
        
		<div class="col-md-12 column" id="dashboard">
        </div>
	</div>

{% endblock %}

