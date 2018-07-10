<?php
/**
 * Shortcode für Kalender Umrechner de / en
 *
 * @package ulb_menalib 
 */


function de_kalenderrechner() 
{
return <<<FORMULAR

<script language="JavaScript" type="text/javascript">       <!--
       var h,g,i;
       
       function gregor (h)
       {
	       i = h - (h/33) + 622;
	       g = Math.round(i);
	       return g;
       }
               
       function islam (g)
       {
	       i = g - 622 + (g-622)/32;
	       h = Math.round(i);
	       return h;
       }

               function onCalendarButtonClick(evt)
               {
	               	var input = document.getElementById('calendarYearInput');
	               	var output = document.getElementById('calendarYearOutput');
	               	var button = evt.target;
	               	var value = input.value;
	           	

               }

      //-->
      </script>

      <p>Bitte tragen Sie links das Jahr (Hiǧra oder Gregorianisch) ein und klicken Sie auf den entsprechenden Button.</p>


      <form name="formular1">
      <table border="0">
      	<tr>
      		<td>
      			Eingabe:<br><input type="text" id="calendarYearInput" name="jahrEin" size="4" maxlength="4" />
      		</td>
      		<td>
      			<p>
      				<input type="button" value="Greg. Jahr&nbsp; ⇛ &nbsp;Hiǧri Jahr" onclick="formular1.jahrAus.value=islam(Number(formular1.jahrEin.value))" />
      			</p> 
      				<input type="Button" value="Hiǧri Jahr&nbsp; ⇛ &nbsp;Greg. Jahr" onclick="formular1.jahrAus.value=gregor(Number(formular1.jahrEin.value))" />
      		</td>

      		<td>
      			Ausgabe:<br><input type="text" id="calendarYearOutput" name="jahrAus" size="4" maxlength="4" readonly/>
      		</td>
      	</tr>
      </table>

       </form>


      <p>
      	Das Ergebnis ist ein N&auml;herungswert. Jedes Hiǧra-Jahr entspricht einem Gregorianischen Jahr und umgekehrt. 

      <p>
      	Die Konvertierung basiert auf dieser Formel:  <code>G=H-(H/33)+622</code> und <code>H=G-622+(G-622)/32</code> (Carl Brockelmann: Arabische Grammatik, 12. neubearbeitete Auflage, Leipzig: Harrassowitz, 1948, p. 209). 
      </p>

      <p>
      	Wenn Sie genaue Daten mit Monat und Jahr konvertieren wollen, gehen Sie bitte auf diese Website: <a href="http://mela.us/hegira.html" target="new">Conversion of Islamic and Christian dates von J. Thomann</a> oder auf <a href="http://www.nabkal.de/kalrech.html" target="new">N.A.B. Rechner</a> von Nikolaus A. B&auml;r.
      </p>
      </p>

     

      	</p>
FORMULAR;
}
add_shortcode('de_kalender', 'de_kalenderrechner');









function en_kalenderrechner() {
return <<<FORMULAR

<script language="JavaScript" type="text/javascript">
	<!--
	var h,g,i;
	
	function gregor (h)
		{
		i = h - (h/33) + 622;
		g = Math.round(i);
		return g;
		}
		
	function islam (g)
		{
		i = g - 622 + (g-622)/32;
		h = Math.round(i);
		return h;
		}
	//-->
	</script>
<p>Enter the year you want to convert (Gregorian or Hijra) into the [i]Input[/i]-field and click the button for the conversion.</p>
<p>&nbsp;</p>
<p><form name="formular1">
<table border="0"><tr>
<td>Input:<br /><input name="jahrEin" size="4" maxlength="4" />&nbsp;&nbsp;&nbsp;</td>
<td><p>&nbsp;<input type="button" value=" Greg. year&nbsp; --&gt; &nbsp;Hijri year&nbsp;" onclick="formular1.jahrAus.value=islam(Number(formular1.jahrEin.value))" /></p>
       &nbsp;<input type="Button" value="&nbsp;Hijri year&nbsp; --&gt; &nbsp;Greg. year " onclick="formular1.jahrAus.value=gregor(Number(formular1.jahrEin.value))" /></td>
<td>&nbsp;&nbsp;&nbsp;Output:<br />&nbsp;&nbsp;&nbsp;<input name="jahrAus" size="4" maxlength="4" /></td>
</tr></table>
<p>&nbsp;</p>
<p>The result is an approximate date. Every Hijri year corresponds to one Gregorian year and vice versa. The conversion is based on the follwing formulae: G=H-(H/33)+622 and H=G-622+(G-622)/32 (Carl Brockelmann: Arabische Grammatik, 12. neubearbeitete Auflage, Leipzig: Harrassowitz, 1948, p. 209).

If you want to convert exact dates including month and day please go to the converter offered by the website <a href="http://mela.us/hegira.html" target="_blank">Conversion of Islamic and Christian dates</a> by J. Thomann or to the <a href="http://www.nabkal.de/kalrech.html" target="new">N.A.B. Rechner</a> developed by Nikolaus A. B&auml;r.</p>
</form></p>
FORMULAR;
}

add_shortcode('en_kalender', 'en_kalenderrechner');