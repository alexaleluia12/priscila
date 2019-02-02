<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Priscila</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" 
     integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    
    
    <script src="<?= PATHHOST . 'assets/chart/dygraph.min.js' ?>"></script>
    <script src="<?= PATHHOST . 'assets/chart/synchronizer.js' ?>"></script>
    <link rel="stylesheet" href="<?= PATHHOST . 'assets/chart/dygraph.css' ?>" />
    
   <link rel="stylesheet" href="<?= PATHHOST . 'assets/css/style.css?q=4' ?>" />
   <link rel="stylesheet" href="<?= PATHHOST . 'assets/css/font-awesome.min.css' ?>" />
   
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-11" style="text-align: center; height: 5em;"><h1><?= $title ?></h1></div>
            <?php if(logedin()): ?>
            <div class="col-sm-1" style="text-align: center; height: 5em;">
                <p><a href="<?= site_url('User/logout') ?>">Sair</a></p>
                <p><a href="<?= site_url('User/account') ?>">Perfil</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>