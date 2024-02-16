<?php

	require './Telegram/TelegramBot.php';
    require './helpers/functions.php';
	require './db_config/db_config.php';

	use TelegramBot as Bot;

	$token = getEnvVariable('BOT_TOKEN');
	$dataSet = ['botToken'=>$token];

	$bot = new Bot($dataSet);

	require_once './helpers/variables.php';
    try {
        $db = new DB_CONFIG(getEnvVariable('HOSTNAME'), getEnvVariable('USERNAME'), getEnvVariable('PASSWORD'), getEnvVariable('DATABASE_NAME');
    } catch (Exception $e) {
        $bot->sendMessage($e->getMessage(), getEnvVariable('ADMIN_ID'));
    }

    include './helpers/sendMessageToUsers.php';
	include './helpers/activeUsers.php';
    include './helpers/keyboards/inline_keyboards.php';
    include './helpers/keyboards/resize_keyboards.php';

	if ($update) {
		if (isset($update->message)) {
			if ($type == 'private') {
				if (removeBotUserName($text) == "/start") {
					$myUser = myUser(['fromid','name','user','chat_type','lang','del'],[$fromid,$full_name,$user ?? null,'private','',0]);
				}
				if (channel($fromid)) {
					$user = mysqli_fetch_assoc(
						$db->selectWhere('users',[
							[
								'fromid'=>$fromid,
								'cn'=>'='
							]
						])
					);
					$user_data = json_decode($user['data']);
					if (removeBotUserName($text) == "/start") {
						$db->updateWhere('users',
							[
								'data'=>'register',
								'step'=>1
							],
							[
								'fromid'=>$fromid,
								'cn'=>'='
							]
						);
						if ($myUser) {
							$bot->sendChatAction('typing', $chat_id)->setInlineKeyBoard($inline_keyboard)->sendMessage("<b>Assalomu alaykum, " . $full_name ." siz 1-martta botga start berdingiz </b>");
							exit();
						}
						$bot->sendChatAction('typing', $chat_id)->setInlineKeyBoard($inline_keyboard)->sendMessage("<b>Assalomu alaykum, " . $full_name ." siz 2-martta botga start berdingiz</b>");
						exit();
					}
					if ($user['data'] == 'register' && $user['step'] == '2') {
						if ($text) {
							$db->updateWhere('users',
								[
									'step'=>3,
									'full_name'=>$text
								],
								[
									'fromid'=>$fromid,
									'cn'=>'='
								]
							);

							$bot->sendChatAction('typing', $fromid)->sendMessage("Bog'lanish uchun telefon raqamingizni yuboring:");
						}
					}
				}
			}
            else{
				if (removeBotUserName($text) == "/start") {
					$myUser = myUser(['fromid','name','user','chat_type','lang','del'],[$fromid,$full_name,$user,'group','',0]);

					if ($myUser) {
						$bot->sendChatAction('typing', $chat_id)->sendMessage('Assalomu alaykum, xush kelibsiz!');
						exit();
					}
					$bot->sendChatAction('typing', $chat_id)->sendMessage('Assalomu alaykum, qayata xush kelibsiz!');
					exit();
				}
			}
		}
        else if (isset($update->callback_query)) {
			if (channel($callback_from_id)) {
				$user = mysqli_fetch_assoc(
					$db->selectWhere('users',[
						[
							'fromid'=>$callback_from_id,
							'cn'=>'='
						]
					])
				);

				if ($data == 'res') {
					$bot->sendChatAction('typing', $callback_from_id)->editMessageText("Assalomu alaykum, xush kelibsiz!", $mid);
					exit();
				}

				if ($data == 'inline_key') {
					$bot->sendChatAction('typing', $callback_from_id)->setInlineKeyBoard($inline_keyboard)->editMessageText("Edit bo'ldi", $mid);
					exit();
				}
			}
		}
	}

	include 'helpers/admin/admin.php';
