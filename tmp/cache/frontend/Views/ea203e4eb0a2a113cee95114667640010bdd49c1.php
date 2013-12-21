<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/home/contact.tpl */
function haanga_ea203e4eb0a2a113cee95114667640010bdd49c1($vars152b593d9476b4, $return=FALSE, $blocks=array())
{
    extract($vars152b593d9476b4);
    if ($return == TRUE) {
        ob_start();
    }
    $buffer1  = 'nbonnici.info:~$';
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
                <div class="row">
					<div class="col-sm-offset-3 col-sm-6 transparentBlackBg ui-shadow padding">
						<form name="sentMessage" class="" id="contactForm" novalidate="">
					         <h1><span class="glyphicon glyphicon-envelope"></span>  '.htmlspecialchars($tr['contact_me']).'</h1>
							 <div class="control-group">
					                    <div class="controls">
								<input type="text" class="form-control" placeholder="'.htmlspecialchars($tr['your_email']).'" id="name" required="" data-validation-required-message="'.htmlspecialchars($tr['your_email_error']).'">
								  <p class="help-block"></p>
							   </div>
						         </div> 	
					                <div class="control-group">
					                  <div class="controls">
								<input type="email" class="form-control" placeholder="'.htmlspecialchars($tr['your_fullname']).'" id="email" required="" data-validation-required-message="'.htmlspecialchars($tr['your_fullname_error']).'">
							<div class="help-block"></div></div>
						    </div> 	
								  
					               <div class="control-group">
					                 <div class="controls">
									 <textarea rows="10" cols="100" class="form-control" placeholder="'.htmlspecialchars($tr['your_message']).'" id="message" required="" data-validation-required-message="'.htmlspecialchars($tr['your_message_error']).'" minlength="5" data-validation-minlength-message="Min 5 characters" maxlength="999" style="resize:none"></textarea>
							<div class="help-block"></div></div>
					               </div> 		 
						     <div id="success"> </div> <!-- For success/fail messages -->
						    <button type="submit" class="btn btn-primary pull-right">'.htmlspecialchars($tr['send']).'</button><br>
				          </form>
					</div>		
				</div>



';
    $blocks['main']  = (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1);
    echo Haanga::Load($appLayout, $vars152b593d9476b4, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}