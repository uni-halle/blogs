<?php
function check_adress( $email )
{
	$expression = "^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}\.){0,}[a-zA-Z0-9-]{3,}(\.[a-zA-Z]{2,4}){1,2}$";
	if ( ereg( $expression, $email ) )
		return TRUE;
	else
		return FALSE;
} 
?>