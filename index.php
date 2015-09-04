<?php

require "twiliomaster/Services/Twilio.php";

#Twilio number: +1 (469) 275-4950
#ACCOUNT
$AccountSid = "ACfccd6007cb12cd9aa11020f3397a770c";
$AuthToken = "7e2d950448a3c12d97561579a304c3e7";

$client = new Services_Twilio($AccountSid, $AuthToken);

$notificationFail = "";
$notificationPass = "";
$error = 0;


If ($_POST["submit"]){
	#form value validation
	$sender = "+14692754950";	
	if($_POST["receiver"]){
			$receiver = $_POST["receiver"];
	} else {
		$notificationFail .= "<li>Please enter a valid number. </li>";
		$error += 1;
	}
	if($_POST["body"]){
			$body = $_POST["body"];
	} else {
		$notificationFail .= "<li>Please enter a message. </li>";
		$error += 1;
	}
	if($_POST["media"]){
			$media = $_POST["media"];
	} else {
		$notificationFail .= "<li>Please add a media. </li>";
		$error += 1;
	}
	
	if($error==0){
		$numbers = explode(",", $receiver);		
		foreach ($numbers as $number){
			$symbols = array('+', ' ', '(', ')');
			$valid_number = str_replace($symbols, '', $number);
			$to_number = $number;
			if($valid_number != ''){
				if (ctype_digit($valid_number)) {
			        try {
			        	#api post call to send message
				        $sms = $client->account->messages->sendMessage($sender, $to_number, $body, $media);
				        $notificationPass .= "<li> $number </li>";
			    	} catch (Exception $e) {
			    		$notificationFail .= "Sending failed. Please contact administrator. ". $e->getMessage(); 
			    	}

			    } else {
			        $notificationFail .= "<li>You have entered an invalid number - $number. </li>";
		    	}
		    }	
		}
	}
	
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Twilio Send MMS</title>
	<meta name="description" content="This is a test Twilio send mms to multiple numbers.">
	<meta name="author" content="Bernadette Caritativo">
	<meta name="assignedby" content="Antonio Evans">
</head>
<body>

<center>

<h2> Send MMS via Twilio </h2>


<?php if ($notificationFail){ ?>
	<div id="notificationFail" style="width: 350px; text-align: left; font-size: 12px; color: #FF0066">
	Sorry, we have encountered an error:
		<ul>
			<?php echo $notificationFail; ?>
		</ul>
	</div> 
<?php } ?>

<?php if ($notificationPass){ ?>
	<div id="notificationPass" style="width: 350px; text-align: left; font-size: 12px; color: #0066FF">
	MMS delivered to:
		<ul>
			<?php echo $notificationPass; ?>
		</ul>
	</div> <br> 
<?php } ?>


<form method="POST" action="">
<table border="0">
	<tr>
		<td> <label>FROM:</label> </td> 
		<td> <input name="sender" type="text" id="sender" value="+14692754950" disabled> </td>
	</tr>
	<tr>
		<td> <label>TO:</label> </td> 
		<td> <input name="receiver" type="text" id="receiver" value="<?php echo $receiver ?>"> <br><small>*Please separate numbers using comma (,) <br>*Example: +639992222888,+639992222555</small></td>
	</tr>
	<tr>
		<td> <label>MESSAGE:</label> </td> 
		<td> <textarea name="body" id="body"><?php echo $body ?></textarea> </td>
	</tr>
	<tr>
		<td> <label>MEDIA:</label></td> 
		<td> <textarea name="media" id="media"><?php echo $media ?></textarea> </td>
	</tr>
</table>
	<br> <input type="submit" name="submit" id="submit" value="SEND">

<br> <br> 

<h4>NOTES and SPECIFICATIONS:</h4>
<div style="width: 300px; text-align: left; font-size: 12px">
<ul>
	<li>Twilio account is on trial.</li>
	<li>All fields are required.</li>
	<li>Sender is default to a registered Twilio number.</li>
</ul>
</div>

</form>


</center>
</body>
</html>