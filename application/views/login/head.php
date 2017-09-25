<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php if(isset($comp_name)) echo $comp_name; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>dist/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo base_url(); ?>dist/img/favicon.ico" type="image/x-icon">
    <?php
        if(isset($css))
            echo $css;
    ?>
    <?php
        if(isset($add_css)){
            if(is_array($add_css)){
                foreach ($add_css as $path) {
                    echo "<link href='".base_url().$path."' rel='stylesheet'>\n";
                }
            }
            else
                echo "<link href='".base_url().$add_css."' rel='stylesheet'>\n";
        }
    ?>
  </head>
  <body class="login-page">