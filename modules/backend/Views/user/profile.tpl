{% extends appLayout %}

{% block meta_title %}Profile{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/hotkeys/js/jquery.hotkeys.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
{% endblock %}


{% block main %}
				<div class="row">


					<div class="col-md-12 column transparentBlackBg well rounded">
						<div class="page-header">
						  <h1>{{tr['profile']}}</h1>
						</div>
						
                        {% if passwordUpdate|Exists %}

                            <div class="alert alert-{% if passwordUpdate %}success{% else %}error{% endif %}">
                                {% if passwordUpdate %}
                                    <p>{{tr['password_update_success']}}</p>
                                {% else %}
                                    <p>{{tr['password_update_error']}}</p>                              
                                {% endif %}                         
                            </div>
                        {% endif %}
	                         <form class="form-horizontal" role="form" action="" method="POST">
	                             <div class="form-group">
	                                  <label for="" class="col-sm-2 control-label">{{tr['avatar']}}</label>
	                                 <div class="col-sm-10">
	                                     <img src="{{sGravatarSrc128}}" alt="Avatar" />                                      
	                                 </div>
	                             </div>                              
	                             <div class="form-group">
	                                 <label for="" class="col-sm-2 control-label">{{tr['username']}}</label>
	                                 <div class="col-sm-10">
	                                     <a href="#" class="ui-editable" data-type="text" data-entity="User" data-url="/backend/crud/update/id/{{aSession['iduser']}}" data-pk="{{aSession['iduser']}}" data-name="firstname">{{aSession['firstname']}}</a>&nbsp;
	                                     <a href="#" class="ui-editable" data-type="text" data-entity="User" data-url="/backend/crud/update/id/{{aSession['iduser']}}" data-pk="{{aSession['iduser']}}" data-name="lastname">{{aSession['lastname']}}</a>&nbsp;
	                                 </div>
	                             </div>
	                             <div class="form-group">
	                                  <label for="" class="col-sm-2 control-label">{{tr['email']}}</label>
	                                 <div class="col-sm-10">
	                                     <a href="#" class="ui-editable" data-type="email" data-entity="User" data-url="/backend/crud/update/id/{{aSession['iduser']}}" data-pk="{{aSession['iduser']}}" data-name="mail" >{{aSession['mail']}}</a>&nbsp;
	                                 </div>
	                             </div>
	                             <div class="form-group">
	                                  <label for="" class="col-sm-2 control-label">{{tr['your_password']}}</label>
	                                 <div class="col-sm-10">
	                                     <input type="password" class="form-control" placeholder="{{tr['input_password']}}" name="password" class="">
	                                     <input type="password" class="form-control" placeholder="{{tr['input_new_password']}}" name="passwordNew1" class="">
	                                     <input type="password" class="form-control" placeholder="{{tr['confirm_new_password']}}" name="passwordNew2" class="">
	                                 </div>
	                             </div>
	                             <div class="form-group">
	                                 <div class="col-md-12 text-right">
	                                      <button type="submit" class="btn btn-primary">{{tr['update_password']}}</button>
	                                 </div>
	                             </div>                                  
	                         </form>                        
                        </div>
					</div>

{% endblock %}