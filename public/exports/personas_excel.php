<?php
require_once __DIR__ . '/../../models/Persona.php';
require_once __DIR__ . '/lib_excel.php';
$f=['cedula'=>$_GET['cedula']??'','nombres'=>$_GET['nombres']??'','apellidos'=>$_GET['apellidos']??'','telefono'=>$_GET['telefono']??''];
$rows = personas_all($f);
$data = array_map(function($p){ return [$p['id'],$p['cedula'],$p['nombres'],$p['apellidos'],$p['telefono'],$p['direccion']]; }, $rows);
excel_xml_download('personas.xls', 'Personas', ['ID','Cédula','Nombres','Apellidos','Teléfono','Dirección'], $data);