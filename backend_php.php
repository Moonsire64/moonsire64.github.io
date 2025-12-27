<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

function checkChaturbate($username) {
    $url = "https://chaturbate.com/api/public/affiliates/onlinerooms/?wm=E9pVl&username=" . urlencode($username);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        return isset($data['results']) && count($data['results']) > 0;
    }
    
    return false;
}

function checkStripchat($username) {
    $url = "https://stripchat.com/api/front/models/username/" . urlencode($username) . "/profile";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        return isset($data['user']['isCamAvailable']) && $data['user']['isCamAvailable'] === true;
    }
    
    return false;
}

// Main execution
$chaturbateUsername = 'moonsire';
$stripchatUsername = 'Moonsire1';

$result = array(
    'chaturbate' => array(
        'username' => $chaturbateUsername,
        'isLive' => checkChaturbate($chaturbateUsername)
    ),
    'stripchat' => array(
        'username' => $stripchatUsername,
        'isLive' => checkStripchat($stripchatUsername)
    ),
    'timestamp' => time(),
    'checked_at' => date('Y-m-d H:i:s')
);

echo json_encode($result);
?>
