
<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">

<style type="text/css">
    input[type="text"] {
  width: 100%;}
</style>


<?php

// https://www.w3schools.io/ini-read-write-php/

$config = parse_ini_file("config.ini");

$configWithSections = parse_ini_file("config.ini",true,INI_SCANNER_RAW);

if ($config !== false) {

?>    

<h1>Settings for Timeline</h1>

<form>

    <fieldset>
        <legend>Advanced</legend>

     <p>
        <label for="debug_mode">Enable to display PHP errors</label>
        <input type="text" id="debug_mode" name="debug_mode" value="">
    </p>

     <p>
        <label for="cache_refresh_time">Time to wait before reloading URLs</label>
        <input type="text" id="cache_refresh_time" name="cache_refresh_time" value="15">
    </p>
    <p>
        <label for="max_execution_time">Max execution time to avoid running to the infinite</label>
        <input type="text" id="max_execution_time" name="max_execution_time" value="300">
    </p>

  </fieldset>        

<fieldset>
    <legend>Metadata</legend>
            
    <p>
        <label for="site_title">Title of your website</label>
        <input type="text" id="site_title" name="site_title" value="Timeline">
    </p>
    <p>
        <label for="txt_file_path">Local path to your twtxt.txt</label>
        <input type="text" id="txt_file_path" name="txt_file_path" value="twtxt.txt">
    </p>

    <p>
        <em>TODO: The following should be pulled from your twtxt.txt</em><br>
        <em>TODO: or should it be saved totwtxt.txt?</em>
    </p>
    <p>
        <label for="public_txt_url">Public URL for your twtxt.txt</label>
        <input type="text" id="public_txt_url" name="public_txt_url" value="https://example.com/timeline/twtxt.txt">
    </p>
    <p>
        <label for="public_avatar">Public URL for your avatar image</label>
        <input type="text" id="public_avatar" name="public_avatar" value="https://example.com/timeline/avatar.png">
    </p>
    <p>
        <label for="public_nick">Your nickname</label>
        <input type="text" id="public_nick" name="public_nick" value="yarner">
    </p>
</fieldset>


<fieldset>
    <legend>Interface</legend>
    <p>
    </p>
    <p>
        <label for="timezone">Timezone</label>
        <!-- <input type="text" id="timezone" name="timezone" value="Europe/Copenhagen"> -->
        <?php include "timezone.php"; ?>
    </p>
    <p>
        <label for="twts_per_page">Posts per page</label>
        <input type="text" id="twts_per_page" name="twts_per_page" value="50">
    </p>
</fieldset>

<fieldset>
    <legend>Integrations</legend>
    <p>
        <label for="webmentions_txt_path">Webmentions - Local path to txt</label>
        <input type="text" id="webmentions_txt_path" name="webmentions_txt_path" value="./mentions.txt">
    </p>
    <p>
        <label for="public_webmentions">Webmentions - URL to txt</label>
        <input type="text" id="public_webmentions" name="public_webmentions" value="https://example.com/timeline/mentions.txt">
    </p>

    <p>
        <label for="email">E-mail for "Reply via email" feature (leave blank to deactive)</label>
        <input type="text" id="email" name="email" value="">
    </p>

</fieldset>

<fieldset>
    <legend>Login</legend>

    <p>
        <label for="totp_digits">TOTP - Number of digits</label>
        <input type="text" id="totp_digits" name="totp_digits" value="10">
    </p>
    <p>
        <label for="totp_secret">TOTP - Secret:</label>
        <input type="text" id="totp_secret" name="totp_secret" value="1234567890">
    </p>
    <p>
        <label for="secure_cookies">Secure Cookies</label>
        <!-- <input type="text" id="secure_cookies" name="secure_cookies" value="1"> -->
        <input type="checkbox" id="secure_cookies" name="secure_cookies" value="1">
        It's recommended that your site is hosted on HTTPS.
        In case it's in HTTP (not secure), turn this off

    </p>
    <p>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" value="">
    </p>
    <input type="submit" value="Save">

</fieldset>

</form>



<?php } 


print_r("<pre>");
print_r($config);
print_r($configWithSections);
print_r("</pre>");



?>
