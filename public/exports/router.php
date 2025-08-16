<?php
// exports/router.php
$type = $_GET['type'] ?? '';
$fmt  = $_GET['fmt'] ?? 'excel';
$allowed = ['usuarios','personas','vehiculos'];
if (!in_array($type,$allowed)) { http_response_code(400); exit('Tipo inválido'); }
$_EXPORT_FILTER = $_GET; // mantener filtros
if ($fmt==='excel') { include __DIR__ . "/{$type}_excel.php"; }
else { include __DIR__ . "/{$type}_pdf.php"; }
