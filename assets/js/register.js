console.log("Loaded register.js");
function registerFirstStep(){
	if($('.systemInput[name="email"]').val().trim() != '' && $('.systemInput[name="password"]').val().trim() != '' && $('.systemInput[name="nickname"]').val().trim() != ''){
		if($('.systemInput[name="password"]').val().length > 6){
			if($('.systemInput[name="nickname"]').val().length < 4 || $('.systemInput[name="nickname"]').val().length > 20){
				if($(".error").length > 0){
					$(".error").text("Ваш никнейм короткий или слишком длинный!");
				}else{
					$("body").prepend('<div class="error">Ваш никнейм короткий или слишком длинный!</div>');
				}
			}else{
				$("#JS__Step1").css("margin-left", "-258px");
				$("#JS__Step2").show(0);
			}
		}else{
			if($(".error").length > 0){
				$(".error").text("Пароль должен быть больше 6 символов!");
			}else{
				$("body").prepend('<div class="error">Пароль должен быть больше 6 символов!</div>');
			}
			setTimeout(() => {$(".error").remove();}, 4000);
		}
	}else{
		if($(".error").length > 0){
			$(".error").text("Не все поля для ввода введены");
		}else{
			$("body").prepend('<div class="error">Не все поля для ввода введены</div>');
		}
		setTimeout(() => {$(".error").remove();}, 4000);
	}
}
function registerForm(){
	$("#JS__Register").show(0);
	$("#JS__Register").css("margin-left", "0px");
	$(".systemRegister2buttons").hide(0);
}
function loginForm(){
	$("#JS__Login").show(0);
	$("#JS__Login").css("margin-left", "0px");
	$(".systemRegister2buttons").hide(0);
}
function backToFirst(){
	$("#JS__Step1").css("margin-left", "0px");
	setTimeout(() => {
		$("#JS__Step2").hide(0);
	}, 490)
}
function checkboxSC(){
	if(document.querySelector("input[name='agree']").checked){
		$(".BTNreg").prop("disabled", false);
	}else{
		$(".BTNreg").prop("disabled", true);
	}
}