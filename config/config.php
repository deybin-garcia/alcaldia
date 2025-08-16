<?php
// config/config.php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'sistran_php');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('APP_NAME', 'SISTRAN');
function flash($key, $value=null){
  if(session_status()===PHP_SESSION_NONE) session_start();
  if($value!==null){ $_SESSION['flash'][$key]=$value; return; }
  $v=$_SESSION['flash'][$key]??null; unset($_SESSION['flash'][$key]); return $v;
}
?>