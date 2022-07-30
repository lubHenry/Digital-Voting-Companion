<?php
global $abs_us_root,$us_url_root,$settings;
require_once($abs_us_root.$us_url_root.'users/includes/template/header1_must_include.php'); require_once($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/assets/fonts/glyphicons.php');
?>
<link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/paper-dashboard.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/fonts/glyphicons.css">
<link href="<?=$us_url_root?>users/css/datatables.css" rel="stylesheet">
<link rel="stylesheet" href="<?=$us_url_root?>users/fonts/css/font-awesome.min.css">
<script src="<?=$us_url_root?>users/js/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" crossorigin="anonymous"></script>

<link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/login.css">
<link rel="stylesheet" href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/timeline.css">
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/timeline.js"></script>



<?php
if(file_exists($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'.css')){?> <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>.css" rel="stylesheet"> <?php } ?>
</head>
<body>
<?php require_once($abs_us_root.$us_url_root.'users/includes/template/header3_must_include.php'); ?>
