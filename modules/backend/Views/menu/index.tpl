			
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="panel-group" id="ui-mvc-menu">
				<div class="panel panel-default">
					<div class="panel-heading">
						 <a class="panel-title" data-toggle="collapse" data-parent="#ui-mvc-menu" href="#ui-mvc-menu-all">Backend</a>
					</div>
					<div id="ui-mvc-menu-all" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="list-group">
								<a href="#" class="list-group-item active">Home</a>
								<div class="list-group-item">
								{% for sController, aActions in aControllers %}
									<h4 class="list-group-item-heading">
										{{sController}}
									</h4>									
									{% for sAction in aActions %}
									<p class="list-group-item-text">
										<a href="/{{sModule}}/{{sController}}/{{sAction}}">{{sAction}}</a>
									</p>				
									{% endfor %}		
								{% endfor %}									
								</div>
								<div class="list-group-item">
									<span class="badge">14</span>Help
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						 <a class="panel-title" data-toggle="collapse" data-parent="#ui-mvc-menu" href="#ui-mvc-menu-front">Frontend</a>
					</div>
					<div id="ui-mvc-menu-front" class="panel-collapse collapse">
						<div class="panel-body">
						{% for sController, aActions in aFrontControllers %}
							<h4 class="list-group-item-heading">
								{{sController}}
							</h4>									
							{% for sAction in aActions %}
							<p class="list-group-item-text">
								<a href="/{{sModule}}/{{sController}}/{{sAction}}">{{sAction}}</a>
							</p>				
							{% endfor %}		
						{% endfor %}	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			

								