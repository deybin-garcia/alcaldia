<?php
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/lib_excel.php';
$f=['usuario'=>$_GET['usuario']??'','rol'=>$_GET['rol']??''];
$rows = usuarios_all($f);
$data = array_map(function($r){ return [$r['id'],$r['usuario'],$r['rol']]; }, $rows);
excel_xml_download('usuarios.xls', 'Usuarios', ['ID','Usuario','Rol'], $data);