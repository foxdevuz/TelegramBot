<?php

	$update = json_decode(file_get_contents('php://input'));
    if (isset($update)) {
        if (isset($update->message)) {
            $message = $update->message;
            $chat_id = $message->chat->id;
            $type = $message->chat->type;
            $message_id =$message->message_id;
            $name = $message->from->first_name;
            $last_name = $message->from->last_name;
            $full_name = $name . " " . $last_name;
            $full_name = html($full_name);
            $user = $message->from->username ?? '';
            $fromid = $message->from->id;
            $text = html($message->text);
            $title = $message->chat->title;
            $username = $message->chat->username;
            $username = $username ? $username : "Shaxsiy Guruh!";
            $caption = $message->caption;
            $entities = $message->entities;
            $entities = $entities[0];
            $left_chat_member = $message->left_chat_member;
            $new_chat_member = $message->new_chat_member;
            $photo = $message->photo;
            $video = $message->video;
            $audio = $message->audio;
            $voice = $message->voice;
            $reply = $message->reply_markup;
            $forwarded_chat_id = $message->forward_from_chat->id;
            $fid = $message->forward_from_message_id;
        }else if(isset($update->callback_query)){
            $callback = $update->callback_query;
            $qid = $callback->id;
            $mes = $callback->message;
            $mid = $mes->message_id;
            $callback_message_text = $mes->text;
            $cid = $callback->message->chat->id;
            $callback_chat_type = $callback->message->chat->type;
            $callback_from_id = $callback->from->id;
            $callback_username = $callback->from->username;
            $data = $callback->data;
        }
    }
    file_put_contents('log.json', file_get_contents('php://input'));
    $cyrArray = [
        '\"','\n',"\n",'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','ў','Ў','ғ','Ғ','қ','Қ','ҳ','Ҳ'
    ];
    $latArray = [
        "","\n","\n",'a','b','v','g','d','e','yo','j','z','i','y','k','l','m','n','o','p',
        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
        'A','B','V','G','D','E','YO','J','Z','I','Y','K','L','M','N','O','P',
        'R','S','T','U','F','H','TS','CH','SH','SHT','A','I','Y','E','YU','YA','oʻ','Oʻ','gʻ','Gʻ','q','Q','x','X'
    ];
    
?>