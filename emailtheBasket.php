<?php

	require_once 'htmlToPDF/HTML_ToPDF-3.5/HTML_ToPDF.php';

	if 	(isset($_GET['cardType'])) 		$thiscardType = $_GET['cardType'];
	elseif 	(isset($_POST['cardType'])) 		$thiscardType = $_POST['cardType'];
	else 	$thiscardType = "1" ;

	if 	(isset($_GET['assertionID'])) 		$thisassertionID = $_GET['assertionID'];
	elseif 	(isset($_POST['assertionID'])) 		$thisassertionID = $_POST['assertionID'];
	else 	$thisassertionID = "1" ;

	if 	(isset($_GET['useremail'])) 		$to  = $_GET['useremail'];
	elseif 	(isset($_POST['useremail'])) 		$to  = $_POST['useremail'];
	else 	$to  = "1" ;

	if 	(isset($_GET['emailOrDownload'])) 	$emailOrDownload  = $_GET['emailOrDownload'];
	elseif 	(isset($_POST['emailOrDownload'])) 	$emailOrDownload  = $_POST['emailOrDownload'];
	else 	$emailOrDownload  = "download" ;

	if 	(isset($_GET['jsrandnum'])) 		$thisRand = $_GET['jsrandnum'];
	elseif 	(isset($_POST['jsrandnum'])) 		$thisRand = $_POST['jsrandnum'];
	else 	$thisRand = "654" ;


	$deckArray = explode("_", $thisassertionID);
	$cardArray = explode("_", $thiscardType);

	$themessage = "";
	$deckSQLWhereClause = "";

	@unlink("basketpdf/attachment$thisRand");

	//shell_exec("echo '<html><head><link href=\"wemestyle.css\" rel=\"stylesheet\" type=\"text/css\"/></head><body>' >> basketpdf/attachment$thisRand");
	shell_exec("echo '<html><head></head><body>' >> basketpdf/attachment$thisRand");
	if (count($deckArray) > 1)
		for ($i = 0; $i < count($deckArray)-1 ; $i++){
			shell_exec("cat offlinecontent/" . $cardArray[$i] . "-" . $deckArray[$i] . ".xml >> basketpdf/attachment$thisRand");

		}

	shell_exec("echo '</body></html>' >> basketpdf/attachment$thisRand");


	$htmlFile = dirname(__FILE__) . "/basketpdf/attachment$thisRand";
	$defaultDomain = "http://weme-dev.uszkalo.com/throwing-bones";
	$pdfFile = dirname(__FILE__) . "/basketpdf/basket$thisRand.pdf";
	@unlink($pdfFile);

	$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain, $pdfFile);

	$pdf->setHeader('color', 'gray');
	$pdf->setFooter('color', 'gray');

	$pdf->setHeader('left', 'Throwing Bones Basket');
	$pdf->setHeader('right', 'Witches of Early Modern England');

	$pdf->setFooter('right', '$D');
	$result = $pdf->convert();

	if ($emailOrDownload  != "download"){

		$email_subject = "Throwing Bones (Witches of Early Modern England) - Your Basket on ".  date("m/d/y"); 

		$themessage = "Please follow the attachment.";

		$fileatt = "basketpdf/basket$thisRand.pdf"; // Path to the file 
		$email_to = $to; 


		shell_exec( "echo \"$themessage\" | mutt -s \"$email_subject\" $to -a basketpdf/basket$thisRand.pdf");

/*

		$fileatt_type = "application/octet-stream"; // File Type 
		$fileatt_name = "basket$thisRand.pdf"; // Filename that will be used for the file as the attachment 

		$email_from = "noreply@weme-dev.org"; // Who the email is from 
		$email_txt = ""; 



		$headers = "From: ".$email_from; 

		$file = fopen($fileatt,'rb'); 
		$data = fread($file,filesize($fileatt)); 
		fclose($file); 

		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

		$headers .= "\nMIME-Version: 1.0\n" . 
		"Content-Type: multipart/mixed;\n" . 
		" boundary=\"{$mime_boundary}\""; 

		$email_message .= "This is a multi-part message in MIME format.\n\n" . 
		"--{$mime_boundary}\n" . 
		"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
		"Content-Transfer-Encoding: 7bit\n\n" . 
		$themessage . "\n\n"; 

		$data = chunk_split(base64_encode($data)); 

		$email_message .= "--{$mime_boundary}\n" . 
		"Content-Type: {$fileatt_type};\n" . 
		" name=\"{$fileatt_name}\"\n" . 
		"Content-Transfer-Encoding: base64\n\n" . 
		$data . "\n\n" . 
		"--{$mime_boundary}--\n"; 

		$ok = @mail($email_to, $email_subject, $email_message, $headers); 
*/
	}

	shell_exec("chmod 777 basketpdf/*");

echo "Done";


function sendfile($input) {

	global $sendfile;

	/* PREPARE MAIL HEADERS */

	$headers = "From: ".$sendfile['email_from'];
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	$headers .= "\nMIME-Version: 1.0\n" .
	            "Content-Type: multipart/mixed;\n" .
	            " boundary=\"{$mime_boundary}\"";

	$email_message = "This is a multi-part message in MIME format.\n\n" .
	                "--{$mime_boundary}\n" .
	               "Content-Type:text/plain; charset=\"iso-8859-1\"\n" .
	               "Content-Transfer-Encoding: 7bit\n\n" .
			"Please follow the attachment\n\n" ;

	/* PREPARE ATTACHMENT */
	$fileatt = basename($input) ;
	$fileatt_type = "application/octet-stream";

	$file = fopen($input,"r${sendfile['bintext']}");
	$data = fread($file,filesize($input));
	fclose($file);

	$data = chunk_split(base64_encode($data));

	$email_message .= "--{$mime_boundary}\n" .
		"Content-Type: {$fileatt};\n" .
		" name=\"{$fileatt}\"\n" .
		"Content-Transfer-Encoding: base64\n\n" .
		$data . "\n\n" .
		"--{$mime_boundary}--\n";

	/* SEND FILE */
	$ok = @mail($sendfile['dest'], $sendfile['email_subject'], $email_message, $headers);
}


?>
