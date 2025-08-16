<?php
require_once __DIR__ . '/lib_pdf.php';
require_once __DIR__ . '/../../models/Usuario.php';
$f=['usuario'=>$_GET['usuario']??'','rol'=>$_GET['rol']??''];
$rows = usuarios_all($f);
$title='Reporte de Usuarios'; $cols=['ID','Usuario','Rol'];
$data = array_map(function($r){ return [$r['id'],$r['usuario'],$r['rol']]; }, $rows);
pdf_simple_download('usuarios.pdf',$title,$cols,$data);
