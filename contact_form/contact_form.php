<?php
	header('Content-Type: application/json');
	$message= "لطفا تمام فیلدهارا کامل کنید.";
	$type= "danger";
	if(isset($_POST['name']) && isset($_POST['g-recaptcha-response']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST['subject'])){
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$msg = trim($_POST['message']);
		$subject = trim($_POST['subject']);
		$captcha=$_POST['g-recaptcha-response'];
		//email address settings
		$my_address = "EMAIL-ADDRESS-HERE";
		$headers = "From: ".$email;
		$message = "Contact name: $name\nContact Email: $email\nContact Message: $msg";
		$to = $my_address;
		if(!$captcha){
			$message = "Please check the the captcha form.";
			$type= "danger";
		}else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email))
		{
			$message = "یک ایمیل معتبر وارد کنید.";
			$type= "danger";
		}
		 else if ( strlen($msg) < 10 )
		{
			$message = "پیام باید بیشتر از 10 کلمه باشد.";
			$type= "danger";
		}
		else
		{
			$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le9HrgZAAAAAIOwDqpl59h-qLyDdYA2NlYylxfL&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
			if($response['success'] == false)
			{
				$message = "You are spammer ! Get the @$%K out";
				$type= "danger";
			}
			else
			{
				mail($to, $subject, $message, $headers);
				$message= "ایمیل شما با موفقیت ارسال شد.";
				$type= "success";
			}
			
		}
		
	}
	$return_arr = array("message" => $message,"type" => $type);
		echo json_encode($return_arr);

?>