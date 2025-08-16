<?php
require_once __DIR__ . '/lib_pdf.php';
require_once __DIR__ . '/../../models/Persona.php';
$f=['cedula'=>$_GET['cedula']??'','nombres'=>$_GET['nombres']??'','apellidos'=>$_GET['apellidos']??'','telefono'=>$_GET['telefono']??''];
$rows = personas_all($f);
$title='Reporte de Personas'; $cols=['ID','Cédula','Nombres','Apellidos','Teléfono','Dirección'];
$data = array_map(function($p){ return [$p['id'],$p['cedula'],$p['nombres'],$p['apellidos'],$p['telefono'],$p['direccion']]; }, $rows);
pdf_simple_download('personas.pdf',$title,$cols,$data);
