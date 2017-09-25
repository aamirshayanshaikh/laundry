<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//////////////////////////////////////////////////
/// Include your css or style sheets          ///
////////////////////////////////////////////////
$styleSheets = array();
$styleSheets[] = "bootstrap/css/bootstrap.min.css";
$styleSheets[] = "dist/font-awesome-4.5.0/css/font-awesome.min.css";
$styleSheets[] = "dist/css/ionicons.min.css";
$styleSheets[] = "dist/css/AdminLTE.css";
$styleSheets[] = "dist/css/skins/_all-skins.min.css";
$styleSheets[] = "dist/css/page.css";
$styleSheets[] = "plugins/perfect-scrollbar/css/perfect-scrollbar.css";
$styleSheets[] = "plugins/daterangepicker/daterangepicker-bs3.css";
$styleSheets[] = "plugins/datepicker/datepicker3.css";
$styleSheets[] = "plugins/bootstrap-select/css/bootstrap-select.min.css";
$config['incCss'] = $styleSheets;
////////////////////////////////////////////////
/// Include your js files                   ///
//////////////////////////////////////////////
$jsFiles = array();
$jsFiles[] = "plugins/jQuery/jQuery-2.1.3.min.js";
$jsFiles[] = "bootstrap/js/bootstrap.min.js";
$jsFiles[] = "plugins/slimScroll/jquery.slimScroll.min.js";
$jsFiles[] = "plugins/fastclick/fastclick.min.js";
$jsFiles[] = "plugins/noty/packaged/jquery.noty.packaged.min.js";
$jsFiles[] = "plugins/jquery.floatThead.js";
$jsFiles[] = "plugins/perfect-scrollbar/js/perfect-scrollbar.jquery.js";
$jsFiles[] = "plugins/bootbox.js";
$jsFiles[] = "plugins/daterangepicker/daterangepicker.js";
$jsFiles[] = "plugins/datepicker/bootstrap-datepicker.js";
$jsFiles[] = "plugins/bootstrap-select/js/bootstrap-select.min.js";
$jsFiles[] = "plugins/jquery.number.js";

$jsFiles[] = "dist/js/app.min.js";
$jsFiles[] = "dist/js/initial.js";
$jsFiles[] = "dist/js/helper.js";
$config['incJs'] = $jsFiles;
