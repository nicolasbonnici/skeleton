            	<div class="row-fluid">	            	
					<div class="span12 alert alert-success alert-block">
					  <button type="button" class="close" data-dismiss="alert">×</button>
					  <h4><em class="icon icon-time"></em>/{{sController}}/{{sAction}}&nbsp;Rendered in {{render_time - framework_started}}</h4>

					  <ul>
					  {% for sClass in aLoadedClass %}
					  	<li><strong>{{sClass}}</strong></li>
					  {% endfor %}
					  </ul>
					</div>									            	
            	</div>