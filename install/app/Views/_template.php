<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SGFA <?= $_ENV['APP_VERSION'] ?></title>
    <link href="<?= assets('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
    <link href="<?= assets('css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-gradient-light m-auto">

<div class="container">
    <div id="ajaxloader" class="loader" hidden></div>
    <?= $this->section('content') ?>
</div>

<script src="<?= assets('vendor/jquery/jquery.js') ?>"></script>
<script src="<?= assets('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= assets('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= assets('js/sb-admin-2.min.js') ?>"></script>
<script src="<?= assets('js/functions.js') ?>"></script>
</body>

</html>