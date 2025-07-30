<?php
namespace app\util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    // 生成 HTML 模板
    protected static function generateTemplate($code): string
    {
        return "
        <html>
        <body>
            <p>欢迎使用 <strong>BangCalendar</strong>，登录后可以备份您的应用偏好。</p>
            <p>您的验证码为：</p>
            <p><strong style='font-size: 20px; color: blue;'>$code</strong></p>
            <p>请在 5 分钟内完成验证。</p>
            <p>本邮件由系统自动发送，请勿回复。</p>
            <br/>
            <p style='text-align: right;'>—— BangCalendar 项目组</p>
        </body>
        </html>
        ";
    }

    // 发送验证码邮件函数
    public static function sendVerificationCode(int $code, string $email): ?int
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP 配置
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');           // SMTP 服务器
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USER');     // 邮箱
            $mail->Password   = env('MAIL_PASS');        // 授权码
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // 可用：ssl / tls
            $mail->Port       = 465;

            // 邮件头
            $mail->setFrom(env('MAIL_USER'), 'BangCalendar 项目组');
            $mail->addAddress($email);
            $mail->isHTML(true);

            // 邮件主题（包含当前时间）
            $time = date("Y-m-d H:i:s");
            $mail->Subject = "【BangCalendar】登录验证码 $time";

            // 邮件内容
            $mail->Body    = self::generateTemplate($code);
            $mail->AltBody = "欢迎使用 BangCalendar，登录后可以备份您的应用偏好。您的验证码为：${code}，请在 5 分钟内完成验证。本邮件由系统自动发送，请勿回复。—— BangCalendar 项目组";

            $mail->send();
            return $code;
        } catch (Exception $e) {
            return null;
        }
    }
}