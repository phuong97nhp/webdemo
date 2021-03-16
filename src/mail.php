<?php

function reponse($success = true, $code = 200, $messenger = "", $data = null,  $error = null){
    $reponse = [
        'success' => $success,
        'code' => $code,
        'messenger' => $messenger ,
        'data' => $data,
        'error' => $error
    ];
    echo json_encode($reponse);
    die();
}

try {
    $redis = new Redis();
    $redis->connect('redis', 6379);
    $value = json_decode(file_get_contents('php://input'), 1);

    if($value['mail'] === "" || !filter_var($value['mail'] , FILTER_VALIDATE_EMAIL)){
        reponse(false, 405, "Mail không hợp lệ");
    }

    if(!$value['title']){
        reponse(false, 405, "Nhập vào tiêu đề");
    }

    if(!$value['content']){
        reponse(false, 405, "Nhập vào nội dung gửi đi");
    }

    $data = [];
    $data = [
            'mail'     => $value['mail'],
            'title'    => $value['title'],
            'content' => $value['content']
            ];

    if($redis->rpush("key_mail", json_encode($data))){
        reponse(true, 405, "Cập nhật nội dung gửi đi thành công");
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
