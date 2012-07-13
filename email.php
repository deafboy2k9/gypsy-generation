<?php 	
	if(isset($_POST['email'])) 
	{
    	//change this to your email. 
    	$email = $_POST['email'];
    	$to = "gypsygenerationmail@gmail.com"; 
    	$from = $email;
    	$subject = "KEEP " . $email . " NOTIFIED" ; 
		
		$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
		$valid = 0;
  		
  		if(!preg_match($email_exp,$email)) 
  		{
    		$valid = 0;
  		}
  		else
  		{
  			//begin of HTML message 
    		$message = $email . " wants to stay informed about Gypsy Generations progress";
    
	  		//end of message 
    		$headers  = "From: $from\r\n"; 
		    $headers .= "Content-type: text/html\r\n"; 

    		//options to send to cc+bcc 
	    	//$headers .= "Cc: [email]maa@p-i-s.cXom[/email]"; 
	    	//$headers .= "Bcc: [email]email@maaking.cXom[/email]"; 
     
	    	// now lets send the email. 
	    	mail($to, $subject, $message, $headers);
	    	$valid = 1;
  		}
  		
  		header('Location: http://gypsy-generation.com?valid=' . $valid);
	} 
?>