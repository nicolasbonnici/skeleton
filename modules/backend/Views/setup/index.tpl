{% extends 'layout.tpl' %}

{% block meta_title %}Setup{% endblock meta_title %}
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
<div class="transparentBlackBg rounded well ui-shadow">
	<div class="row">
		<div class="col-md-12">
			<h1>
				Admin dashboard <small>configure your installation</small>
			</h1>
		</div>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row clearfix">
				<div class="col-md-3 column">
					<div class="list-group">
						 <a href="#" class="list-group-item active"><span class="glyphicon glyphicon-dashboard"></span> {{tr['admin_panel']}}</a>
						<div class="list-group-item">
							<p class="list-group-item-text">
								<a href="#" class="ui-sendxhr" data-url="/backend/setup/users" data-selector="#setupMain" title="{{tr['users_managment_tip']}}" data-placement="right">								
									<span class="glyphicon glyphicon-user"></span> {{tr['users_managment']}}								
								</a>
							</p>
						</div>
						<div class="list-group-item">							
							<p class="list-group-item-text">
								<a href="#" class="ui-sendxhr" data-url="/backend/setup/acl" data-selector="#setupMain" title="{{tr['acl_managment_tip']}}" data-placement="right">														
									<span class="glyphicon glyphicon-lock"></span> {{tr['acl_managment']}}
								</a>
							</p>
						</div>
						<div class="list-group-item">							
							<p class="list-group-item-text">
								<a href="#" class="ui-sendxhr" data-url="/backend/setup/entities" data-selector="#setupMain" title="{{tr['entities_managment_tip']}}" data-placement="right">																				
									<span class="glyphicon glyphicon-hdd"></span> {{tr['entities_managment']}}
								</a>
							</p>
						</div>
						<div class="list-group-item">							
							<p class="list-group-item-text">
								<span class="glyphicon glyphicon-ban-circle"></span> {{tr['acl_managment']}}
							</p>
						</div>
						<div class="list-group-item">
							<span class="badge">14</span><span class="glyphicon glyphicon-question-sign"></span> {{tr['support_center']}}
						</div>
					</div>
				</div>			
			
				<div id="setupMain" class="col-md-9 column">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							 <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="inputEmail3" />
							</div>
						</div>
						<div class="form-group">
							 <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="inputPassword3" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="checkbox">
									 <label><input type="checkbox" /> Remember me</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								 <button type="submit" class="btn btn-default">Sign in</button>
							</div>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
</div>
{% endblock %}