<?php
//
//	Form Bootstrap Based and..filtered Wow !!!	
//

header( 'content-type: text/html; charset=utf-8');
session_start();

//Inclusing...
include("your_db_connector.php");

//classe Form Bypass Protector
include("fbpp.class2021.php");
$myfbpp = new FBPP();

//POST processing...


	if(isset($_POST['msg_submitted']) && strpos($_POST['message'],'http')===false && substr(trim($_POST['phonenumber']), 0, 1) != "8")
	{
		//antispam Filtering
		if(!(isset($_POST["FBPP_{$myfbpp->getHashInputKey()}"]) && $_POST["FBPP_{$myfbpp->getHashInputKey()}"]==$myfbpp->getHashData()))
		{
			header('Location: https://www.yourwebsite.com');
			exit;
		}
		
		//Gathering $_POST variables values according context or your personal rules
		
		//Sending Emails notifications if necessary BUT NOT in Spamming contexts! VICTORY !!!
		
		FBPP::destroyInternalCredentials();
	}	
?>
<!DOCTYPE />
<html lang="fr">
<head>
	<title>The (Filtered) Form</title>
	<meta name="Description" content="Une question sur votre activité de médecin libéral, sur votre activité de médecin remplaçant. Un besoin de conseil concernant vos cotisations sociales de médecin, votre comptabilité de médecin ? Média-santé et toute son équipe sont à votre disposition pour répondre à vos questions."/>
</head>
<body id="top">
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-9">
					<p>( All followed fields by * are required )</p>
					<br />
					<!-- CONTACT FORM -->
					<div class="contact-form-wrapper">
						<form id="form_page-contacts" class="form-horizontal margin-bottom-30px" method="POST">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="contact-name" class="control-label sr-only">Last Name</label>
										<input type="text" class="form-control" id="lastname" name="lastname" value="" placeholder="Your last name*" required />
									</div>
								</div>
                                <div class="col-sm-6">
									<div class="form-group">
										<label for="contact-forname" class="control-label sr-only">Prenom</label>
										<input type="text" class="form-control" id="firstname" name="firstname" placeholder="Your first name*" value="" required />
									</div>
								</div>
							</div>
							<div class="row">
                                <div class="col-sm-12">
									<div class="form-group">
										<label for="contact-address" class="control-label sr-only">Address</label>
										<input type="text" class="form-control" id="adresse" name="adress" placeholder="Your Addresse*" value="" required />
									</div>
								</div>
							</div>
							<div class="row">
                                <div class="col-sm-3">
									<div class="form-group">
										<label for="contact-cp" class="control-label sr-only">Postal Code</label>
										<input type="text" class="form-control" id="postalcode" name="postalcode" placeholder="Your Postal Code*" value="" required />
									</div>
								</div>
                                <div class="col-sm-9">
									<div class="form-group">
										<label for="contact-ville" class="control-label sr-only">City</label>
										<input type="text" class="form-control" id="city" name="city" placeholder="Your city*" value="" required />
									</div>
								</div>
							</div>
							<div class="row">
   								<div class="col-sm-6">
									<div class="form-group">
										<label for="contact-email" class="control-label sr-only">Email</label>
										<input type="email" class="form-control" id="email" name="email" value="" placeholder="Your Email*" required />
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="contact-tel" class="control-label sr-only">Phone number</label>
										<input type="tel" class="form-control" id="phonenumber" name="phonenumber"  placeholder="Your Phone number" value="" required />
										<input type="text" id="honeypot" name="honeypot" style="display:none;">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="contact-message" class="control-label sr-only">Message</label>
								<div class="col-sm-12">
									<textarea class="form-control" id="message" name="message" rows="5" cols="30" placeholder="Votre Message"></textarea>
								</div>
							</div>
							<input type="hidden" name="<?php echo $myfbpp->getHashInputForm(); ?>" id="<?php echo $myfbpp->getHashInputForm(); ?>" value="" />
							<input type="hidden" name="msg_submitted" id="msg_submitted" value="true" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- JAVASCRIPTS -->
	<script src="/_assets/js/jquery-2.1.1.min.js"></script>
	<?php echo "<script type=\"text/javascript\">
	 $(document).ready(function(){
		 
		 $.ajax({
			type: 'post',
			data: '_rfi='+$('input[id^=FBPP_]').attr('id'),
			url: 'https://www.yourwebsite.com/_wp".$myfbpp->getWp().".php',
			dataType : 'text',
			success: function(ans)
			{ let res=''; 
			if(ans != undefined) 
				res=ans.split('#');
			$('#FBPP_'+res[1]).val(res[0]);
			},
			error : function(xhr,ajaxOptions,thrownError){}
			});
	 });
	</script>"; ?>
<script src="/_assets/js/bootstrap.min.js"></script>
<script src="/_assets/js/validation.min.js"></script>
<script src="/_assets/js/custom_form_handler.js"></script>
</body>
</html>
