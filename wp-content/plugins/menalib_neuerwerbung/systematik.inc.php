<?php
// ============================ "SSG Systematik"  table =========================================

// TODO: Laden aus csv Datei

 $content = array(
"1." 	   =>	array("de" => "ALLGEMEINES",                                                                         "en" => "GENERAL WORKS",                                                                     "Query"=> urlencode("lsy ssg1?"),                   "Parent" => "" ),
"2." 	   =>	array("de" => "ISLAMWISSENSCHAFT",                                                                   "en" => "ISLAMIC STUDIES",                                                                   "Query"=> urlencode("lsy ssg2?"),                   "Parent" => "" ),
"2.1." 	   =>	array("de" => "Allgemeines",                                                                         "en" => "General Works",                                                                     "Query"=> urlencode("(sys ssg2.1 or sys ssg6.1.)"), "Parent" => "2." ),
"2.2." 	   =>	array("de" => "Theologie, Religiöses Leben (Sunniten, Schiiten)",                                    "en" => "Theology, Religious Life ",                                                         "Query"=> urlencode("(sys ssg2.2 or sys ssg6.2.)"), "Parent" => "2." ),
"2.3." 	   =>	array("de" => "Mystik, Sufismus",                                                                    "en" => "Sufism",                                                                            "Query"=> urlencode("(sys ssg2.3)"),                "Parent" => "2." ),
"2.4." 	   =>	array("de" => "Glaubensrichtungen",                                                                  "en" => "Religious Denominations",                                                           "Query"=> urlencode("(sys ssg2.4 or sys ssg6.4.)"), "Parent" => "2." ),
"2.5." 	   =>	array("de" => "Interreligöse Beziehungen",                                                           "en" => "Interreligious Relations",                                                          "Query"=> urlencode("(sys ssg2.1)"),                "Parent" => "2." ),
"2.6." 	   =>	array("de" => "Islamisches Recht",                                                                   "en" => "Islamic law",                                                                       "Query"=> urlencode("(sys ssg2.6)"),                "Parent" => "2." ),
"2.7." 	   =>	array("de" => "Islamische Geschichte (Anfänge bis ca. 1400)",                                        "en" => "Islamic history (beginnings - 1400)",                                               "Query"=> urlencode("(sys ssg2.7)"),                "Parent" => "2." ),
"2.8." 	   =>	array("de" => "Kulturgeschichte, Philosophie, Wissenschaften",                                       "en" => "History of Culture and Civilization, Philosophy, Sciences",                         "Query"=> urlencode("(sys ssg2.8)"),                "Parent" => "2." ),
"2.9." 	   =>	array("de" => "Islamische Institutionen, islamisches Denken",                                        "en" => "Islamic Institutions, Islamic Thought",                                             "Query"=> urlencode("(sys ssg2.9)"),                "Parent" => "2." ),
"2.10."    =>	array("de" => "Islam und Moderne",                                                                   "en" => "Muslim Communities in the Modern World",                                            "Query"=> urlencode("(sys ssg2.10)"),               "Parent" => "2." ),
"2.11."    =>	array("de" => "Islam in Europa, Amerika, Australien",                                                "en" => "Islam in Europe, America, Australia",                                               "Query"=> urlencode("(sys ssg2.11)"),               "Parent" => "2." ),
"2.12."    =>	array("de" => "Islam in Süd-, Südost- und Ostasien",                                                 "en" => "Islam in South, Southeast and East Asia",                                           "Query"=> urlencode("(sys ssg2.12)"),               "Parent" => "2." ),
"2.13."    =>	array("de" => "Islam im Subsaharischen Afrika",                                                      "en" => "Islam in Sub-Saharan Africa",                                                       "Query"=> urlencode("(sys ssg2.13)"),               "Parent" => "2." ),
"3." 	   =>	array("de" => "REGIONEN, STAATEN und VÖLKER",                                                        "en" => "REGIONS, COUNTRIES and PEOPLES ",                                                   "Query"=> urlencode("(sys ssg3?)"),                 "Parent" => "" ),
"3.1." 	   =>	array("de" => "Allgemein",                                                                           "en" => "General Works",                                                                     "Query"=> urlencode("(sys ssg3.1.?)"),              "Parent" => "3." ),
"3.2." 	   =>	array("de" => "Einzelne Regionen",                                                                   "en" => "The Region",                                                                        "Query"=> urlencode("(sys ssg3.2.?)"),              "Parent" => "3." ),
// -- ergänzt --

"3.2.1."         => array("de" => "Arabische Halbinsel (Saudi-Arabien, Jemen, Oman, Golfstaaten)",                        "en" => " Arabian Peninsula (Saudi-Arabien, Jemen, Oman, Golfstaaten)",                                 "Query"=> urlencode("(sys ssg3.2.1.?)"),       "Parent"=>"3.2."),
"3.2.1.2."       => array("de" => "Saudi-Arabien",                                                                        "en" => "Saudi Arabia",                                                                                 "Query"=> urlencode("(sys ssg3.2.1.2.?)"),     "Parent"=>"3.2.1."),
"3.2.1.3."       => array("de" => "Jemen",                                                                                "en" => "Yemen",                                                                                        "Query"=> urlencode("(sys ssg3.2.1.3.?)"),     "Parent"=>"3.2.1."),
"3.2.1.4."       => array("de" => "Kuweit",                                                                               "en" => "Kuwait",                                                                                       "Query"=> urlencode("(sys ssg3.2.1.4.?)"),     "Parent"=>"3.2.1."),
"3.2.1.5."       => array("de" => "Katar",                                                                                "en" => "Qatar",                                                                                        "Query"=> urlencode("(sys ssg3.2.1.5.?)"),     "Parent"=>"3.2.1."),
"3.2.1.6."       => array("de" => "Vereinigte Arabische Emirate",                                                         "en" => "United Arab Emirates",                                                                         "Query"=> urlencode("(sys ssg3.2.1.6.?)"),     "Parent"=>"3.2.1."),
"3.2.1.7."       => array("de" => "Oman",                                                                                 "en" => "Oman",                                                                                         "Query"=> urlencode("(sys ssg3.2.1.7.?)"),     "Parent"=>"3.2.1."),
"3.2.1.8."       => array("de" => "Bahrein",                                                                              "en" => "Bahrain",                                                                                      "Query"=> urlencode("(sys ssg3.2.1.8.?)"),     "Parent"=>"3.2.1."),

"3.2.2."         => array("de" => "Arabischer Osten (Irak, Syrien, Libanon, Jordanien, Palästina)",                       "en" => "Eastern Arab Countries and Peoples(Iraq, Syria, Libanon, Jordan, Palestine)",                  "Query"=> urlencode("(sys ssg3.2.2.?)"),       "Parent"=>"3.2." ),
"3.2.2.2."       => array("de" => "Irak",                                                                                 "en" => "Iraq",                                                                                         "Query"=> urlencode("(sys ssg3.2.2.2.?)"),     "Parent"=>"3.2.2."),
"3.2.2.3."       => array("de" => "Syrien",                                                                               "en" => "Syria",                                                                                        "Query"=> urlencode("(sys ssg3.2.2.3.?)"),     "Parent"=>"3.2.2."),
"3.2.2.4."       => array("de" => "Libanon",                                                                              "en" => "Lebanon",                                                                                      "Query"=> urlencode("(sys ssg3.2.2.4.?)"),     "Parent"=>"3.2.2."),
"3.2.2.5."       => array("de" => "Jordanien",                                                                            "en" => "Jordan",                                                                                       "Query"=> urlencode("(sys ssg3.2.2.5.?)"),     "Parent"=>"3.2.2."),
"3.2.2.6."       => array("de" => "Palästina",                                                                            "en" => "Palestine",                                                                                    "Query"=> urlencode("(sys ssg3.2.2.6.?)"),     "Parent"=>"3.2.2."),

"3.2.3."         => array("de" => "Maghreb, Nordafrika (Westsahara bis Ägypten, inkl. Sudan, Malta, Nordzypern)",          "en" => "North Africa (Western Sahara to Egypt, incl. Sudan),Malta and Northern Cyprus",                "Query"=> urlencode("(sys ssg3.2.3.?)"),       "Parent"=>"3.2." ),
"3.2.3.2."       => array("de" => "West-Sahara",                                                                          "en" => "Western Sahara",                                                                               "Query"=> urlencode("(sys ssg3.2.3.2.?)"),     "Parent"=>"3.2.3."),
"3.2.3.3."       => array("de" => "Mauretanien",                                                                          "en" => "Mauritania",                                                                                   "Query"=> urlencode("(sys ssg3.2.3.3.?)"),     "Parent"=>"3.2.3."),
"3.2.3.4."       => array("de" => "Marokko",                                                                              "en" => "Morocco",                                                                                      "Query"=> urlencode("(sys ssg3.2.3.4.?)"),     "Parent"=>"3.2.3."),
"3.2.3.5."       => array("de" => "Algerien",                                                                             "en" => "Algeria",                                                                                      "Query"=> urlencode("(sys ssg3.2.3.5.?)"),     "Parent"=>"3.2.3."),
"3.2.3.6."       => array("de" => "Tunesien",                                                                             "en" => "Tunisia",                                                                                      "Query"=> urlencode("(sys ssg3.2.3.6.?)"),     "Parent"=>"3.2.3."),
"3.2.3.7."       => array("de" => "Libyen",                                                                               "en" => "Libya",                                                                                        "Query"=> urlencode("(sys ssg3.2.3.7.?)"),     "Parent"=>"3.2.3."),
"3.2.3.8."       => array("de" => "Ägypten",                                                                              "en" => "Egypt",                                                                                        "Query"=> urlencode("(sys ssg3.2.3.8.?)"),     "Parent"=>"3.2.3."),
"3.2.3.9."       => array("de" => "Sudan",                                                                                "en" => "Sudan",                                                                                        "Query"=> urlencode("(sys ssg3.2.3.9.?)"),     "Parent"=>"3.2.3."),
"3.2.3.10."      => array("de" => "Malta",                                                                                "en" => "Malta",                                                                                        "Query"=> urlencode("(sys ssg3.2.3.10.?)"),    "Parent"=>"3.2.3."),
"3.2.3.11."      => array("de" => "Zypern",                                                                               "en" => "Cyprus",                                                                                       "Query"=> urlencode("(sys ssg3.2.3.11.?)"),    "Parent"=>"3.2.3."),

"3.2.4."         => array("de" => "Horn von Afrika (Äthiopien, ...)",                                                     "en" => "Horn of Africa (Ethiopia,...)",                                                                "Query"=> urlencode("(sys ssg3.2.4.?)"),       "Parent"=>"3.2." ),
"3.2.4.2."       => array("de" => "Äthiopien",                                                                            "en" => "Ethiopia",                                                                                     "Query"=> urlencode("(sys ssg3.2.4.2.?)"),     "Parent"=>"3.2.4."),
"3.2.4.3."       => array("de" => "Eritrea",                                                                              "en" => "Eritrea",                                                                                      "Query"=> urlencode("(sys ssg3.2.4.3.?)"),     "Parent"=>"3.2.4."),
"3.2.4.4."       => array("de" => "Dschibuti",                                                                            "en" => "Djibouti",                                                                                     "Query"=> urlencode("(sys ssg3.2.4.4.?)"),     "Parent"=>"3.2.4."),
"3.2.4.5."       => array("de" => "Somalia",                                                                              "en" => "Somalia",                                                                                      "Query"=> urlencode("(sys ssg3.2.4.5.?)"),     "Parent"=>"3.2.4."),

"3.2.5."         => array("de" => "Iranischsprachige Länder und Völker (Iran, Afghanistan, Tadschikistan, Kurden, ...)",  "en" => "Iran, Afghanistan, Tajikistan and other Iranian Peoples of the Region",                        "Query"=> urlencode("(sys ssg3.2.5.?)"),       "Parent"=>"3.2." ),
"3.2.5.2."       => array("de" => "Iran",                                                                                 "en" => "Iran",                                                                                         "Query"=> urlencode("(sys ssg3.2.5.2.?)"),     "Parent"=>"3.2.5."),
"3.2.5.3."       => array("de" => "Afghanistan",                                                                          "en" => "Afghanistan",                                                                                  "Query"=> urlencode("(sys ssg3.2.5.3.?)"),     "Parent"=>"3.2.5."),
"3.2.5.4."       => array("de" => "Tadschikistan",                                                                        "en" => "Tajikistan",                                                                                   "Query"=> urlencode("(sys ssg3.2.5.4.?)"),     "Parent"=>"3.2.5."),
"3.2.5.5."       => array("de" => "Kurden und andere iranische Völker der Region",                                        "en" => "Kurds and other Iranian Peoples of the Region",                                                "Query"=> urlencode("(sys ssg3.2.5.5.?)"),     "Parent"=>"3.2.5."),

"3.2.6."         => array("de" => "Türkei und europäische Turkvölker (inkl. Tataren)",                                    "en" => "Turkey and Turkic Peoples in South Europe and the Russian Federation",                         "Query"=> urlencode("(sys ssg3.2.6.?)"),       "Parent"=>"3.2." ),
"3.2.6.2."       => array("de" => "Türkei (inkl. Osmanisches Reich)",                                                     "en" => "Turkey (including the Ottoman Empire)",                                                        "Query"=> urlencode("(sys ssg3.2.6.2.?)"),     "Parent"=>"3.2.6."),
"3.2.6.3."       => array("de" => "Andere muslimische Turkvölker in Europa",                                              "en" => "Turkic Peoples in the Balkans and the Russian Federation",                                     "Query"=> urlencode("(sys ssg3.2.6.3.?)"),     "Parent"=>"3.2.6."),

"3.2.7."         => array("de" => "Kaukasus (Armenien, Georgien, Aserbaidschan, Nordkaukasus)",                           "en" => "Caucasus (Armenia, Georgia, Azerbaijan, Northern Caucasus)",                                   "Query"=> urlencode("(sys ssg3.2.7.?)"),       "Parent"=>"3.2." ),
"3.2.7.2."       => array("de" => "Armenien",                                                                             "en" => "Armenia",                                                                                      "Query"=> urlencode("(sys ssg3.2.7.2.?)"),     "Parent"=>"3.2.7."),
"3.2.7.3."       => array("de" => "Georgien",                                                                             "en" => "Georgia",                                                                                      "Query"=> urlencode("(sys ssg3.2.7.3.?)"),     "Parent"=>"3.2.7."),
"3.2.7.4."       => array("de" => "Aserbaidschan",                                                                        "en" => "Azerbaijan",                                                                                   "Query"=> urlencode("(sys ssg3.2.7.4.?)"),     "Parent"=>"3.2.7."),
"3.2.7.5."       => array("de" => "Andere Völker des Kaukasus",                                                           "en" => "Other Peoples of the Caucasus",                                                                "Query"=> urlencode("(sys ssg3.2.7.5.?)"),     "Parent"=>"3.2.7."),

"3.2.8."         => array("de" => "Turkvölker Mittelasiens (Kasachstan, Kirgisien, Usbekistan, Turkmenistan)",            "en" => "Turkic Peoples of Central Asia (Uzbekistan, Kazakhstan, Kyrgyzstan, Turkmenistan)",            "Query"=> urlencode("(sys ssg3.2.8.?)"),       "Parent"=>"3.2." ),
"3.2.8.2."       => array("de" => "Usbekistan",                                                                           "en" => "Uzbekistan",                                                                                   "Query"=> urlencode("(sys ssg3.2.8.2.?)"),     "Parent"=>"3.2.8."),
"3.2.8.3."       => array("de" => "Turkmenistan",                                                                         "en" => "Turkmenistan",                                                                                 "Query"=> urlencode("(sys ssg3.2.8.3.?)"),     "Parent"=>"3.2.8."),
"3.2.8.4."       => array("de" => "Kasachstan",                                                                           "en" => "Kazakhstan",                                                                                   "Query"=> urlencode("(sys ssg3.2.8.4.?)"),     "Parent"=>"3.2.8."),
"3.2.8.5."       => array("de" => "Kirgisien",                                                                            "en" => "Kyrgyzstan",                                                                                   "Query"=> urlencode("(sys ssg3.2.8.5.?)"),     "Parent"=>"3.2.8."),


// -- ergänzt ende  --
"4." 	   =>	array("de" => "SPRACHEN und LITERATUREN",                                                            "en" => "LANGUAGES and LITERATURES",                                                         "Query"=> urlencode("(sys ssg4.?)"),                "Parent" => "" ),
"4.1." 	   =>	array("de" => "Arabisch, Arabische Länder (inkl. Berber, Kopten...)",                                "en" => "Arabic Language and Literature",                                                    "Query"=> urlencode("(sys ssg4.1?)"),               "Parent" => "4." ),
"4.2." 	   =>	array("de" => "Horn von Afrika",                                                                     "en" => "Languages and Literatures at the Horn of Africa",                                   "Query"=> urlencode("(sys ssg4.2?)"),               "Parent" => "4." ),
"4.3." 	   =>	array("de" => "Semitische Sprachen außer Arabisch",                                                  "en" => "Other Semitic Languages and Literatures",                                           "Query"=> urlencode("(sys ssg4.3?)"),               "Parent" => "4." ),
"4.4." 	   =>	array("de" => "Iranische Sprachen und Literaturen",                                                  "en" => "Languages and Literatures of Iran, Afghanistan and other Iranian Peoples",          "Query"=> urlencode("(sys ssg4.4?)"),               "Parent" => "4." ),
"4.5." 	   =>	array("de" => "Türkische Sprachen und Literaturen",                                                  "en" => "Turkish and Turkic Languages and Literatures",                                      "Query"=> urlencode("(sys ssg4.5?)"),               "Parent" => "4." ),
"4.6." 	   =>	array("de" => "Kaukasische Sprachen und Literaturen",                                                "en" => "Caucasian Languages and Literatures",                                               "Query"=> urlencode("(sys ssg4.6?)"),               "Parent" => "4." ),
"4.7." 	   =>	array("de" => "Armenische Sprache und Literatur",                                                    "en" => "Armenian Language and Literature",                                                  "Query"=> urlencode("(sys ssg4.7?)"),               "Parent" => "4." )
 );
// ============================ rewrite table hirachical  =======================================

function Tree_Append(&$root, $nodekey, $node){

  $found= false;
  if (!isset($root['Children'])) $root['Children']= array();

 foreach($root["Children"] as $key => $value){
    if($key == $node["Parent"]) {
                    if (!isset($root['Children'][$key]['Children'])) $root['Children'][$key]['Children'] = array($nodekey => $node);
                                                               else  $root['Children'][$key]['Children']+= array($nodekey => $node);
                    $found=true;
                    }
                else{
                     $found= $found || Tree_Append($root["Children"][$key], $nodekey, $node);
                    }

    if ($found===true)  return $found; // abbruch
 }
 return $found;
}

// =========== INIT ===========

$content2=array("Children" => array());
foreach($content as $key=> $entry){
  if ($entry["Parent"]=="")  $content2["Children"]+= array($key => $entry);
}

// ======== Built Tree ========

foreach($content as $key=> $entry){
  Tree_Append($content2, $key, $entry);
}

?>