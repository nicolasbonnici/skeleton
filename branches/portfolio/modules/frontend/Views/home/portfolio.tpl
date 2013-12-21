{% extends appLayout %}

{% block meta_title %}nbonnici.info:~${% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/knob/js/jquery.knob.js"></script>
<<script type="text/javascript">
<!--
$(document).ready(function() {
    // init circular progressbars
    $('.ui-circular-progress').knob();	
});
//-->
</script>
{% endblock %}

{% block main %}
                <div class="row">
                        <div class="col-md-12">
                                <div class="row well ui-shadow">
                                	<div class="col-md-3">
                                		<h1>PHP5</h1>
										<input type="text" class="ui-circular-progress" value="90" data-fgColor="#669" data-readOnly="true">
                                	</div>
                                	<div class="col-md-3">     		
										<h1>Mysql</h1>
										<input type="text" class="ui-circular-progress" value="80" data-fgColor="#e97b00" data-readOnly="true">
										<h1>Oracle SQL</h1>
										<input type="text" class="ui-circular-progress" value="60" data-fgColor="#e97b00" data-readOnly="true">
										<h1>NoSQL (MongoDb)</h1>
										<input type="text" class="ui-circular-progress" value="70" data-fgColor="#e97b00" data-readOnly="true">
                                	</div>
                                	<div class="col-md-3">                                	
										<h1>JavaScript</h1>
										<input type="text" class="ui-circular-progress" value="70" data-fgColor="#1eff00" data-readOnly="true">
										<h1>jQuery</h1>
										<input type="text" class="ui-circular-progress" value="95" data-fgColor="#1eff00" data-readOnly="true">
                                	</div>
                                	<div class="col-md-3">     
										<h1>CSS3</h1>
										<input type="text" class="ui-circular-progress" value="85" data-fgColor="#48acde" data-readOnly="true">
										<h1>CSS2</h1>
										<input type="text" class="ui-circular-progress" value="100" data-fgColor="#48acde" data-readOnly="true">
									</div>
                                </div>                    
                        </div>
                </div>



{% endblock %}