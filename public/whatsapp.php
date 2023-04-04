<?php


function send($message){
    $url = 'https://api.ultramsg.com/instance41546/messages/chat';
    $data = array(
        'token' => "zshfdy3mb07hg8uf",
        'to' => "+201007415843",
        'body' => $message,
        'priority' => 1,
        'referenceId' => '',
        'msgId' => '',
        'mentions' => '',
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        return redirect()->back()->with('error', 'حدث خطأ ما في ارسال رسالة تأكيد للعميل');
    }
}
