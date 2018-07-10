<?php
/**
 * Shortcode für Al Manhal Suche
 *
 * @package ulb_menalib 
 */



function almanhal_search($atts = [], $tag = '') 
{

      // https://developer.wordpress.org/plugins/shortcodes/shortcodes-with-parameters/
     // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
       // override default attributes with user attributes
    $search_atts = shortcode_atts([
                                     'placeholder' => 'Search Ha:Lit',
                                     'submit' => 'Suche',
                                     'style' => 'default',
                                 ], $atts, $tag);
    $placeholder = esc_html__($search_atts['placeholder'], 'search');
    $submit = esc_html__($search_atts['submit'], 'search');
    $style = esc_html__($search_atts['style'], 'search');

   /*print esc_html__($search_atts['placeholder'], 'search');*/
      
return <<<FORMULAR
<div class="external-search $style">

		<select id="select-institution" width="100%">
			<option value="none">[:de]Bitte wählen Sie Ihre Institution:[:en]Please select your institution:[:]</option>
			<option value="false">[:de]Kein Internetzugang über eine Institution[:en]Non-institutional internet access[:]</option>
			<option value="true">Bamberg | Otto-Friedrich-Universität Bamberg</option>
			<option value="true">Bayreuth | Universität Bayreuth</option>
			<option value="true">Beirut | Orient-Institut Beirut</option>
			<option value="true">Berlin | Freie Universität Berlin</option>
			<option value="true">Berlin | Humboldt-Universität zu Berlin</option>
			<option value="true">Berlin | Leibniz-Zentrum Moderner Orient</option>
			<option value="true">Berlin | Staatsbibliothek zu Berlin - Preußischer Kulturbesitz</option>
			<option value="true">Berlin | Stiftung Wissenschaft und Politik (SWP) Deutsches Institut für Internationale Politik und Sicherheit</option>
			<option value="true">Bochum | Ruhr-Universität Bochum</option>
			<option value="true">Bonn | Rheinische Friedrich-Wilhelms-Universität Bonn</option>
			<option value="true">Erfurt | Universität Erfurt</option>
			<option value="true">Erlangen | Friedrich-Alexander-Universität Erlangen-Nürnberg</option>
			<option value="true">Frankfurt am Main | Johann Wolfgang Goethe-Universität Frankfurt</option>
			<option value="true">Freiburg | Albert-Ludwigs-Universität Freiburg</option>
			<option value="true">Göttingen | Niedersächsische Staats- und Universitätsbibliothek Göttingen</option>
			<option value="true">Halle | Martin-Luther-Universität Halle-Wittenberg</option>
			<option value="true">Hamburg | GIGA German Institute of Global and Area Studies / Leibniz-Institut für Globale und Regionale Studien</option>
			<option value="true">Hamburg | Universität Hamburg</option>
			<option value="true">Heidelberg | Universität Heidelberg</option>
			<option value="true">Istanbul | Orient-Institut Istanbul</option>
			<option value="true">Jena | Friedrich-Schiller-Universität Jena</option>
			<option value="true">Kiel | Christian-Albrechts-Universität zu Kiel</option>
			<option value="true">Köln | Universität zu Köln</option>
			<option value="true">Leipzig | Universität Leipzig</option>
			<option value="true">Mainz | Johannes Gutenberg-Universität Mainz</option>
			<option value="true">Marburg | Philipps-Universität Marburg</option>
			<option value="true">München | Bayerische Staatsbibliothek</option>
			<option value="true">München | Ludwig-Maximilians-Universität München</option>
			<option value="true">Münster | Westfälische Wilhelms-Universität Münster</option>
			<option value="true">Osnabrück | Universität Osnabrück</option>
			<option value="true">Tübingen | Eberhard Karls Universität Tübingen</option>
			<option value="false">[:de]Andere Institution [:en]Other institution[:]</option>



		</select>




      <form id="form-search" accept-charset="UTF-8" action="https://platform-almanhal-com.fid-nahost.idm.oclc.org/Search/Result" method="get" target="_blank">

      <!-- Suchbox und Button -->         
            <input id='input-search' name="q" size="5" type="text" value="" placeholder="$placeholder">
            <!--old: <input value="$submit" type="submit">-->
            <button id='submit-search'>$submit</button>
    </form>


<script type="text/javascript">

	var form, input, select, submit, enabled;

	function initForm() 
	{
		form = document.getElementById('form-search');
		input = document.getElementById('input-search');
		select = document.getElementById('select-institution');
		submit = document.getElementById('submit-search');




		var messages = {};
		messages.none = '[:de]Bitte wählen Sie zuerst Ihre Institution aus.[:en]Please select your institution first.[:]';
		messages.false = '[:de]Sie haben leider keinen direkten Zugriff auf die Sammlung. Bitte lesen Sie die Hiweise zum Nutzerkreis.[:en]Sorry you have no direct access to the collection. Please notice the istructions about the circle of users.[:]';


		function disableSearch()
		{
			input.setAttribute('disabled', true);
			input.setAttribute('style', 'pointer-events:none;');
			submit.setAttribute('style', 'pointer-events:none;');
			form.setAttribute('style', 'cursor:not-allowed;opacity:0.75;');
			enabled = false;
		}

		function enableSearch()
		{
			input.removeAttribute('disabled');
			input.removeAttribute('style');
			submit.removeAttribute('style');
			form.removeAttribute('style');
			enabled = true;
		}
		

		select.addEventListener('change', onSelectChange);
		function onSelectChange(evt)
		{
			if(select.value == 'true')
			{
				enableSearch();
			}else
			{
				disableSearch();
			}
		}

		onSelectChange(null);

		form.addEventListener('click', onSubmitClick);
		function onSubmitClick(evt)
		{
			if(!enabled)
			{
				alert( messages[select.value]);

			}

		}



		}

		initForm();

</script>


    </div>

FORMULAR;
}


add_shortcode('almanhal_search', 'almanhal_search');



/*
Usage: 

[menadoc_search submit="Go!" placeholder="Suche und Du wirst finden"]

*/
