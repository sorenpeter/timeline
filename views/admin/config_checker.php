<?php

function validateConfig($configFile, $templateFile) {
    $config = parse_ini_file($configFile);
    $template = parse_ini_file($templateFile);

    $configKeys = array_keys($config);
    $templateKeys = array_keys($template);

    $missingKeys = array_diff($templateKeys, $configKeys);
    $extraKeys = array_diff($configKeys, $templateKeys);

    $configIsCorrect = true;
    if (!empty($missingKeys)) {
        echo "Missing keys: " . implode(', ', $missingKeys) . "\n";
        $configIsCorrect = false;
    }

    if (!empty($extraKeys)) {
        echo "Extra keys: " . implode(', ', $extraKeys) . "\n";
        $configIsCorrect = false;
    }
    
    if ($configIsCorrect) {
        echo "Config file $configFile looks OK.\n";
    }
}

$configFile = 'private/config.ini';
$templateFile = 'private/config_template.ini';

if (!file_exists($configFile)) {
    echo "Config file $configFile not found.\n";
    exit;
}

if (!file_exists($templateFile)) {
    echo "Template file $templateFile not found.\n";
    exit;
}

validateConfig($configFile, $templateFile);