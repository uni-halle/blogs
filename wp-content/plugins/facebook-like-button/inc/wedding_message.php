<?php

function showWeddingMessage()
{
	if(get_option('fbGeekWedding') == 0)
	{
		echo "<center><div ><br><a href='http://geeksavenue.com/wedding/' target='_blank'><img style='box-shadow:inset 0 0 5px black; border-radius:5px;' src='". plugins_url('images/GeekWedding.png',__FILE__)."' alt='Royal Geek Wedding Donation' /></a><br><a href='".admin_url()."admin.php?page=main.php&action=closeWedding'>I Don't Care About Geeks, Close This Message :'(</a></div></center>";
	}
}

?>