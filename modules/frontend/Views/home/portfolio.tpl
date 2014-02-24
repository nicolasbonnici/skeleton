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
                        <div class="col-md-12 whiteBg ui-shadow rounded">
                                <div class="row">

                                	<div class="col-md-9">
						                <h1 class="showOnHover">
						                    Portfolio <small class="targetToShow">un peu de bla bla</small>
						                </h1>
                                	    <p></p>
									</div>
									
                                	<div class="col-md-3">
                                		<h1>Languages</h1>
                                		<h3>PHP5</h3>
										<input type="text" class="ui-circular-progress" value="90" data-width="120" data-fgColor="#669" data-readOnly="true">
                                		<h3>Java</h3>
                                        <input type="text" class="ui-circular-progress" value="50" data-width="120" data-fgColor="#669" data-readOnly="true">
                                		<h3>Python</h3>
                                        <input type="text" class="ui-circular-progress" value="60" data-width="120" data-fgColor="#669" data-readOnly="true">
										<h3>JavaScript</h3>
										<input type="text" class="ui-circular-progress" value="70" data-width="120" data-fgColor="#1eff00" data-readOnly="true">
										<h1>SGBD</h1>
										<h3>MySQL</h3>
										<input type="text" class="ui-circular-progress" value="80" data-width="120" data-fgColor="#e97b00" data-readOnly="true">
										<h3>Oracle SQL</h3>
										<input type="text" class="ui-circular-progress" value="60" data-width="120" data-fgColor="#e97b00" data-readOnly="true">
										<h3>NoSQL (MongoDb)</h3>
										<input type="text" class="ui-circular-progress" value="70" data-width="120" data-fgColor="#e97b00" data-readOnly="true">
										<h1>Misc</h1>
										<h3>CSS2/3</h3>
										<input type="text" class="ui-circular-progress" value="85" data-width="120" data-fgColor="#48acde" data-readOnly="true">
										<h3>HTML5</h3>
										<input type="text" class="ui-circular-progress" value="100" data-width="120" data-fgColor="#48acde" data-readOnly="true">
                                	</div>
                                </div>                    
                        </div>
                </div>

{% endblock %}