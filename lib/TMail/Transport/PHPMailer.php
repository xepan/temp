<?php
class TMail_Transport_PHPMailer extends TMail_Transport_SwiftMailer {
    /*public $PHPMailer = null;
    
    function init(){
        parent::init();

        require_once("PHPMailer/class.phpmailer.php");
        $this->PHPMailer = $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuthSecure = 'ssl';
        $mail->AltBody = null;
        $mail->IsHTML(true);
        $mail->SMTPKeepAlive = true;
    }

    function send($to, $from, $subject, $body, $headers="",$ccs=array(), $bcc=array(),  $skip_inlining_images=false, $read_confirmation_to=''){
        $mail = $this->PHPMailer;
        
        $mail->ClearAllRecipients();
        
        if(is_array($to)){
            foreach ($to as $to_1) {
                $mail->AddAddress($to_1);
            }
        }else{
            $mail->AddAddress($to);
        }

        if($ccs){
            if(is_array($ccs)){
                foreach ($ccs as $ccs_1) {
                    $mail->AddCC($ccs_1);
                }
            }else{
                $mail->AddCC($ccs);
            }
        }

        if($bcc){
            if(is_array($bcc)){
                foreach ($bcc as $bcc_1) {
                    $mail->AddBCC($bcc_1);
                }
            }else{
                $mail->AddBCC($bcc);
            }
        }

        if(!$skip_inlining_images){
            $body = $this->convertImagesInline($body);
        }

        $mail->Subject = $subject;
        $mail->MsgHTML($body);

        $mail->SMTPAuth   = $this->api->current_website['email_username']?true:false;                  // enable SMTP authentication
        $mail->Host       = $this->api->current_website['email_host'];
        $mail->Port       = $this->api->current_website['email_port'];
        $mail->Username   = $this->api->current_website['email_username'];
        $mail->Password   = $this->api->current_website['email_password'];
        
        if($this->add('Controller_EpanCMSApp')->emailSettings($mail) !== true){
            $mail->AddReplyTo($this->api->current_website['email_reply_to'], $this->api->current_website['email_reply_to_name']);
            $mail->SetFrom($this->api->current_website['email_from'], $this->api->current_website['email_from_name']);
        }

        $mail->AddAddress($to);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        foreach (explode("\n", $headers) as $h){
            $mail->AddCustomHeader($h);
        }
        $mail->Send();
    }

    function convertImagesInline(&$body){
        // get all img tags
        preg_match_all('/<img.*?>/', $body, $matches);
        if (!isset($matches[0])) return;
        // foreach tag, create the cid and embed image
        $i = 1;
        foreach ($matches[0] as $img)
        {
            // make cid
            $id = 'img'.($i++);
            // replace image web path with local path
            preg_match('/src="(.*?)"/', $body, $m);
            if (!isset($m[1])) continue;
            $arr = parse_url($m[1]);
            if (!isset($arr['host']) || !isset($arr['path']))continue;
            // add
            $this->PHPMailer->AddEmbeddedImage(getcwd().'/'.$arr['path'], $id, 'attachment', 'base64', 'image/jpeg');
            $body = str_replace($img, '<img alt="" src="cid:'.$id.'" style="border: none;" />', $body); 
        }
        return $body;
    }

    function __destruct(){
        $this->PHPMailer->SmtpClose();
    }*/
}
 
