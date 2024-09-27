<?php
function loadTemplate($templateFileName, $variables = [])
{
    extract($variables);
    ob_start();
    require $templateFileName;
    return ob_get_clean();
}