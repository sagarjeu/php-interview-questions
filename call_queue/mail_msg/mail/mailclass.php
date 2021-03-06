<?php
/*
 * test_smtp.php
 *
 * @(#) $Header: /home/mlemos/cvsroot/smtp/test_smtp.php,v 1.18 2009/04/11 22:23:24 mlemos Exp $
 */
	require("class.phpmailer.php");

 
  /* Uncomment when using SASL authentication mechanisms */
	
	require("class.smtp.php");

function send_mail($notify_fromaddress,$parent_mailid,$mail_smtpserver,$mail_smtpport,$mail_smtpuser,$Domain_name,$mail_smtppass,$subject,$mail_boday)
{
	$from = $notify_fromaddress;                           /* Change this to your address like "me@mydomain.com"; */
	$recipient_line=__LINE__;
		

	$to   = $parent_mailid;                             /* Change this to your test recipient address */ 
	$recipient_line=__LINE__;


	if(strlen($from)==0)
		die("Please set the messages sender address in line ".$sender_line." of the script ".basename(__FILE__)."\n");
	if(strlen($to)==0)
		die("Please set the messages recipient address in line ".$recipient_line." of the script ".basename(__FILE__)."\n");

	$smtp=new smtp_class;

	$smtp->host_name=$mail_smtpserver;       /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
	$smtp->host_port=$mail_smtpport;                /* Change this variable to the port of the SMTP server to use, like 465 */
	$smtp->ssl=1;                       /* Change this variable if the SMTP server requires an secure connection using SSL */
	$smtp->start_tls=0;                 /* Change this variable if the SMTP server requires security by starting TLS during the connection */
	$smtp->localhost="localhost";       /* Your computer address */
	$smtp->direct_delivery=0;           /* Set to 1 to deliver directly to the recepient SMTP server */
	$smtp->timeout=10;                  /* Set to the number of seconds wait for a successful connection to the SMTP server */
	$smtp->data_timeout=0;              /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
	                                       Set to 0 to use the same defined in the timeout variable */
	$smtp->debug=1;                     /* Set to 1 to output the communication with the SMTP server */
	$smtp->html_debug=1;                /* Set to 1 to format the debug output as HTML */
	$smtp->pop3_auth_host="";           /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
	$smtp->user=$mail_smtpuser;                     /* Set to the user name if the server requires authetication */
	$smtp->realm=$Domain_name;                    /* Set to the authetication realm, usually the authentication user e-mail domain */
	$smtp->password=$mail_smtppass;                 /* Set to the authetication password */
	$smtp->workstation="";              /* Workstation name for NTLM authentication */
	$smtp->authentication_mechanism="PLAIN"; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
	                                       Leave it empty to make the class negotiate if necessary */

	/*
	 * If you need to use the direct delivery mode and this is running under
	 * Windows or any other platform that does not have enabled the MX
	 * resolution function GetMXRR() , you need to include code that emulates
	 * that function so the class knows which SMTP server it should connect
	 * to deliver the message directly to the recipient SMTP server.
	 */
	if($smtp->direct_delivery)
	{
		if(!function_exists("GetMXRR"))
		{
			/*
			* If possible specify in this array the address of at least on local
			* DNS that may be queried from your network.
			*/
			$_NAMESERVERS=array();
			include("getmxrr.php");
		}
		/*
		* If GetMXRR function is available but it is not functional, to use
		* the direct delivery mode, you may use a replacement function.
		*/
		/*
		else
		{
			$_NAMESERVERS=array();
			if(count($_NAMESERVERS)==0)
				Unset($_NAMESERVERS);
			include("rrcompat.php");
			$smtp->getmxrr="_getmxrr";
		}
		*/
	}

	if($smtp->SendMessage(
		$from,
		array(
			$to
		),
		array(
			"MIME-Version: 1.0",
			"Content-type: text/html; charset=iso-8859-1",
			"From: $from",
			"To: $to",
			"Subject: $subject",
			"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
		),
		"$mail_boday")
		)
		
		echo "Mail sent to $to OK. <br>\n";
	else
		echo "Cound not send the message to $to.\nError: ".$smtp->error."\n";

}
?>