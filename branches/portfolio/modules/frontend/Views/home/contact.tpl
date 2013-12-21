{% extends appLayout %}

{% block meta_title %}nbonnici.info:~${% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}

{% endblock %}

{% block main %}
                <div class="row">
					<div class="col-sm-offset-3 col-sm-6 transparentBlackBg ui-shadow padding">
						<form name="sentMessage" class="" id="contactForm" novalidate="">
					         <h1><span class="glyphicon glyphicon-envelope"></span>  {{tr['contact_me']}}</h1>
							 <div class="control-group">
					                    <div class="controls">
								<input type="text" class="form-control" placeholder="{{tr['your_email']}}" id="name" required="" data-validation-required-message="{{tr['your_email_error']}}">
								  <p class="help-block"></p>
							   </div>
						         </div> 	
					                <div class="control-group">
					                  <div class="controls">
								<input type="email" class="form-control" placeholder="{{tr['your_fullname']}}" id="email" required="" data-validation-required-message="{{tr['your_fullname_error']}}">
							<div class="help-block"></div></div>
						    </div> 	
								  
					               <div class="control-group">
					                 <div class="controls">
									 <textarea rows="10" cols="100" class="form-control" placeholder="{{tr['your_message']}}" id="message" required="" data-validation-required-message="{{tr['your_message_error']}}" minlength="5" data-validation-minlength-message="Min 5 characters" maxlength="999" style="resize:none"></textarea>
							<div class="help-block"></div></div>
					               </div> 		 
						     <div id="success"> </div> <!-- For success/fail messages -->
						    <button type="submit" class="btn btn-primary pull-right">{{tr['send']}}</button><br>
				          </form>
					</div>		
				</div>



{% endblock %}