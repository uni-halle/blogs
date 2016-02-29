<?php
/*
Plugin Name: Wordpress Simpleform
Plugin URI: http://www.3dbits.de
Description: Einfache Formularabwicklung
Version: 0.0.1
Author: Ole Trenner
Author URI: http://www.3dbits.de
License: custom
*/

define("WPSF_VERSION", "0.0.2");

define("WPSF_BASE", basename(dirname(__FILE__)));
define("WPSF_URL", WP_PLUGIN_URL . "/" . WPSF_BASE);
define("WPSF_PATH", WP_PLUGIN_DIR . "/" . WPSF_BASE);


//autoloading all includes
foreach (glob(WPSF_PATH . "/inc/*.php") as $filename) {
    require_once($filename);
}



class WPSimpleform {
    public function __construct() {
        if (function_exists('add_action')) {
            add_shortcode('wp_simpleform', array($this, 'render_form'));
            add_action('init', array($this, 'handle_submission'));
        }
    }

    public function render_form($attrs) {
        $attrs = shortcode_atts(array("id"=>"1"), $attrs, "wp_simpleform");
        $formoptions = apply_filters('wpsf_formoptions', $this->default_options(), $attrs["id"]);
        $formfields = isset($formoptions["formfields"]) ? $formoptions["formfields"] : $this->default_formfields();
        $formclass = $formoptions["formclass"];
        $errorclass = $formoptions["errorclass"];
        $messageclass = $formoptions["messageclass"];

        if ($this->is_submission()) {
            $errors = $this->validate($formfields);
            $success = false;
        } else {
            $errors = false;
            $success = isset($_GET["wpsf_success"]) && $_GET["wpsf_success"] == "true";
        }

        ?>
        <form class='<?php echo $formclass; ?>' method='post'>
            <?php echo $success ? "<div class='$messageclass'>" . $formoptions["successmessage"] . "</div>" : ""; ?>
            <?php foreach ($formfields as $field): ?>
                <div class='formrow <?php echo $field["type"]; ?> <?php echo $this->has_error($errors, $field) ? $errorclass : ''; ?>'>
                    <?php
                    $this->render_field($field);
                    if ($this->has_error($errors, $field)) {
                        echo "<div class='$messageclass'>" . $errors[$field["name"]] . "</div>";
                    }
                    ?>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="wp_simpleform_submit" value="true">
            <?php wp_nonce_field('wp_simpleform', '_wpnonce', true); ?>
        </form>
        <?php
    }

    public function render_field($field) {
        $labelclass = (isset($field["required"]) && $field["required"]) ? "required" : "";
        $value = isset($_POST[$field["name"]]) ? $_POST[$field["name"]] : "";
        if (isset($field["type"]) && $field["type"] === "textarea") {
            echo "<label class='$labelclass'>$field[label]</label>";
            echo "<textarea class='text' name='$field[name]'>$value</textarea>";
        } else if (isset($field["type"]) && $field["type"] === "captcha") {
            $captcha = $this->captcha();
            echo "<label class='$labelclass'>$field[label] $captcha[label]</label>";
            echo "<input name='wpsf_captchaid' type='hidden' value='$captcha[id]'>";
            echo "<input class='text' name='$field[name]' type='text'>";
        } else if (isset($field["type"]) && $field["type"] === "hidden") {
            echo "<input type='hidden' name='$field[name]' value='$field[value]'>";
        } else if (isset($field["type"]) && $field["type"] === "submit") {
            echo "<button class='main' type='submit'>$field[label]</button>";
        } else {
            echo "<label class='$labelclass'>$field[label]</label>";
            echo "<input type='text' class='text' name='$field[name]' value='$value'>";
        }
    }

    public function has_error($errors, $field) {
        return is_array($errors) && isset($field["name"]) && isset($errors[$field["name"]]);
    }

