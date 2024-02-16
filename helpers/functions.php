<?php

	function html($tx): array|string
    {
        return str_replace(['<','>'],['&#60;','&#62;'],$tx);
    }

    function removeBotUserName($tx): string
    {
    	return explode('@', $tx)[0];
    }

	function myUser(array $dbColumns=[], array $myUser=[]): bool
    {
        global $db;
        $user = $db->selectWhere('users',[
            [
                'fromid'=>$myUser[0],
                'cn'=>'='
            ],
        ]);
        if ($user->num_rows) {
            if (mysqli_fetch_assoc($user)['del'] == '1') {
                $db->updateWhere('users',[
                    'del'=>0,
                ],[
                    'fromid'=>$myUser[0],
                    'cn'=>'='
                ]);
            }
            return false;
        }
        $dbInsert = [];
        foreach ($dbColumns as $key => $value) {
            $dbInsert[$dbColumns[$key]] = $myUser[$key];
        }
        return $db->insertInto('users',$dbInsert);
    }

    function channel(int $fromid): bool
    {
        global $db,$admin,$mid,$message_id,$qid, $bot;
        $status = mysqli_fetch_assoc($db->selectWhere('channels',[
            [
                'name'=>"status",
                'cn'=>'='
            ],
        ]));
        if ($status["target"] == "on") {
            $channels = $db->selectWhere('channels',[
                [
                    'name'=>"status",
                    'cn'=>'!='
                ],
            ]);
            if ($num = $channels->num_rows) {
                $res = 0;
                foreach ($channels as $key => $value) {
                    $id = $value['target'];
                    $getchatadmin = $bot->request('getChatMember',[
                        'chat_id'=>$id,
                        'user_id'=>$fromid
                    ]);
                    $status = $getchatadmin->result->status;
                    if ($status == "administrator" or $status == "creator" or $status == "member") {
                        $res++;
                    }
                }
                if ($res == $num) {
                    return true;
                }else{
                    foreach ($channels as $key => $value) {
                        $id = $value['target'];
                        $getchat = $bot->request('getChat',[
                            'chat_id'=>$id
                        ]);
                        $title = $getchat->result->title;
                        $link = $getchat->result->invite_link;
                        if (!empty($link)) {
                            $keyy[]=['text'=>"➕ Obuna boʻlish", 'url'=>"$link"];
                        }
                    }
                    $keyy[] = ['callback_data'=>"res", 'text'=>"✅ Tasdiqlash"];
                    $key = array_chunk($keyy, 1);
                    $admin = $db->selectWhere('admins',[
                        [
                            'fromid'=>$fromid,
                            'cn'=>'='
                        ],
                    ]);
                    if ($admin->num_rows) {
                        return true;
                    }
                    if (!is_null($mid)) {
                        $bot->request('answerCallbackQuery',[
                            'callback_query_id'=>$qid,
                            'text'=>"Barcha kanallarimizga obuna bo'lmadingiz!"
                        ]);
                        $bot->request('editMessageText',[
                            'chat_id'=>$fromid,
                            'text'=>"<b>❗️Botdan foydalanishni davom ettirish uchun quyidagi kanalimizga obuna bo'ling👇🏼</b>",
                            'parse_mode'=>'html',
                            'message_id'=>$mid,
                            'reply_markup'=>json_encode([
                                'inline_keyboard'=>$key
                            ]),
                        ]);
                        return false;
                    }
                    $bot->request('sendMessage',[
                        'chat_id'=>$fromid,
                        'text'=>"<b>❗️Botdan foydalanishni davom ettirish uchun quyidagi kanalimizga obuna bo'ling👇🏼</b>",
                        'parse_mode'=>'html',
                        'reply_to_message_id'=>$message_id,
                        'reply_markup'=>json_encode([
                            'inline_keyboard'=>$key
                        ]),
                    ]);
                    if (!is_null($mid)) {
                        $bot->request('editMessageText',[
                            'chat_id'=>$fromid,
                            'text'=>"<b>❗️Botdan foydalanishni davom ettirish uchun quyidagi kanalimizga obuna bo'ling👇🏼</b>",
                            'parse_mode'=>'html',
                            'message_id'=>$mid,
                            'reply_markup'=>json_encode([
                                'inline_keyboard'=>$key
                            ]),
                        ]);
                    }
                    return false;
                }
            }
        }
        return true;
    }

    function isAdmin(int $fromid): bool
    {
        global $db;
        $user = $db->selectWhere('admins',[
            [
                'fromid'=>$fromid,
                'cn'=>'='
            ],
        ]);
        return (bool) mysqli_num_rows($user);
    }

    function getEnvVariable(string $key): ?string
    {
        $envFile = dirname(__DIR__) . '/.env';
        $envContent = file_get_contents($envFile);
        $envLines = explode("\n", $envContent);

        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            list($envKey, $envValue) = explode('=', $line, 2);

            if ($envKey === $key) {
                return $envValue;
            }
        }

        return null;
    }

    function ShowError(): void
    {
        $env = getEnvVariable('APP_ENV');
        if($env == 'local'){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }
    ShowError();
