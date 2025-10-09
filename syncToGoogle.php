<?php
function syncToGoogleSheet($data) {
    $url = "https://script.google.com/macros/s/AKfycby6nlEtQMVmgos0Z6LRSWrTU3_IQ8xOdZZ04fxcqfli4LPZ-9u9xAHbxWJZZ492xzu5/exec"; // your script URL

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
