<?php
namespace Models;
class Bot
{
	var $url;
	public function __construct($token)
	{
		$this->url="https://api.telegram.org/bot";
        $this->url.=$token."/";
    }
	public function sendMessage($chat_id,$msg,$keyboard=array(),$pars_mode="Markdown",$additional="")
	{
		if(sizeof($keyboard)!=0)
		{
			$result = file_get_contents($this->url.'sendMessage?chat_id='.$chat_id.'&text='.urlencode($msg).'&reply_markup='.json_encode($keyboard)."&parse_mode=".$pars_mode.$additional);
		}
		else
		{
			$result = file_get_contents($this->url.'sendMessage?chat_id='.$chat_id.'&text='.urlencode($msg)."&parse_mode=".$pars_mode.$additional);
		}
		 return $result;
	}
    public function sendLocation($chat_id,$latitude,$longitude)
    {
        $result = file_get_contents($this->url.'sendLocation?chat_id='.$chat_id.'&latitude='.$latitude.'&longitude='.$longitude);
        return $result;
    }
	public function deleteMessage($msg_id,$chat_id)
	{
		$result = file_get_contents($this->url.'deleteMessage?message_id='.$msg_id.'&chat_id='.$chat_id);
		return $result;
	}
	public function editMessage($chat_id,$msg_id,$msg,$keyboard=array(),$pars_mode="Markdown")
	{
		if(sizeof($keyboard)!=0)
		{
			$result = file_get_contents($this->url.'editMessageText?chat_id='.$chat_id.'&message_id='.$msg_id.'&text='.urlencode($msg).'&reply_markup='.json_encode($keyboard));//."&parse_mode=".$pars_mode);
		}
		else
		{
			$result = file_get_contents($this->url.'editMessageText?chat_id='.$chat_id.'&message_id='.$msg_id.'&text='.urlencode($msg)."&parse_mode=".$pars_mode);
		}

		return $result;
	}
    public function editMessageCaption($chat_id,$msg_id,$msg,$keyboard=array(),$pars_mode="Markdown")
    {
        if(sizeof($keyboard)!=0)
        {
            $result = file_get_contents($this->url.'editMessageCaption?chat_id='.$chat_id.'&message_id='.$msg_id.'&caption='.urlencode($msg).'&reply_markup='.json_encode($keyboard));//."&parse_mode=".$pars_mode);
        }
        else
        {
            $result = file_get_contents($this->url.'editMessageCaption?chat_id='.$chat_id.'&message_id='.$msg_id.'&caption='.urlencode($msg)."&parse_mode=".$pars_mode);
        }

        return $result;
    }
	public function forwardMessage($chat_id,$from_chat_id,$msg_id)
	{
		$result = file_get_contents($this->url."forwardMessage?chat_id=".$chat_id."&from_chat_id=".$from_chat_id."&message_id=".$msg_id);
		return $result;
	}
	public function restrictChatMember($chat_id,$user_id,$until_date,$can_send_messages=0,$can_send_media_message=0,$can_sende_other_message=0,$can_add_web_page_previews=0)
	{
		$result = file_get_contents($this->url."restrictChatMember?chat_id=".$chat_id."&user_id=".$user_id."&until_date=".$until_date."&can_send_messages=".$can_send_messages."&can_send_media_messages".$can_send_media_message."&can_send_other_messages=".$can_sende_other_message."&can_add_web_page_previews=$can_add_web_page_previews");
		return $result;
	}
	public function kickChatMember($chat_id,$user_id)
	{
		$result = file_get_contents($this->url.'kickChatMember?chat_id='.$chat_id.'&user_id='.$user_id);
		 return $result;
	}
	public function unbanChatMember($chat_id,$user_id)
	{
		$result = file_get_contents($this->url.'unbanChatMember?chat_id='.$chat_id.'&user_id='.$user_id);
		 return $result;
	}
	public function exportChatInviteLink($chat_id)
	{
		$result = json_decode(file_get_contents($this->url.'exportChatInviteLink?chat_id='.$chat_id));
		 return $result->result;
	}
    public function getChatMembersCount($chat_id)
    {
        $result = json_decode(file_get_contents($this->url.'getChatMembersCount?chat_id='.$chat_id));
        return $result->result;
    }
	public function pinChatMessage($chat_id,$msg_id)
	{
		$result = file_get_contents($this->url.'pinChatMessage?chat_id='.$chat_id.'&message_id='.$msg_id);
		 return $result;
	}
	public function unpinChatMessage($chat_id)
	{
		$result = file_get_contents($this->url.'unpinChatMessage?chat_id='.$chat_id);
		 return $result;
	}
	public function leaveChat($chat_id)
	{
		$result = file_get_contents($this->url.'leaveChat?chat_id='.$chat_id);
		 return $result;
	}
	public function getChatAdministrators($chat_id)
	{
		$result = file_get_contents($this->url.'getChatAdministrators?chat_id='.$chat_id);
		 return $result;
	}
	public function getChatMember($chat_id,$user_id)
	{
		$result = file_get_contents($this->url.'getChatMember?chat_id='.$chat_id.'&user_id='.$user_id);
		 return $result;
	}
	public function answerCallbackQuery($callback_query_id,$text)
	{
		$result = file_get_contents($this->url.'answerCallbackQuery?callback_query_id='.$callback_query_id.'&text='.$text);
		 return $result;
	}
	public function sendDocument($chat_id,$fid,$caption="",$keyboard=array())
	{
	    if(sizeof($keyboard)==0)
        {
            $curl = curl_init($this->url . 'sendDocument?chat_id=' . $chat_id);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'document' => $fid,
                'caption' => $caption
            ));
            $result = curl_exec($curl);
        }
        else
        {
            $curl = curl_init($this->url . 'sendDocument?chat_id=' . $chat_id);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'document' => $fid,
                'caption' => $caption,
                'reply_markup'=>json_encode($keyboard)
            ));
            $result = curl_exec($curl);
        }
		return $result;
	}
	public function sendPhoto($chat_id,$pid,$caption="",$keyboard=array())
	{
	    if(sizeof($keyboard)==0)
        {
            $curl=curl_init($this->url.'sendPhoto?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'photo'=>$pid,
                'caption'=>$caption
            ));
            $result = curl_exec($curl);
        }
        else
        {
            $curl=curl_init($this->url.'sendPhoto?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'photo'=>$pid,
                'caption'=>$caption,
                'reply_markup'=>json_encode($keyboard)
            ));
            $result = curl_exec($curl);
        }

		return $result;
	}
	public function sendVideo($chat_id,$vid,$caption="",$keyboard=array())
	{
	    if(sizeof($keyboard)==0)
        {
            $curl=curl_init($this->url.'sendVideo?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'video'=>$vid,
                'caption'=>$caption
            ));
            $result = curl_exec($curl);
        }
        else
        {
            $curl=curl_init($this->url.'sendVideo?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'video'=>$vid,
                'caption'=>$caption,
                'reply_markup'=>json_encode($keyboard)
            ));
            $result = curl_exec($curl);
        }

		return $result;
	}
	public function sendAudio($chat_id,$aid,$caption="",$keyboard=array())
	{
	    if(sizeof($keyboard)==0)
        {
            $curl=curl_init($this->url.'sendAudio?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'audio'=>$aid,
                'caption'=>$caption
            ));
            $result = curl_exec($curl);
        }
        else
        {
            $curl=curl_init($this->url.'sendAudio?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'audio'=>$aid,
                'caption'=>$caption,
                'reply_markup'=>json_encode($keyboard)
            ));
            $result = curl_exec($curl);
        }

		return $result;
	}
	public function sendVoice($chat_id,$vid,$caption="",$keyboard=array())
	{
	    if(sizeof($keyboard)==0)
        {
            $curl=curl_init($this->url.'sendVoice?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'voice'=>$vid,
                'caption'=>$caption
            ));
        }
        else
        {
            $curl=curl_init($this->url.'sendVoice?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'voice'=>$vid,
                'caption'=>$caption,
                'reply_markup'=>json_encode($keyboard)
            ));
        }

			$result = curl_exec($curl);
		return $result;
	}
	public function sendVideoNote($chat_id,$vnid,$caption="",$keyboard=array())
	{
	    if(sizeof($keyboard)==0)
        {
            $curl=curl_init($this->url.'sendVideoNote?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'video_note'=>$vnid,
                'caption'=>$caption
            ));
            $result = curl_exec($curl);
        }
        else
        {
            $curl=curl_init($this->url.'sendVideoNote?chat_id='.$chat_id);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,array(
                'video_note'=>$vnid,
                'caption'=>$caption,
                'reply_markup'=>json_encode($keyboard)
            ));
            $result = curl_exec($curl);
        }

		return $result;
	}
    public function simplekbmaker($items)
    {
        $keybodard=array();
        $keybodard['keyboard']=array();
        array_push($keybodard['keyboard'],$items);
        $keybodard['resize_keyboard']=true;
        $keybodard['one_time_keyboard']=true;
        return $keybodard;
    }
    public function inlinekbmaker($items)
    {
        $keyboard=array();
        $keyboard['inline_keyboard']=$items;
        return $keyboard;
    }

}
?>