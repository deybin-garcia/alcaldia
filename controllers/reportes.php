<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Vehiculo.php';

$uf = ['usuario'=>$_GET['usuario'] ?? '', 'rol'=>$_GET['rol'] ?? ''];
$pf = ['cedula'=>$_GET['cedula'] ?? '', 'nombres'=>$_GET['nombres'] ?? '', 'apellidos'=>$_GET['apellidos'] ?? '', 'telefono'=>$_GET['telefono'] ?? ''];
$vf = ['placa'=>$_GET['placa'] ?? '', 'tipo'=>$_GET['tipo'] ?? '', 'estado'=>$_GET['estado'] ?? '', 'desde'=>$_GET['desde'] ?? '', 'hasta'=>$_GET['hasta'] ?? ''];

$usuarios = usuarios_all($uf);
$personas = personas_all($pf);
$vehiculos = vehiculos_all($vf);

$uq = http_build_query(array_filter($uf));
$pq = http_build_query(array_filter($pf));
$vq = http_build_query(array_filter($vf));

function export_url($type, $fmt, $query) {
  $base = 'exports/router.php';
  $sep = $query ? '&' : '';
  return $base . '?type=' . urlencode($type) . '&fmt=' . urlencode($fmt) . ($query ? '&' . $query : '');
}
?>
<div class="container py-3 d-grid gap-3">

  <div class="card p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="m-0"><i class="bi bi-people me-1"></i>Reporte de Usuarios</h5>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="<?= export_url('usuarios','pdf',$uq) ?>">PDF</a>
        <a class="btn btn-success btn-sm" href="<?= export_url('usuarios','excel',$uq) ?>">Excel</a>
      </div>
    </div>
    <div class="table-responsive mt-2">
      <table class="table table-sm table-striped align-middle">
        <thead><tr><th>ID</th><th>Usuario</th><th>Rol</th></tr></thead>
        <tbody>
          <?php foreach ($usuarios as $r): ?>
            <tr><td><?= htmlspecialchars($r['id']) ?></td><td><?= htmlspecialchars($r['usuario']) ?></td><td><?= htmlspecialchars($r['rol']) ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="m-0"><i class="bi bi-person-badge me-1"></i>Reporte de Personas</h5>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="<?= export_url('personas','pdf',$pq) ?>">PDF</a>
        <a class="btn btn-success btn-sm" href="<?= export_url('personas','excel',$pq) ?>">Excel</a>
      </div>
    </div>
    <div class="table-responsive mt-2">
      <table class="table table-sm table-striped align-middle">
        <thead><tr><th>ID</th><th>Cédula</th><th>Nombres</th><th>Apellidos</th><th>Teléfono</th><th>Dirección</th></tr></thead>
        <tbody>
          <?php foreach ($personas as $p): ?>
            <tr><td><?= htmlspecialchars($p['id']) ?></td><td><?= htmlspecialchars($p['cedula']) ?></td><td><?= htmlspecialchars($p['nombres']) ?></td><td><?= htmlspecialchars($p['apellidos']) ?></td><td><?= htmlspecialchars($p['telefono']) ?></td><td><?= htmlspecialchars($p['direccion']) ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="m-0"><i class="bi bi-truck me-1"></i>Reporte de Vehículos</h5>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="<?= export_url('vehiculos','pdf',$vq) ?>">PDF</a>
        <a class="btn btn-success btn-sm" href="<?= export_url('vehiculos','excel',$vq) ?>">Excel</a>
      </div>
    </div>
    <div class="table-responsive mt-2">
      <table class="table table-sm table-striped align-middle">
        <thead>
          <tr><th>ID</th><th>Entrada</th><th>Salida</th><th>Propietario</th><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Placa</th><th>Días</th><th>Caja</th><th>Monto</th><th>Entregado por</th><th>Hora Entrega</th></tr>
        </thead>
        <tbody>
          <?php foreach ($vehiculos as $v): $owner = trim(($v['nombres']??'').' '.($v['apellidos']??'')); ?>
            <tr>
              <td><?= htmlspecialchars($v['id']) ?></td>
              <td><?= htmlspecialchars($v['fecha_entrada']) ?></td>
              <td><?= htmlspecialchars($v['fecha_salida'] ?? '') ?></td>
              <td><?= htmlspecialchars($owner ?: '—') ?></td>
              <td><?= htmlspecialchars($v['tipo_vehiculo']) ?></td>
              <td><?= htmlspecialchars($v['marca']) ?></td>
              <td><?= htmlspecialchars($v['modelo']) ?></td>
              <td><?= htmlspecialchars($v['placa']) ?></td>
              <td><?= htmlspecialchars($v['dias']) ?></td>
              <td><?= htmlspecialchars($v['numero_caja']) ?></td>
              <td><?= htmlspecialchars(number_format((float)$v['monto_total'],2)) ?></td>
              <td><?= htmlspecialchars($v['entregado_por']) ?></td>
              <td><?= htmlspecialchars($v['hora_entrega']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
