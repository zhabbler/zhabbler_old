<?php
if(isset($_POST['value'])){
	$search = $_POST['value'];
	foreach (DB::Query("SELECT * FROM users WHERE (nickname LIKE :search OR name LIKE :search) AND (entered_all = 1 AND reason = '')", true, true, [":search" => "%".$search."%"]) as $user) {
		echo '<a href="/profile/'.$user->nickname.'" class="ui__db2xDK__srFcPf">
                        <div>
                            <img src="'.(!empty($user->reason) ? '/assets/images/banned.png' : $user->profileImage).'" class="ui__QYoMBd__cN6sz7">
                        </div>
                        <div>
                            <span>
                                <strong>'.(!empty($user->reason) ? 'Заблокированный пользователь' : $user->name).'</strong> @'.$user->nickname.'
                            </span>
                        </div>
                    </a>';
	}
}