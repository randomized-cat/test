<?php
// توکن ربات تلگرام شما
$token = '6158917854:AAFyX5_AsJvik9OYEm67y2a9HeDZcLMiv7Y';

// آدرس API تلگرام
$apiUrl = 'https://api.telegram.org/bot' . $token . '/';

// دریافت محتوای درخواست از تلگرام
$update = json_decode(file_get_contents('php://input'), true);

// دریافت شناسه کاربری و پیام ارسال شده توسط کاربر
$user_id = $update['message']['from']['id'];
$message = $update['message']['text'];

// ارسال پیام به کاربر
function sendMessage($chat_id, $message, $reply_markup = null) {
    global $apiUrl;
    $params = array('chat_id' => $chat_id, 'text' => $message);
    if ($reply_markup) {
        $params['reply_markup'] = json_encode($reply_markup);
    }
    file_get_contents($apiUrl . 'sendMessage?' . http_build_query($params));
}

// دریافت لینک ویدیوها
function getVideos($username) {
    $url = 'https://www.pornhub.com/model/' . $username;
    $html = file_get_contents($url);
    preg_match_all('/<a class="title" href="(.*?)"/', $html, $matches);
    $videos = array_slice($matches[1], 0, 5);
    return $videos;
}

// دانلود ویدیو و آپلود آن در تلگرام
function downloadVideo($url) {
    // این قسمت برای دانلود ویدیو و آپلود آن در تلگرام است
}

// شروع ربات تلگرام
if ($message == '/start') {
    sendMessage($user_id, '{bruh}');
}
// درخواست نام کاربری
else if (strpos($message, '/username') === 0) {
    $username = substr($message, 9);
    $videos = getVideos($username);
    $text = "1- متن اول\n2- متن دوم\n";
    $reply_markup = array(
        'keyboard' => array(
            array('1', '2'),
            array('3', '4'),
            array('5', 'بیشتر')
        ),
        'resize_keyboard' => true
    );
    sendMessage($user_id, $text, $reply_markup);
}
// انتخاب ویدیو توسط کاربر
else if (in_array($message, array('1', '2', '3', '4', '5'))) {
    $username = substr($message, 9);
    $videos = getVideos($username);
    $video_index = intval($message) - 1;
    $video_url = $videos[$video_index];
    downloadVideo($video_url);
}
// نمایش ویدیوهای بعدی
else if ($message == 'بیشتر') {
    $username = substr($message, 9);
    $videos = getVideos($username);
    $text = "";
    for ($i = 0; $i < 5; $i++) {
        $index = $i + 6;
        $text .= $index . "- متن ویدیوی " . $index . "\n";
    }
    $reply_markup = array(
        'keyboard' => array(
            array('6', '7'),
            array('8', '9'),
            array('10', 'بیشتر')
        ),
        'resize_keyboard' => true
    );
    sendMessage($user_id, $text, $reply_markup);
}
?>
