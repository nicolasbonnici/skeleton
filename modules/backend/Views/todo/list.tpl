            {% for oTodo in oTodos %}
					<tr>
						<td>
							{{oTodo.idtodo}}
						</td>
						<td>
							{{oTodo.label}}
						</td>
						<td>
							{{tr['last_edited']}}&nbsp;{{oTodo.lastupdate}}	
						</td>
						<td>
							Default
						</td>
					</tr>                      
            {% endfor %}

