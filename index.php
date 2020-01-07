<?php
use Models\User;
use Models\Bot;
use Models\Myutils;
use Models\Post;

function __autoload($class) {

	// convert namespace to full file path
	$class = str_replace('\\', '/', $class) . '.php';
	require_once($class);

}
 $data = json_decode(file_get_contents("php://input"));
 $chnlpost = $data->channel_post;
 $cq = $data->callback_query;
 $chnlId = $chnlpost->chat->id;
 $chat_id = $data->message->chat->id;
 $type = $data->message->chat->type;
 $msg_id = $data->message->message_id;
 $message =  Myutils::convertNumbers($data->message->text, false);
 $userid = $data->message->from->id;
 $fileid = $data->message->document->file_id;
 @$imageid = end($data->message->photo)->file_id;
 $image_id = $data->message->photo->file_id;
 $voiceid = $data->message->voice->file_id;
 $videoid = $data->message->video->file_id;
 $audioid = $data->message->audio->file_id;
 $video_message_id = $data->message->video_note->file_id;
 $caption = $data->message->caption;
 $username = $data->message->chat->first_name;
 $userlastname = $data->message->chat->last_name;
 $callback = $data->callback_query->data;
 $callbackid = $data->callback_query->message->message_id;
 $callbackchid = $data->callback_query->message->chat->id;
 $callbackqid = $data->callback_query->id;
 $lat = $data->message->location->latitude;
 $lang = $data->message->location->longitude;
 $bot = new Bot("YOUR BOT TOKEN HERE");
 $user=User::find($chat_id);
 if(!is_object($user) or !($user instanceof User)){
     $data=array(
         'tid'=>$chat_id,
         'name'=>$username." ".$userlastname,
     );
     $user=User::create($data);
     $msg="سلام "."♥"."\r\n"." به بات ساخت اینستنت ویو مخفی خوش آمدید ،";
 }
 $backflag=0;
 if(trim($message)=="بازگشت")
 {
     $user=$user->backwardStep();
     $backflag=1;
 }
 switch ($user->step)
 {
     case User::START:
         $msg.=" لطفا لینک مطلب در وبسایت خود را ارسال نمایید";
         $bot->sendMessage($user->tid,$msg);
         $user->forwardStep();
         break;
     case User::WAITFORLINK:
         $msg=" کد قالب خود (rhash) را ارسال نمایید";
         $kb=$bot->simplekbmaker(array("بازگشت"));
         $user->forwardStep();
         $bot->sendMessage($user->tid,$msg,$kb);
         if(!$backflag){
             $user->update(array('link'=>$message));
         }
         break;
     case User::WAITFORRHASH:
         $msg="متن دلخواه خود را ارسال نمایید  ";
         $kb=$bot->simplekbmaker(array("بازگشت"));
         $bot->sendMessage($user->tid,$msg,$kb);
         $user->forwardStep();
         if(!$backflag){
             $user->update(array('rhash'=>trim($message)));
         }
         break;
     case User::WAITFORTEXT:
         $user->update(array('text'=>$message));
         $user=new User($user->id);
         $msg="انجام شد"."✅"."\r\n"."پیام زیر را فوروارد کنید (کپی نکنید)";
         $bot->sendMessage($user->tid,$msg);
         $link="<a href='https://t.me/iv?url=".urlencode($user->link)."&rhash=".$user->rhash."'> </a>";
         $msg=$link."<pre style='direction: rtl;text-align: right;float: right;'>".$user->text."</pre>";
         $kb=$bot->simplekbmaker(array("شروع مجدد"));
         $bot->sendMessage($user->tid,$msg,$kb,"HTML");
         $user->forwardStep();
         $data=array(
             'user_id'=>$user->id,
             'link'=>$user->link,
             'text'=>$user->text,
             'rhash'=>$user->rhash,
             'sent_at'=>date("Y-m-d H:i:s"),
         );
         $user=Post::create($data);
         break;
 }
