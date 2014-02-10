{% extends 'layout.tpl' %}

{% block meta_title %}Todo!{% endblock meta_title %}
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
<div class="modal fade" id="modal-create-todo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

		    <div class="modal-header">
		         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title" id="myModalLabel">
		            Nouveau Todo!
		        </h4>
		    </div>
		    
		    <div id="modal-create-content" class="modal-body">
		    
		    </div> 

		    <div class="modal-footer">
		         <button type="button" class="btn btn-default" data-dismiss="modal">{{tr['cancel']}}</button>
		         &nbsp;<button type="button" class="ui-sendform refreshOnCallback btn btn-primary" data-form="#newTodoForm" title="Enregistrer ce todo">{{tr['save']}}</button>
		    </div>     
		</div>
	</div>				
</div>
<div class="modal fade" id="modal-todo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-todo-content">
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
					Todo! <small class="targetToShow">1.0</small>
				</h1>
			</div>
		</div>

		<div class="col-md-12 column">
			<div class="btn-group btn-group-lg">
                 <button type="button" class="btn btn-lg btn-default">
                     <span class="glyphicon glyphicon-refresh"></span> Raffraichir
                 </button>
				 <button  href="#modal-create-todo" type="button" class="hidden btn btn-lg btn-danger ui-sendxhr" data-url="/backend/todo/create/" data-selector="#modal-create-content" role="button" data-toggle="modal">
				    <span class="glyphicon glyphicon-trash"></span> Supprimer
				 </button> 
				 <button  href="#modal-create-todo" type="button" class="btn btn-lg btn-info ui-sendxhr" data-url="/backend/todo/create/" data-selector="#modal-create-content" role="button" data-toggle="modal">
				    <span class="glyphicon glyphicon-file"></span> New todo!
				 </button> 
			</div>					

			<table id="todo-last-items" class="table table-responsive">
				<thead>
					<tr>
						<th>
							<input type="checkbox" class="ui-select-all" data-container="#todo-last-items" />
						</th>
						<th>
							Title
						</th>
                        <th>
                            Actions
                        </th>
						<th>
							Deadline
						</th>
						<th>
							Last edited
						</th>
					</tr>
				</thead>
				<tbody id="todoList" class="ui-loadable" data-module="backend" data-controller="todo" data-action="list">

				</tbody>
			</table>
		</div>
	</div>

{% endblock %}

