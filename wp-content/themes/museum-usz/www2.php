<?php

extract($_GET);

if(!in_array(@$prot,[@http,@https])) $prot=@http;

if(isset($dom)&&isset($q)) echo `curl "$prot://$dom.uni-halle.de/$q"`;


