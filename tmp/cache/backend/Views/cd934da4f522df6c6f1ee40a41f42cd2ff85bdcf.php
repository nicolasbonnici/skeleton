<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/backend/Views/layout.tpl */
function haanga_cd934da4f522df6c6f1ee40a41f42cd2ff85bdcf($vars152b59423043b1, $return=FALSE, $blocks=array())
{
    extract($vars152b59423043b1);
    if ($return == TRUE) {
        ob_start();
    }
    echo Haanga::Load($appLayout, $vars152b59423043b1, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}