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
			<div class="page-header">
				<h1 class="showOnHover">
					<img src="/lib/img/apps/post/icon.png" alt="App icon" />Blogging app <small class="targetToShow">1.0</small>
				</h1>
			</div>
		</div>

		<div class="col-md-12 column">
			<div class="btn-group btn-group-lg">
                 <button type="button" class="ui-reload btn btn-lg btn-default" title="Refresh!">
                     <span class="glyphicon glyphicon-refresh"></span> Raffraichir
                 </button>
				 <button  href="#modal-post" type="button" class="hide btn btn-lg btn-danger ui-sendxhr ui-delete-posts" data-url="/backend/post/delete/" data-selector="#modal-create-content" role="button" data-toggle="modal">
				    <span class="glyphicon glyphicon-trash"></span> Supprimer
				 </button> 
				 <button  href="#modal-post" type="button" class="btn btn-lg btn-info ui-sendxhr" data-url="/backend/post/create/" data-selector="#modal-post-content" role="button" data-toggle="modal">
				    <span class="glyphicon glyphicon-file"></span> New post!
				 </button> 
			</div>

			<table id="post-last-items" class="table table-responsive">
				<thead>
					<tr>
						<th>
							<input type="checkbox" class="ui-select-all ui-toggle" data-toggle-selector=".ui-delete-posts" data-checkbox-class="posts" data-container="#post-last-items" />
						</th>
						<th>
							<h3>Title</h3>
						</th>
						<th>
							<h3>Status</h3>
						</th>
						<th>
							<h3>Last edited</h3>
						</th>
                        <th class="text-center">
                            <h3>Actions</h3>
                        </th>
					</tr>
				</thead>
				<tbody id="postList" class="ui-loadable" data-entity="Post" data-view="post/list.tpl" data-parameters="" data-module="backend" data-controller="crud" data-action="listByUser">

				</tbody>
			</table>
		</div>
	</div>

{% endblock %}