    public function captcha($id=null) {
        $captchas = array(
            array("id"=>"1", "label"=>"2 + 3 =", "result"=>"5"),
            array("id"=>"2", "label"=>"1 + 3 =", "result"=>"4"),
            array("id"=>"3", "label"=>"3 + 3 =", "result"=>"6"),
            array("id"=>"4", "label"=>"1 + 2 =", "result"=>"3"),
        );
        if ($id == null) {
            return $captchas[array_rand($captchas)];
        } else {
            foreach ($captchas as $captcha) {
                if ($captcha["id"] == $id) {
                    return $captcha;
                }
            }
        }
        return false;
    }

    public function is_submission() {
        return $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['wp_simpleform_submit']);
    }

    public function handle_submission() {
        $formoptions = apply_filters('wpsf_formoptions', $this->default_options());
        $formfields = isset($formoptions["formfields"]) ? $formoptions["formfields"] : $this->default_formfields();

        if ($this->is_submission()) {
            if (!wp_verify_nonce($_POST['_wpnonce'], 'wp_simpleform')) {
                wp_die("Invalid nonce.");
                exit;
            }
            $errors = $this->validate($formfields);
            if (empty($errors)) {
                //send mail or whatever
                do_action('wpsf_success');
                $this->redirect_to_referer();
            }
        }
    }

    public function validate($formfields) {
        $errors = array();
        foreach ($formfields as $field) {
            if (!isset($field["name"])) {
                continue;
            }
            $name = $field["name"];
            $value = isset($_POST[$name]) ? $_POST[$name] : null;
            $msg = isset($field["errormessage"]) ? $field["errormessage"] : "Invalid";
            if ($field["required"] && empty($value)) {
                $errors[$name] = $msg;
            } elseif ($field["type"] == "email" && !is_email($value)) {
                $errors[$name] = $msg;
            } else if ($field["type"] == "captcha") {
                $id = isset($_POST["wpsf_captchaid"]) ? $_POST["wpsf_captchaid"] : "";
                $captcha = $this->captcha($id);
                if ($value != $captcha["result"]) {
                    $errors[$name] = $msg;
                }
            }
        }
        return $errors;
    }



    protected function default_options() {
        return array(
            "formfields" => $this->default_formfields(),
            "successmessage" => "Das Formular wurde erfolgreich abgesendet",
            "formclass" => "wpsf_form",
            "errorclass" => 'wpsf_invalid',
            "messageclass" => 'wpsf_message',
        );
    }

    protected function default_formfields() {
        return array(
            array(
                "label" => "Name",
                "type" => "text",
                "name" => "wpsf_name",
                "required" => true,
                "errormessage" => "Bitte geben Sie Ihren Namen ein.",
            ),
            array(
                "label" => "E-Mail-Adresse",
                "type" => "email",
                "name" => "wpsf_email",
                "required" => true,
                "errormessage" => "Bitte geben Sie eine E-Mail-Adresse ein.",
            ),
            array(
                "label" => "Betreff",
                "type" => "text",
                "name" => "wpsf_subject",
            ),
            array(
                "label" => "Nachricht",
                "type" => "textarea",
                "name" => "wpsf_message",
                "required" => true,
                "errormessage" => "Bitte geben Sie eine Nachricht ein.",
            ),
            array(
                "label" => "Spamschutz",
                "type" => "captcha",
                "name" => "wpsf_captcha",
                "required" => true,
                "errormessage" => "Bitte geben Sie die Lösung ein.",
            ),
            array(
                "label" => "Absenden",
                "type" => "submit",
            ),

        );
    }

    protected function redirect_to_referer() {
        $param = "wpsf_success=true";
        $referer = $_POST['_wp_http_referer'];
        if (strpos($referer, $param) === false) {
            $referer .= strpos($referer, "?") === false ? "?" : "&";
            $referer .= $param;
        }
        wp_redirect($referer);
        exit;
    }
}


$wpsf = new WPSimpleform();

