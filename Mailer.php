<?php //-->
namespace Mailer;

require_once __DIR__.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'PHPMailerAutoload.php';

class Mailer { 
    /*
        * DEFAULT SENDER METHOD

    */

    public static function sendEmail( $options, $config ) {  

        $config = array_merge( $config, $options );
        
        //Create a new PHPMailer instance
        $mail = new \PHPMailer(); //$mail = new PHPMailer();
        
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = $config['smtp_debug'];
        
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = $config['debug_output']; 
        
        //Set the hostname of the mail server
        $mail->Host = $config['host'];
        
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = $config['port'];
        
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = $config['smtp_secure'];
        
        //Whether to use SMTP authentication
        $mail->SMTPAuth = $config['smtp_auth'];
        
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = $config['user_name'];
        
        //Password to use for SMTP authentication
        $mail->Password = $config['password'];
        
        //Set who the message is to be sent from
        $mail->setFrom( $config['from'], $config['from_name'] );
        
        //Set an alternative reply-to address
        $mail->addReplyTo( $config['reply'], $config['reply_name'] );
        
        //Set who the message is to be sent to
        $mail->addAddress( $config['to'][0], $config['to'][1] );

        // if ( isset( $config['cc'] ) ) {
        //     foreach ( $config['cc'] as $cc ) {
        //         $mail->AddCC( $cc[0], $cc[1] );
        //     }
        // }

        // if ( isset( $config['bcc'] ) ) {
        //     foreach ( $config['bcc'] as $bcc ) {
        //         $mail->AddBCC( $bcc[0], $bcc[1] );
        //     }
        // }

        //Set Carbon Copy
        if ( isset( $config['cc'] ) && !empty( $config['cc'] ) ) {
            foreach ( $config['cc'] as $address ) {
                $email = trim( $address );

                if ( empty( $address ) ) {
                    continue;
                }

                $mail->AddCC( $address );
            }
        }

        //Set Blind Carbon Copy
        if ( isset( $config['bcc'] ) && !empty( $config['bcc'] ) ) {
            foreach ( $config['bcc'] as $address ) {
                $email = trim( $address );

                if ( empty( $address ) ) {
                    continue;
                }

                $mail->AddBCC( $address );
            }
        }
        
        //Set the subject line
        $mail->Subject = $config['subject'];
        
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        $mail->msgHTML( $config['content'] );
        
        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        
        //send the message 
        return $mail->send();
    }

}



