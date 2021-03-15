<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendMail($mailto, $title, $content){
    try {
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output SMTP::DEBUG_SERVER
        $mail->isSMTP();                           //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';      //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                  //Enable SMTP authentication
        $mail->Username   = 'phuongfado@gmail.com';//SMTP username
        $mail->Password   = '123321@asd!';         //SMTP password
        $mail->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;//Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                   //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //Recipients
        $mail->setFrom('phuongfado@gmail.com', 'Nguyễn Hoàng Phương');
        $mail->addAddress($mailto);     //Add a recipient
        $mail->addReplyTo('phuongfado@gmail.com', 'Nguyễn Hoàng Phương');
    
        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->CharSet = "utf-8";
        $mail->Subject = $title;
        $mail->Body    = $content;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

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

ini_set('max_execution_time', '0');
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

    $redis->rpush("key_mail", json_encode($data));

} catch (Exception $e) {
    echo $e->getMessage();
}

while (true) {
    if(!empty($value = $redis->lpop("key_mail"))){
        $value  = json_decode($value, true);
        if(sendMail($value['mail'], $value['title'], $value['content'])){
            reponse(true, 200, "Gửi mail thành công");
        }
        $redis->delete("key_mail");
    }
}