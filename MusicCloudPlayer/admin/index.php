<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

// Role verification (only admin can access
if( $_SESSION['member']['role'] == '1' ){ 
header('Location: admin.php');     
}

// Error management
$error = $_REQUEST['error'];

if( $error == 'failed' ){ 
$error = '<div class="alert alert-error">Wrong login/password!</div>';     
}else{ 
$error = '';     
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow">
	<meta name="description" content="Pwaq Admin Panel: Controle music player">
	
	<title>Pwaq Admin Panel</title>

	<!-- CSS --> 
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">

	<!-- Jquery 1.8.2 --> 
	<script src="js/jquery-1.8.2.min.js"></script>
	
	<!-- Twitter Bootstrap --> 
	<script src="js/bootstrap.min.js"></script>
	<script src="js/core.js"></script>
	
	<!-- Plugins Tables --> 
	<script src="js/jquery.dataTables.js"></script> 
	<script src="js/jquery.dataTables.columnFilter.js"></script> 
	<script src="js/jqueryForm.js"></script>
	
    
</head>
<body>
	<div class="container">
	<?php echo $error; ?>
		<div class="content">
			<div class="row">
				<div class="login-form">
					<h2>Login</h2>
					<form action="<?php echo $config['siteurl']; ?>ss/user.php?action=login&page=admin" method="POST" >
						<fieldset>
							<div class="clearfix">
								<input type="text" name="u" required="" placeholder="Username">
							</div>
							<div class="clearfix">
								<input type="password" name="p" required="" placeholder="Password">
							</div>
							<button class="btn primary" type="submit">Sign in</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- /container -->
</body>
</html>
