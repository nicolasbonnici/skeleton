<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/auth/index.tpl */
function haanga_dc4b37af3115a95e88628178b4090c0ce2a28cbc($vars152b59416bc238, $return=FALSE, $blocks=array())
{
    extract($vars152b59416bc238);
    if ($return == TRUE) {
        ob_start();
    }
    $buffer1  = 'Test';
    $blocks['meta_title']  = (isset($blocks['meta_title']) ? (strpos($blocks['meta_title'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_title'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_title'])) : $buffer1);
    $buffer1  = '';
    $blocks['meta_description']  = (isset($blocks['meta_description']) ? (strpos($blocks['meta_description'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_description'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_description'])) : $buffer1);
    $buffer1  = '

';
    $blocks['css']  = (isset($blocks['css']) ? (strpos($blocks['css'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['css'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['css'])) : $buffer1);
    $buffer1  = '

';
    $blocks['js']  = (isset($blocks['js']) ? (strpos($blocks['js'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['js'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['js'])) : $buffer1);
    $buffer1  = '

<div class="container">

	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="page-header">
				<h1>
					'.htmlspecialchars($tr['welcome']).'! <small>'.htmlspecialchars($tr['login_to_your_account']).'</small>
				</h1>
			</div>
		</div>
	</div>

	<div class="row clearfix">
		<div class="col-md-12 column">
        	<form class="form-horizontal well ui-shadow ui-rounded" role="form" method="POST" action="/frontend/auth/">
			
				<div class="alert alert-info">
					<p>'.htmlspecialchars($tr['login_helper']).'</p>
				</div>
			
				<div class="form-group">
					 <label for="emailInput" class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10">
                    	<input type="email" placeholder="Type your email john.doe@domain.com" class="form-control" id="emailInput" name="email">
					</div>
				</div>
				<div class="form-group">
					 <label for="inputPassword" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-10">
						<input type="password" placeholder="type your password" class="form-control" id="inputPassword" name="password">					
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
                         <button type="submit" id="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Sign in</button>
                         <button type="button" class="btn btn-secondary button-loading" data-loading-text="Loading...">Forgot Password</button>						 
					</div>
				</div>			
			</form>
		</div>
	</div>
</div>

';
    $blocks['main']  = (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1);
    echo Haanga::Load($appLoginLayout, $vars152b59416bc238, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}