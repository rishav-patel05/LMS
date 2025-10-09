<?php
function syncToGoogleSheet($data) {
    $url = "https://script.google.com/macros/s/AKfycbz2_n14yd8PKosnc50kFFkt5PP_Qx8mYv_yAge4Rf5gTWq0FmhhEXO4g1eKSDAKOPua/exec"; // your script URL

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 10
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        error_log("❌ Failed to sync with Google Sheets");
    } else {
        error_log("✅ Synced successfully: " . $result);
    }
}
?>
