<?php
class ChatHandler{
	public static function showMessages($to, $user){
		if(DB::RowCount("SELECT * FROM users WHERE userID = :to", [":to" => $to]) > 0){
			if($to == $user->userID){
				return json_encode(["error_ident" => "USER_MATCHES_WITH_USER_COOKIE_ID"], JSON_UNESCAPED_UNICODE);
			}else{
				$messages = '';
				if(DB::RowCount("SELECT * FROM messages LEFT JOIN users ON userID = messageBy WHERE (messageTo = :to AND messageBy = :by) OR (messageTo = :by AND messageBy = :to)", [":by" => $user->userID, ":to" => $to]) == 0){
					$messages = '<div class="ui__HaaEJ7__ZdUBmG"><div><div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 00-1.032-.211 50.89 50.89 0 00-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 002.433 3.984L7.28 21.53A.75.75 0 016 21v-4.03a48.527 48.527 0 01-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979z" /><path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 001.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0015.75 7.5z" /></svg></div><div class="ui__GUQEES__4Fs5Bn">Начните свой диалог</div></div></div>';
				}
				foreach(DB::Query("SELECT * FROM messages LEFT JOIN users ON userID = messageBy WHERE (messageTo = :to AND messageBy = :by) OR (messageTo = :by AND messageBy = :to)", true, true, [":by" => $user->userID, ":to" => $to]) as $message){
					if($message->messageBy == $user->userID){
						$messages = $messages.'<div id="MessageAppMessage" class="MessageWasByUser"><div id="MessageAppMessageItself"><span>'.$message->messageContent.'</span><div id="MessageAppMessageDateTime">'.$message->messageAdded.'</div></div><div id="MessageAppMessagePFP"><img src="'.$message->profileImage.'" alt="Фотография профиля"></div></div>';
					}else{
						$messages = $messages.'<div id="MessageAppMessage"><div id="MessageAppMessagePFP"><img src="'.$message->profileImage.'" alt="Фотография профиля"></div><div id="MessageAppMessageItself"><span>'.$message->messageContent.'</span><div id="MessageAppMessageDateTime">'.$message->messageAdded.'</div></div></div>';
					}
				}
				return $messages;
			}
		}else{
			return json_encode(["error_ident" => "USER_DOES_NOT_FOUNDED"], JSON_UNESCAPED_UNICODE);
		}
	}

	public static function showConversations($user){
        foreach(DB::Query("SELECT * FROM conversations WHERE conversationTo = :to or conversationBy = :to ORDER BY conversationID DESC", true, true, [":to" => $user->userID]) as $conversation){
        	if($conversation->conversationBy != $user->userID){
        		$profile = DB::Query("SELECT * FROM users WHERE userID = :id", true, false, [":id" => $conversation->conversationBy]);
        	}
        	if($conversation->conversationTo != $user->userID){
        		$profile = DB::Query("SELECT * FROM users WHERE userID = :id", true, false, [":id" => $conversation->conversationTo]);
        	}
        	echo '<a class="followed" href="#" onclick="location.href = `/messages/'.$profile->nickname.'`;">
                <div>
                    <img src="'.$profile->profileImage.'">
                </div>
                <div>
                    <b>'.$profile->name.'</b><br>@'.$profile->nickname.'
                </div>
                '.(DB::RowCount("SELECT * FROM messages WHERE messageTo = :to AND messageBy = :by AND messageReaded != 1", [":to" => $user->userID, ":by" => $profile->userID]) == 0 ? '' : '<div id="msgCounter01">'.DB::RowCount("SELECT * FROM messages WHERE (messageTo = :to AND messageBy = :by AND messageReaded = 0) OR (messageTo = :by AND messageBy = :to AND messageReaded = 0)", [":to" => $user->userID, ":by" => $profile->userID]).'</div>').'
            </a>';
        }
	}

	public static function sendMessage($message, $to, $user){
		$message = nl2br(Text::Prepare($message));
		$profile = DB::Query("SELECT * FROM users WHERE userID = :userID", true, false, [":userID" => $to]);
		$show = false;
		if($profile->cf_messages == 0){
		    $show = true;
		}else if($profile->cf_messages == 1 && DB::RowCount("SELECT * FROM follows WHERE followBy = :by AND followTo = :to", [":by" => $profile->userID, ":to" => $user->userID]) == 1){
		        $show = true;
		}else if($profile->cf_messages == 2 && DB::RowCount("SELECT * FROM follows WHERE followTo = :by AND followBy = :to", [":by" => $profile->userID, ":to" => $user->userID]) == 1){
		        $show = true;
		}else if($profile->cf_messages == 3 && DB::RowCount("SELECT * FROM follows WHERE (followTo = :by AND followBy = :to) OR (followBy = :by AND followTo = :to)", [":by" => $profile->userID, ":to" => $user->userID]) == 2){
		        $show = true;
		}
		if(!Text::Null($message) && $show === true){
			if(DB::RowCount("SELECT * FROM users WHERE userID = :to", [":to" => $to]) > 0){
				if($to != $user->userID){
					if(DB::RowCount("SELECT * FROM conversations WHERE conversationTo = :id OR conversationBy = :id ORDER BY conversationID DESC", [":id" => $user->userID]) != 0){
		                DB::Query("DELETE FROM conversations WHERE (conversationTo = :to AND conversationBy = :by) OR (conversationBy = :to AND conversationTo = :by)", false, false, [":to" => $to, ":by" => $user->userID]);
		            }
		            DB::Query("INSERT INTO conversations(conversationTo, conversationBy) VALUES(:to, :id)", false, false, [":to" => $to, ":id" => $user->userID]);
					DB::Query("INSERT INTO messages(messageContent, messageBy, messageTo, messageAdded) VALUES(:content, :by, :to, :added)", false, false, [":content" => $message, ":by" => $user->userID, ":to" => $to, ":added" => date("Y-m-d H:i:s")]);
				}
			}
		}
	}
}