<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
if (array_key_exists('to', $_POST)) {
    $err = false;
    $msg = '';
    $email = '';
    //Apply some basic validation and filtering to the subject
    if (array_key_exists('subject', $_POST)) {
        $subject = substr(strip_tags($_POST['subject']), 0, 255);
    } else {
        $subject = 'No subject given';
    }
    //Apply some basic validation and filtering to the query
    if (array_key_exists('query', $_POST)) {
        //Limit length and strip HTML tags
        $query = substr(strip_tags($_POST['query']), 0, 16384);
    } else {
        $query = '';
        $msg = 'No query provided!';
        $err = true;
    }
    //Apply some basic validation and filtering to the name
    if (array_key_exists('name', $_POST)) {
        //Limit length and strip HTML tags
        $name = substr(strip_tags($_POST['name']), 0, 255);
    } else {
        $name = '';
    }
    //Validate to address
    //Never allow arbitrary input for the 'to' address as it will turn your form into a spam gateway!
    //Substitute appropriate addresses from your own domain, or simply use a single, fixed address
    if (array_key_exists('to', $_POST) and in_array($_POST['to'], ['sales', 'support', 'accounts'])) {
        $to = $_POST['to'] . '@example.com';
    } else {
        $to = 'support@example.com';
    }
    //Make sure the address they provided is valid before trying to use it
    if (array_key_exists('email', $_POST) and PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $msg .= "Error: invalid email address provided";
        $err = true;
    }
    if (!$err) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 2500;
        $mail->CharSet = 'utf-8';
        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('contact@example.com', (empty($name) ? 'Contact form' : $name));
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);
        $mail->Subject = 'Contact form: ' . $subject;
        $mail->Body = "Contact form submission\n\n" . $query;
        if (!$mail->send()) {
            $msg .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $msg .= "Message sent!";
        }
    }
} ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>PHPMailer Contact Form</title>
</head>
<body>
<div id="subscribe-popup">
<?php if (empty($msg)) { ?>
	<form action="" data-action="https://www.chiaraferragnicollection.com/eu-en/frontend/subscriber/ajaxnew/"
	      method="post" id="newsletter-validate-detail-popup">
		<div class="block-title">
			Sign up for our newsletter
		</div>
		<div class="block-content">
			<div class="input-box">
				<button type="submit" title="Subscribe" class="button"><span><span>Subscribe</span></span></button>
				<input placeholder="Enter your E-mail" type="email" autocapitalize="off" autocorrect="off"
				       spellcheck="false" name="email" id="newsletter-popup" title="Enter your E-mail"
				       class="input-text required-entry validate-email"/>
			</div>
			<div class="clearfix"></div>
		</div>

		<ul>
			<li class="wide privacy-textarea">
				<label>Privacy policy</label>
				<div class="privacy-fake-textarea">
					<div>
						<p>This page provides information relevant to the methods of managing the processing of personal
							data of users who subscribe the newsletter service of Serendipity Srl<b>.</b>, (the
							following &ldquo;User&rdquo; or &ldquo;Users&rdquo;), at the address <a
									href="http://www.castioficial.com">www.castioficial.com</a>
							(hereinafter the &ldquo;Website&rdquo;). The subscription will allow the users to
							automatically receive, free of charge, every new newsletter. It is information given in
							compliance with art. 13 of the Law Decree of 30<sup>th</sup> June 2003, no. 196, &ldquo;Code
							for protecting personal data&rdquo; (hereinafter &ldquo;Privacy Code&rdquo;).</p>
						<p><strong>1. Personal Data Collector </strong></p>
						<p>The Data Collector is <b>Serendipity Srl</b> (hereinafter only &ldquo;Serendipity&rdquo;
							and/or the &ldquo;Collector&rdquo;), with offices at via Melone 2, 20121 Milano.</p>
						<p><strong>2. Purposes of the processing and type of personal details</strong></p>
						<p>The personal details that the User voluntarily provides when he/she subscribes the newsletter
							service, such as the e-mail address, might be used by the Collector, after getting the User&rsquo;s
							consent, for managing the subscription and sending the newsletter, containing informational,
							commercial, and/or promotional communications. The provision of the personal data for the
							aforementioned purposes is optional; the refusal to provide the personal data prevents the
							user from subscribing to the newsletter and/or receiving it.</p>
						<p>Please take note that pursuant to art. 130, paragraph 4, of the Privacy Code, the Collector
							may use the details relevant to the e-mail that the User has provided during the sale of a
							product or the supply of a service, for offering services similar to those that are subject
							of the sales and without getting the User&rsquo;s authorization. At any moment, the User can
							refuse the processing of data easily and free of charge by following the methods given in
							paragraph 5 &ldquo;Rights of the concerned person.&rdquo;</p>
						<p><strong>3. Method to process the data</strong></p>
						<p>The User&rsquo;s data is processed mainly by means of electronic systems and sometimes in
							hardcopies.</p>
						<p>The User&rsquo;s data will be kept with a method that allows the identification for the time
							strictly necessary to achieve the purposes for which the data is collected and processed
							and, in any case, in accordance with law provisions.</p>
						<p>The Collector and the responsible part have adopted suitable security measures in accordance
							with the law to prevent the loss of Data, unauthorized access or improper or illegal
							uses.</p>
						<p>The User&rsquo;s data may be transferred to foreign countries (also extra-EU countries, such
							as USA), in compliance to the above-mentioned information.</p>
						<p><b>4. Categories of subjects to whom the personal data can be communicated or who can know it
							and the diffusion of personal data</b></p>
						<p>In order to achieve the purposes that the personal data is collected for, the Collector can
							appoint other responsible parties for processing the personal data such as IT service
							providers, i.e. direct marketing, internet service and cloud computing, subjects that are in
							charge of the warehouse logistics, as well as promotion, sale and delivery of the products
							and services of the Collector, customer care, companies and other subjects that provide
							customers with legal, fiscal, accountancy, financial, technical-organization, data
							processing and communication services. Subjects that provide bank services, i.e. financial,
							insurance and credit recovery services, controlled companies, subsidiaries, public
							authorities and supervising bodies.</p>
						<p>In order to get the complete list of subjects responsible for personal data processing, the
							User can contact the Collector in accordance with the methods given in the following section
							5 &ldquo;Rights of the concerned persons&rdquo;.</p>
						<p>For the same purposes, Serendipity and the parties responsible for processing can appoint
							other responsible parties for processing data such as employees in charge of the execution
							of orders, the customer care, the IT and marketing services (if the authorization is given),
							the IT systems and management systems of the Website and the technical and commercial
							personal in charge of providing the website services.</p>
						<p>No User&rsquo;s personal data collected from this website will be diffused.</p>
						<p><strong>5. Right of the concerned person</strong></p>
						<p>Pursuant to art. 7 of the Privacy code, the Users can, at any time, request information about
							their personal data, if it exists, its contents, origin, correctness or ask for its
							integration and update or correction. In accordance with the same article, the User is
							entitled to request its deletion, transformation in an anonymous form or the block of
							his/her data which is processed in violation of the law and, in any case, the User can, for
							legitimate reasons, oppose himself/herself to its processing.</p>
						<p>In order to exercise the above mentioned rights, the User can send a request to VR/46 RACING
							APPAREL S.r.l<b>.</b><b>,</b> the Data Collector, by:</p>
						<ul>
							<li>post: via Melone 2, 20121 Milano, or</li>
							<li>e-mail: <a href="mailto:customercare@castioficial.com">customercare@castioficial.com</a>.</li>
						</ul>
					</div>
				</div>
				<p>
					For Further information about the processing of personal data by Casti Collection, please
					see the Website’s <a href="legal_area.html">
					Privacy Policy </a>
				</p>
			</li>
			<li class="wide privacy-box">
				<label for="privacy_form">Consent form</label>
				<div class="input-box">
					<p>
						Having acknowledged the previous information, I agree to the processing of my personal data for
						the subscription to the newsletter service by Casti Collection </p>
					<ul class="radio-content-ul">
						<li class="radio-content first">
							<input id="is_subscribed_3" type="radio" name="is_subscribed" value="1"
							       class="radio required">
							<label for="is_subscribed_3">I agree</label>
						</li>
						<li class="radio-content">
							<input id="is_subscribed_4" type="radio" name="is_subscribed" value="0" class="radio">
							<label for="is_subscribed_4">I don't agree</label>
						</li>
						<input type="hidden" class="required-entry privacy-hidden-requirement">
					</ul>
				</div>
			</li>
		</ul>
	</form>

	<script type="text/javascript">
		var newsletterSubscriberFormPopup = new VarienForm('newsletter-validate-detail-popup');
	</script>
</div>
<?php } else {
    echo $msg;
} ?>
</body>
</html>
