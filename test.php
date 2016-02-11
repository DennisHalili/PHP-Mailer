<?php //-->

require_once __DIR__.DIRECTORY_SEPARATOR.'Mailer.php';

use \Mailer\Mailer as Mailer;

class SendEmail
{

	public $config = [
			'smtp_debug'    => 0,
		    'debug_output'  => 'html',
		    'host'          => 'mail.domain.com',
		    'port'          => '587',
		    'smtp_secure'   => 'tls',
		    'smtp_auth'     => true,
		    'user_name'     => 'no_reply@domain.com',
		    'password'      => 'password-here',
		    'from'          => 'no_reply@domain.com',
		    'from_name'     => 'Juan Dela Cruz Company',
		    'reply'         => 'no_reply@domain.com',
		    'reply_name'    => 'Juan Dela Cruz Company',
		    'alt_body'      => null,
		];
	public $emailAdmin    = [
        'to'         => [ 'juan.delacruz@domain.com' , 'Email name' ],        
        'cc'         => '', //['mariaclara.delacruz@domain.com', 'crisostomo.ibarra@domain.com'],
        'bcc'        => '', //['padre.damaso@domain.com'],
        'smtp_debug' => false,
    ];

    public function sendWithoutTemplate()
    {
    	//Email Subject
		$subject = 'Sample Subject Line';

		//HTML CODE or Simple plain Message for body of email
		$content = 'Body of the message';

		//Send email
		$emailContent = [
		    'subject'       => $subject,
		    'content'       => $content,
		];

		//Merge the Email list and body of the email with subject
		$options = array_merge( $this->emailAdmin, $emailContent );

		//SEND EMAIL
		$emailSend = Mailer::sendEmail( $this->config, $options  ); // 1st param is configuration, 2nd param is options

		return $emailSend;
    }

    public function sendWithTemplate()
    {
    	$inquiryTpl = file_get_contents( __DIR__.DIRECTORY_SEPARATOR.'email-template/inquiry.tpl' );

        $emailSubject = ucwords( 'juan dela cruz' );

        $dataInput = [
        	'inquiry_full_name'     => 'Juan Dela Cruz',
        	'inquiry_phone_number'  => '09*********',
        	'inquiry_email_address' => 'sample.email@domain.com',
        	'inquiry_message'       => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
        ];

        $inquiry = array_merge( $dataInput, [
            'inquiry_created' => date('Y-m-d H:i:s'),
            'inquiry_subject' => $emailSubject,
        ]);
 
        foreach ($inquiry as $key => $value) {
            if ( is_array( $value ) ) {
                continue;
            }

            $inquiryTpl = str_replace( '{'.$key.'}', $value, $inquiryTpl );
        }

        //Send email
		$emailContent = [
		    'subject'       => $emailSubject,
		    'content'       => $inquiryTpl,
		];
		
		//Merge the Email list and body of the email with subject
		$options = array_merge( $this->emailAdmin, $emailContent );

		//SEND EMAIL
		$emailSend = Mailer::sendEmail( $this->config, $options  ); // 1st param is configuration, 2nd param is options

		return $emailSend;
    }

}

$obj = new SendEmail;

var_dump( $obj->sendWithTemplate() );

exit();