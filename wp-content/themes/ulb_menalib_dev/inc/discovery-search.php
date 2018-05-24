<?php
/**
 * Shortcode für Hal:Lit Discovery Suche
 *
 * @package ulb_menalib 
 */



function de_discovery_search() {
return <<<FORMULAR
	<!-- Aufbau der URL -->
	<form accept-charset="UTF-8" action="https://search.ebscohost.com/login.aspx" method="get" target="_blank">
            <input name="direct" value="true" type="hidden">
            <input name="scope" value="site" type="hidden">
            <input name="site" value="eds-live" type="hidden">
	<!-- Authentifizierung via IP, sonst Gast -->
            <input name="authtype" value="ip,guest" type="hidden">
            <input name="custid" value="s6374753" type="hidden">
            <input name="groupid" value="main" type="hidden">
            <input name="profile" value="eds" type="hidden">
	<!-- Einschraenkung: Verfuegbar (1. Limiter ID + Limiter Value: Verfuegbar in Bibliothek + Value Yes) -->
            <input name="cli0" value="FT1" type="hidden">
            <input name="clv0" value="N" type="hidden">
	<!-- Suchbox und Button -->		
            <input name="bquery" size="35" type="text" value="" placeholder="Suche in Ha:Lit">
            <input value=" Suche " type="submit">
    </form>

FORMULAR;
}
add_shortcode('de_discovery', 'de_discovery_search');


add_shortcode('en_discovery', 'de_discovery_search');
