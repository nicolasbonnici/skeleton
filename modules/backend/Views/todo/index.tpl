{% extends 'layout.tpl' %}

{% block meta_title %}Todo!{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/summernote/js/summernote.js"></script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote-bs3.css" rel="stylesheet">
{% endblock %}

{% block modal %}
<div class="modal fade" id="modal-container-275600" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">
					New todo
				</h4>
			</div>
			<div class="modal-body">
				<form role="form" id="newTodoForm" action="" method="post">
					<div class="form-group">
						 <label for="todoTitle">Title </label><input type="text" class="form-control" placeholder="Input a title for your todo" id="todoTitle" />
					</div>
					<div class="form-group">
						<label for="exampleInputFile">File input</label><div class="ui-editor"></div>
						<p class="help-block">
							Vous pouvez mettre en form votre todo à l'aide de la barre d'outils de mise en forme
						</p>
					</div>
				</form>							
			</div>
			<div class="modal-footer">
				 <button type="button" class="btn btn-default" data-dismiss="modal">{{tr['cancel']}}</button>
				 &nbsp;<button type="button" class="btn btn-primary">{{tr['save']}}</button>
			</div>
		</div>
		
	</div>				
</div>
{% endblock %}

{% block main %}
	<div class="row clearfix transparentBlackBg rounded well ui-transition ui-shadow">
		<div class="col-md-12 column">
			<div class="page-header">
				<h1 class="showOnHover">
					Todo! <small class="targetToShow">1.0</small>
				</h1>
			</div>
		</div>

		<div class="col-md-12 column">
			<div class="btn-group btn-group-lg">
				 <button class="btn btn-default pull-right" type="button"id="modal-275600" href="#modal-container-275600" role="button" class="btn" data-toggle="modal"><em class="glyphicon glyphicon-file"></em> New todo!</button> 
				 {#<button class="btn btn-default" type="button"><em class="glyphicon glyphicon-align-center"></em> Center</button>#} 
			</div>					

			<table class="table">
				<thead>
					<tr>
						<th>
							#
						</th>
						<th>
							Title
						</th>
						<th>
							Last edited
						</th>
						<th>
							Status
						</th>
					</tr>
				</thead>
				<tbody id="todoList" class="ui-loadable" data-module="backend" data-controller="todo" data-action="list">

				</tbody>
			</table>
		</div>
	</div>

{% endblock %}

