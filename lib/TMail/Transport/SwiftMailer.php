<?php
class TMail_Transport_SwiftMailer extends AbstractObject {

	public $mailer=null;

	function init(){
		parent::init();
		require_once('lib/Swift/swift_init.php');

    	$email_settings = $this->api->current_website;

    	switch ($email_settings['email_transport']) {
			case 'SmtpTransport':
				$transport = Swift_SmtpTransport::newInstance($email_settings['email_host'],$email_settings['email_port'],$email_settings['encryption']!='none'?$email_settings['encryption']:null);
				$transport->setUsername($email_settings['email_username']);
				$transport->setPassword($email_settings['email_password']);
				break;
			case 'SendmailTransport':
				$transport = Swift_SendmailTransport::newInstance();
				break;
			case 'MailTransport':
				$transport = Swift_MailTransport::newInstance();
				break;
			
			default:
				# code...
				break;
		}

		$this->mailer = Swift_Mailer::newInstance($transport);
	}
	    
    function send($to, $from, $subject, $body, $headers="",$ccs=array(), $bcc=array(),  $skip_inlining_images=false, $read_confirmation_to=''){
		$email_settings = $this->api->current_website;
    	
    	
		// $logger = new Swift_Plugins_Loggers_EchoLogger();
		// $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

    	$mailer = $this->mailer;

		$message = Swift_Message::newInstance($subject)
		  ->setFrom(array($email_settings['from_email'] => $email_settings['from_name']))
		  ->setReplyTo(array($email_settings['email_reply_to'] => $email_settings['email_reply_to_name']))
		  ;

		if($email_settings['sender_email'])
			$message->setSender(array($email_settings['sender_email']=>$email_settings['sender_name']));

		if($email_settings['return_path'])
			$message->setReturnPath($email_settings['return_path']);

		$email_body = $body;
		$email_body = str_replace("{{email}}", is_array($to)?$to[0]:$to, $email_body);
		$email_body = $this->convertImagesInline($message,$email_body);
		$message->setBody($email_body,'text/html');

		if(is_array($to)){
            foreach ($to as $to_1) {
                $message->addTo($to_1);
            }
        }else{
            $message->addTo($to);
        }

        if($ccs){
            if(is_array($ccs)){
                foreach ($ccs as $ccs_1) {
                    $message->addCc($ccs_1);
                }
            }else{
                $message->addCc($ccs);
            }
        }

        if($bcc){
            if(is_array($bcc)){
                foreach ($bcc as $bcc_1) {
                    $message->addBcc($bcc_1);
                }
            }else{
                $message->addBcc($bcc);
            }
        }

		$failed=array();
		$sent_this =  $mailer->send($message, $failed);

		if(!$sent_this){
			return false;	
		} 

		if(strtotime(date('Y-m-d H:i:0',strtotime($email_settings['last_engaged_at']))) == strtotime(date('Y-m-d H:i:0',strtotime(date('Y-m-d H:i:s'))))){
			$email_settings['email_sent_in_this_minute'] = $email_settings['email_sent_in_this_minute'] + 1;
		}else{
			$email_settings['email_sent_in_this_minute'] = 1;
		}

		$email_settings['last_engaged_at'] = date('Y-m-d H:i:s');
		$email_settings->save();

		return true;

    }

    function convertImagesInline(&$message, &$body){
        // get all img tags
        preg_match_all('/<img.*?>/', $body, $matches);
        if (!isset($matches[0])) return;
        // foreach tag, create the cid and embed image
        foreach ($matches[0] as $img)
        {
            // make cid
            // $id = 'img'.($i++);
            // replace image web path with local path
            preg_match('/src="(.*?)"/', $img, $m);
            if (!isset($m[1])) continue;
            $arr = parse_url($m[1]);
            if (isset($arr['host'])) continue;
            // add
            $cid = $message->embed(Swift_Image::fromPath(getcwd().'/'.$arr['path']));
            $body = str_replace($img, '<img alt="" src="'.$cid.'" style="border: none;" />', $body); 
        }
        return $body;
    }
}