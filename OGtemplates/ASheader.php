<?php
// include 'ASEngine/AS.php';
// if(!$login->isLoggedIn())
//     header("Location: login.php");
// $user = new ASUser(ASSession::get("user_id"));
// $userInfo = $user->getInfo();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo ASLang::get('home'); ?> | Advanced Security</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Advanced Security - PHP MySQL Register/Login System">
    <meta name="author" content="Milos Stojanovic">
    
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <?php if(BOOTSTRAP_VERSION == 2): ?>
        <link rel='stylesheet' href='assets/css/bootstrap.min2.css' type='text/css' media='all' />
        <script type="text/javascript" src="assets/js/bootstrap.min2.js"></script>
        <link rel='stylesheet' href='ASLibrary/css/style2.css' type='text/css' media='all' />
    <?php else: ?>
        <link rel='stylesheet' href='assets/css/bootstrap.min3.css' type='text/css' media='all' />
        <script type="text/javascript" src="assets/js/bootstrap.min3.js"></script>
        <link rel='stylesheet' href='ASLibrary/css/style3.css' type='text/css' media='all' />
    <?php endif; ?>
    <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap3-glyphicons/bootstrap-glyphicons.css" rel="stylesheet">
	<script type="text/javascript" src="assets/js/respond.min.js"></script>
    <script type="text/javascript" charset="utf-8">
        var $_lang = <?php echo ASLang::all(); ?>;
    </script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

  </head>

  <body>
    <div id="wrap">














