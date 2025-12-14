<?php
// public/test_api.php

// ⚠️ PASTE YOUR KEY FROM SCREENSHOT 3 (ending in ...tGVg) HERE
$apiKey = 'AIzaSyDjMpeDsF2hK778_5ylLSCuGtkv-wfnPnE'; 

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for Localhost

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Curl Error: ' . curl_error($ch);
} else {
    echo "<h1>Google's Response:</h1>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}
curl_close($ch);
?>