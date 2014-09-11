<?php

function art_parse_template($template_name, $variables = array(), $ext = null) 
{
  if ($ext === null ){
    $ext = art_option('theme.template_ext'); 
  }
  $path = TEMPLATEPATH . '/templates/'.$template_name.'.'.$ext;
  if (!is_readable($path)) return '';
  if ($ext == 'php'){
    extract($variables, EXTR_SKIP);
    ob_start();
    include ($path);
    return ob_get_clean();
	} else {
    $content = file_get_contents($path);
    return art_parse($content, $variables);
	}
	
}

function art_parse($template, $variables = array()) 
{
	foreach ($variables as $key => $value)
	{
	     $template = str_replace('[' . $key . ']', $value, $template);
	}
	return $template;
}
