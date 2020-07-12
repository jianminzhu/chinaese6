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
function sendMail( $toEmail, $subject, $body)
{
    $userName = '442469884@qq.com';
    $password = '？？？？？？';
    $fromEmail = $userName;
    // 实例化PHPMailer核心类
    $mail = new PHPMailer();
// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 1;
// 使用smtp鉴权方式发送邮件
    $mail->isSMTP();
// smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;
// 链接qq域名邮箱的服务器地址
    $mail->Host = 'smtp.qq.com';
// 设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';
// 设置ssl连接smtp服务器的远程服务器端口号
    $mail->Port = 465;
// 设置发送的邮件的编码
    $mail->CharSet = 'UTF-8';
// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '';
// smtp登录的账号 QQ邮箱即可
    $mail->Username = $userName;
// smtp登录的密码 使用生成的授权码
    $mail->Password = $password;
// 设置发件人邮箱地址 同登录账号
    $mail->From = $fromEmail;
// 邮件正文是否为html编码 注意此处是一个方法
    $mail->isHTML(true);
    $mail->addAddress($toEmail);
// 添加该邮件的主题
    $mail->Subject = $subject;
// 添加邮件正文
    $mail->Body = $body;
// 发送邮件 返回状态
    $status = $mail->send();
    return $status;
}

$toEmail = '442469884@qq.com';
$body = '<h1>q1test</h1>';
$subject = 'q1test';
$status = sendMail( $toEmail, $subject, $body);
echo $status;
