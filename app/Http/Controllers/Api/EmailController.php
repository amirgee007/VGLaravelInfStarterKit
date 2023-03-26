<?php
/**
 * @desc
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-24 14:27
 */
namespace Vanguard\Http\Controllers\Api;
use Mail;
use Log;
class EmailController extends ApiController {
    //
    public function send(string $email){
        if(empty($email)){
            $this->log('email fail error', 'email is none');
            return;
        }
        Mail::raw('Dear users, your crawler queue has been completed, please go to the background to check', function($message)use($email){
            $message->to($email)->subject('Dear users, your crawler queue has been completed, please go to the background to check');
        });
        if(!empty(Mail::failures())){
            $this->log('email fail error', json_encode(Mail::failures(), JSON_UNESCAPED_UNICODE));
        }
    }
}