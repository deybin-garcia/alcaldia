<?php
require_once __DIR__ . '/lib_pdf.php';
require_once __DIR__ . '/../../models/Vehiculo.php';
$f=['placa'=>$_GET['placa']??'','tipo'=>$_GET['tipo']??'','estado'=>$_GET['estado']??'','desde'=>$_GET['desde']??'','hasta'=>$_GET['hasta']??''];
$rows = vehiculos_all($f);
$title='Reporte de Vehículos'; $cols=['ID','Entrada','Salida','Propietario','Tipo','Marca','Modelo','Placa','Días','Caja','Monto','Entregado por','Hora Entrega'];
$data = array_map(function($v){ $owner=trim(($v['nombres']??'').' '.($v['apellidos']??'')); return [$v['id'],$v['fecha_entrada'],$v['fecha_salida']??'', $owner, $v['tipo_vehiculo'],$v['marca'],$v['modelo'],$v['placa'],$v['dias'],$v['numero_caja'],number_format((float)$v['monto_total'],2),$v['entregado_por'],$v['hora_entrega']]; }, $rows);
pdf_simple_download('vehiculos.pdf',$title,$cols,$data);
