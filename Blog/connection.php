<?php

	$password = "thepassword";

	if(isset($_POST['password']))
	{
		if(($_POST['password']) == $password)
		{
			session_start();
			$_SESSION['connected'] = true;
			echo "redirect";
		}
		else
		{
			echo "password";
		}
	}