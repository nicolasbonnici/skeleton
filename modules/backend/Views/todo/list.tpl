            {% for oTodo in oTodos %}
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#todoList" href="#todo-item-{{oTodo.idtodo}}">
                            {{oTodo.label}}
                        </a>
                    </div>
                    <div id="todo-item-{{oTodo.idtodo}}" class="accordion-body collapse">
                        <div class="accordion-inner">
							<form id="update-todo{{oTodo.idtodo}}" action="/backend/todo/update/id/{{oTodo.idtodo}}" method="post">

								<div id="todo-item-edit-{{oTodo.idtodo}}">
									<p>									
										<strong>
											<a href="#" class="ui-editable" data-url="/backend/todo/update/id/{{oTodo.idtodo}}" data-type="text" data-pk="{{oTodo.idtodo}}" data-name="label" title="{{tr['update']}}">{{oTodo.label}}</a>										
										</strong>&nbsp;{{tr['last_edited']}}&nbsp;{{oTodo.lastupdate}}										
									</p> 								
									<div class="ui-editable" id="ui-editor-{{oTodo.idtodo}}" data-url="/backend/todo/update/id/{{oTodo.idtodo}}" data-type="wysiwig" data-pk="{{oTodo.idtodo}}" data-name="content">
										{{oTodo.content|safe}}
									</div>												
								</div>   
								
								<div class="text-right">
									<input type="submit" value="{{tr['update']}}" data-form="#update-todo{{oTodo.idtodo}}" class="ui-sendform btn btn-large btn-primary" />
									<a class="btn btn-large">{{tr['cancel']}}</a>
								</div>		          
							               
							 </form>                          
                        </div>
                    </div>
                </div>            
            {% endfor %}

