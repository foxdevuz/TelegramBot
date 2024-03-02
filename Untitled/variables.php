<?php
namespace Untitled;

use App\Functions\Core;

$update = json_decode(file_get_contents('php://input'));

if (isset($update)) {
    if (isset($update->message)) {
        Variables::$update = $update;
        Variables::$message = $update->message;
        Variables::$chat_id = $update->message->chat->id;
        Variables::$type = $update->message->chat->type;
        Variables::$message_id = $update->message->message_id;
        Variables::$name = $update->message->from->first_name;
        Variables::$last_name = $update->message->from->last_name;
        Variables::$full_name = Core::html(Variables::getName() . " " . Variables::getLastName());
        Variables::$user = $update->message->from->username ?? '';
        Variables::$from_id = $update->message->from->id;
        Variables::$text = Core::html($update->message->text);
        Variables::$title = $update->message->chat->title;
        Variables::$username = $update->message->chat->username ?? "Private Group";
        Variables::$caption = $update->message->caption;
        Variables::$entities = $update->message->entities;
        Variables::$left_chat_member = $update->message->left_chat_member;
        Variables::$new_chat_member = $update->message->new_chat_member;
        Variables::$photo = $update->message->photo;
        Variables::$video = $update->message->video;
        Variables::$audio = $update->message->audio;
        Variables::$voice = $update->message->voice;
        Variables::$reply = $update->message->reply_markup;
        Variables::$forwarded_chat_id = $update->message->forward_from_chat->id;
        Variables::$forward_message_id = $update->message->forward_from_message_id;
    } else if (isset($update->callback_query)) {
        Variables::$callback = $update->callback_query;
        Variables::$callback_id = Variables::$callback->id;
        Variables::$message = Variables::$callback->message;
        Variables::$message_id = Variables::$message->message_id;
        Variables::$text = Variables::$message->text;
        Variables::$chat_id = Variables::$callback->message->chat->id;
        Variables::$chat_type = Variables::$callback->message->chat->type;
        Variables::$from_id = Variables::$callback->from->id;
        Variables::$username = Variables::$callback->from->username;
        Variables::$data = Variables::$callback->data;
    }
}

if (Core::getEnvVariable("APP_ENV") === "local") {
    file_put_contents('log.json', file_get_contents('php://input'));
}
