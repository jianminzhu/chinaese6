<?php
require("class.phpmailer.php"); //下载的文件必须放在该文件所在目录
require("class.smtp.php"); //下载的文件必须放在该文件所在目录


/**
 * @param $toEmail
 * @param $subject
 * @param $body
 * @return bool
 * @throws phpmailerException
 */
function sendMail($toEmail, $subject, $body)
{
    $test = [
        "gmail" => [
            'host' => "smtp.gmail.com",
            'username' => 'zhujianmin2008@gmail.com',
            'password' => '',
            'port' => 465,
            'SMTPSecure' => 'ssl',
        ], "qq" => [
            'host' => "smtp.qq.com",
            'username' => '442469884@qq.com',
            'password' => '',
            'port' => 465,
            'SMTPSecure' => 'ssl',
        ], "cc" => [
            'host' => "mail.chinesecompanion.com",
            'username' => 'support@chinesecompanion.com',
            'password' => 'happy3000ok',
            'port' => 465,
            'SMTPSecure' => 'ssl',
        ]
    ];
    $it = "qq";
    $config = $test[$it];
    $host = $config['host'];
    $username = $config['username'];
    $password = $config['password'];
    $port = $config['port'];
    $SMTPSecure = $config['SMTPSecure'];

    echo join("\n",
        [
              $it
            , $host
            , $username
            , $password
            , $port
            , $SMTPSecure
        ]);

    $fromEmail = $username;
    $mail = new PHPMailer();
    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = $host;
    $mail->SMTPSecure = $SMTPSecure;
    $mail->Port = $port;
    $mail->CharSet = 'UTF-8';
    $mail->FromName = '';
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->From = $fromEmail;
    $mail->isHTML(true);
    $mail->addAddress($toEmail);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $status = $mail->send();
    return $status;
}

$toEmail = '442469884@qq.com';
$body = '<h1>q1test</h1>';
$subject = 'q1test';
$status = sendMail($toEmail, $subject, $body);
echo $status;
