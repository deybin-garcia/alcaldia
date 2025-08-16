<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/role_check.php';
require_once __DIR__ . '/../models/Vehiculo.php';
require_once __DIR__ . '/../models/Persona.php';

if ($_SERVER['REQUEST_METHOD']==='POST' && is_admin()){
  $a = $_POST['accion'] ?? '';
  if ($a==='guardar'){ vehiculo_save($_POST); flash('ok','Vehículo guardado'); }
  if ($a==='eliminar'){ vehiculo_delete($_POST['id']); flash('ok','Vehículo eliminado'); }
  if ($a==='alta'){ vehiculo_alta($_POST['id'], $_POST['fecha_salida'], $_POST['numero_caja'], $_SESSION['user']['id'] ?? null, date('H:i:s')); flash('ok','Vehículo dado de alta'); }
  header("Location: ?page=vehiculos&".http_build_query($_GET)); exit;
}
$filter = [
  'placa'=>$_GET['placa'] ?? '',
  'tipo'=>$_GET['tipo'] ?? '',
  'estado'=>$_GET['estado'] ?? '',
  'propietario'=>$_GET['propietario'] ?? '',
  'desde'=>$_GET['desde'] ?? '',
  'hasta'=>$_GET['hasta'] ?? '',
];
$vehiculos = vehiculos_all($filter);
$personas = personas_all();
$qstr = http_build_query(array_filter($filter));
?>
<div class="container py-3">
  <div class="card card-rounded p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2 sticky-actions">
      <h4 class="m-0"><i class="bi bi-truck-front me-2"></i>Vehículos</h4>
      <div class="toolbar d-flex">
        <a class="btn btn-outline-secondary btn-sm btn-rounded" data-bs-toggle="collapse" href="#filtros"><i class="bi bi-funnel me-1"></i>Filtros</a>
        <a class="btn btn-outline-dark btn-sm btn-rounded" href="?page=vehiculos"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar</a>
        <?php if(is_admin()): ?><a class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="collapse" href="#formVehiculo"><i class="bi bi-plus-circle me-1"></i>Nuevo</a><?php endif; ?>
        <a class="btn btn-success btn-sm btn-rounded" data-export href="?page=export&type=vehiculos&fmt=excel&<?php echo $qstr; ?>"><i class="bi bi-download icon-export me-1"></i><span class="spinner-border spinner-border-sm spinner-export"></span> Excel</a>
        <a class="btn btn-outline-secondary btn-sm btn-rounded" target="_blank" data-export href="?page=export&type=vehiculos&fmt=pdf&<?php echo $qstr; ?>"><i class="bi bi-filetype-pdf icon-export me-1"></i><span class="spinner-border spinner-border-sm spinner-export"></span> PDF</a>
      </div>
    </div>

    <div id="filtros" class="collapse mb-3">
      <form method="get" class="row g-2">
        <input type="hidden" name="page" value="vehiculos">
        <div class="col-md-2 form-floating"><input class="form-control" id="f_placa" name="placa" value="<?php echo htmlspecialchars($filter['placa']); ?>" placeholder="Placa"><label for="f_placa">Placa</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" id="f_tipo" name="tipo" value="<?php echo htmlspecialchars($filter['tipo']); ?>" placeholder="Tipo"><label for="f_tipo">Tipo</label></div>
        <div class="col-md-2 form-floating">
          <select class="form-select" id="f_estado" name="estado">
            <option value="">Todos</option>
            <option value="resguardo" <?php echo $filter['estado']=='resguardo'?'selected':''; ?>>En resguardo</option>
            <option value="entregado" <?php echo $filter['estado']=='entregado'?'selected':''; ?>>Entregado</option>
          </select>
          <label for="f_estado">Estado</label>
        </div>
        <div class="col-md-3 form-floating"><input class="form-control" id="f_prop" name="propietario" value="<?php echo htmlspecialchars($filter['propietario']); ?>" placeholder="Propietario"><label for="f_prop">Propietario</label></div>
        <div class="col-md-1 form-floating"><input type="datetime-local" class="form-control" id="f_desde" name="desde" value="<?php echo htmlspecialchars($filter['desde']); ?>" placeholder="Desde"><label for="f_desde">Desde</label></div>
        <div class="col-md-1 form-floating"><input type="datetime-local" class="form-control" id="f_hasta" name="hasta" value="<?php echo htmlspecialchars($filter['hasta']); ?>" placeholder="Hasta"><label for="f_hasta">Hasta</label></div>
        <div class="col-md-1 d-grid"><button class="btn btn-primary btn-rounded"><i class="bi bi-funnel me-1"></i>Aplicar</button></div>
      </form>
    </div>

    <?php if(is_admin()): ?>
    <div id="formVehiculo" class="collapse">
      <form method="post" class="row g-2 needs-validation" novalidate>
        <input type="hidden" name="accion" value="guardar">
        <div class="col-md-3 form-floating"><input type="datetime-local" class="form-control" name="fecha_entrada" value="<?php echo date('Y-m-d\TH:i'); ?>" required><label>Fecha Entrada</label><div class="invalid-feedback">Requerido</div></div>
        <div class="col-md-3 form-floating">
          <select class="form-select" name="propietario_id"><option value="">—</option>
            <?php foreach($personas as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombres'].' '.$p['apellidos']) ?></option><?php endforeach; ?>
          </select>
          <label>Propietario</label>
        </div>
        <div class="col-md-2 form-floating"><input class="form-control" name="tipo_vehiculo" placeholder="Tipo"><label>Tipo</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" name="marca" placeholder="Marca"><label>Marca</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" name="modelo" placeholder="Modelo"><label>Modelo</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" name="motor" placeholder="Motor"><label>Motor</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" name="chasis" placeholder="Chasis"><label>Chasis</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" name="placa" placeholder="Placa"><label>Placa</label></div>
        <div class="col-12"><button class="btn btn-primary btn-rounded"><i class="bi bi-save me-1"></i>Guardar</button></div>
      </form>
    </div>
    <?php endif; ?>

    <div class="table-responsive mt-2">
      <table id="tblVehiculos" class="table table-sm align-middle">
        <thead><tr><th>ID</th><th>Entrada</th><th>Propietario</th><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Motor</th><th>Chasis</th><th>Placa</th><th>Estado</th><th>Días</th><th>Monto</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach($vehiculos as $v): $owner=trim(($v['nombres']??'').' '.($v['apellidos']??'')); $estado=$v['fecha_salida']?'Entregado':'En resguardo'; ?>
          <tr>
            <td><?= $v['id'] ?></td>
            <td><?= htmlspecialchars($v['fecha_entrada']) ?></td>
            <td><?= $owner ?: '—' ?></td>
            <td><?= htmlspecialchars($v['tipo_vehiculo']) ?></td>
            <td><?= htmlspecialchars($v['marca']) ?></td>
            <td><?= htmlspecialchars($v['modelo']) ?></td>
            <td><?= htmlspecialchars($v['motor']) ?></td>
            <td><?= htmlspecialchars($v['chasis']) ?></td>
            <td><?= htmlspecialchars($v['placa']) ?></td>
            <td><span class="badge <?php echo $estado==='En resguardo'?'text-bg-warning':'text-bg-success'; ?>"><?= $estado ?></span></td>
            <td><?= (int)$v['dias'] ?></td>
            <td><?= number_format((float)$v['monto_total'],2) ?></td>
            <td>
              <?php if(is_admin()): ?>
              <button class="btn btn-outline-primary btn-sm btn-rounded" data-bs-toggle="collapse" data-bs-target="#edit<?= $v['id'] ?>" title="Editar"><i class="bi bi-pencil-square"></i></button>
              <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar vehículo?')">
                <input type="hidden" name="id" value="<?= $v['id'] ?>"><input type="hidden" name="accion" value="eliminar">
                <button class="btn btn-outline-danger btn-sm btn-rounded" title="Eliminar"><i class="bi bi-trash"></i></button>
              </form>
              <?php if(!$v['fecha_salida']): ?>
              <a class="btn btn-outline-success btn-sm btn-rounded" data-bs-toggle="collapse" href="#alta<?= $v['id'] ?>" title="Dar de alta"><i class="bi bi-box-arrow-up"></i></a>
              <?php endif; else: ?><span class="text-muted small">Solo lectura</span><?php endif; ?>
            </td>
          </tr>
          <?php if(is_admin()): ?>
          <tr class="collapse" id="edit<?= $v['id'] ?>"><td colspan="13">
            <form method="post" class="row g-2">
              <input type="hidden" name="accion" value="guardar"><input type="hidden" name="id" value="<?= $v['id'] ?>">
              <div class="col-md-3 form-floating"><input type="datetime-local" class="form-control" name="fecha_entrada" value="<?= date('Y-m-d\TH:i', strtotime($v['fecha_entrada'])) ?>"><label>Fecha Entrada</label></div>
              <div class="col-md-3 form-floating">
                <select class="form-select" name="propietario_id"><option value="">—</option>
                  <?php foreach($personas as $p): ?><option value="<?= $p['id'] ?>" <?= $p['id']==$v['propietario_id']?'selected':'' ?>><?= htmlspecialchars($p['nombres'].' '.$p['apellidos']) ?></option><?php endforeach; ?>
                </select>
                <label>Propietario</label>
              </div>
              <div class="col-md-2 form-floating"><input class="form-control" name="tipo_vehiculo" value="<?= htmlspecialchars($v['tipo_vehiculo']) ?>"><label>Tipo</label></div>
              <div class="col-md-2 form-floating"><input class="form-control" name="marca" value="<?= htmlspecialchars($v['marca']) ?>"><label>Marca</label></div>
              <div class="col-md-2 form-floating"><input class="form-control" name="modelo" value="<?= htmlspecialchars($v['modelo']) ?>"><label>Modelo</label></div>
              <div class="col-md-2 form-floating"><input class="form-control" name="motor" value="<?= htmlspecialchars($v['motor']) ?>"><label>Motor</label></div>
              <div class="col-md-2 form-floating"><input class="form-control" name="chasis" value="<?= htmlspecialchars($v['chasis']) ?>"><label>Chasis</label></div>
              <div class="col-md-2 form-floating"><input class="form-control" name="placa" value="<?= htmlspecialchars($v['placa']) ?>"><label>Placa</label></div>
              <div class="col-12"><button class="btn btn-primary btn-rounded"><i class="bi bi-save me-1"></i>Actualizar</button></div>
            </form>
          </td></tr>

          <tr class="collapse" id="alta<?= $v['id'] ?>"><td colspan="13">
            <form method="post" class="row g-2">
              <input type="hidden" name="accion" value="alta"><input type="hidden" name="id" value="<?= $v['id'] ?>">
              <div class="col-md-3 form-floating"><input type="datetime-local" class="form-control" name="fecha_salida" value="<?php echo date('Y-m-d\TH:i'); ?>" required><label>Fecha Salida</label></div>
              <div class="col-md-3 form-floating"><input class="form-control" name="numero_caja" required placeholder="Número de caja"><label>Número de caja</label></div>
              <div class="col-md-3 d-grid"><button class="btn btn-success btn-rounded"><i class="bi bi-check2-circle me-1"></i>Confirmar alta</button></div>
            </form>
          </td></tr>
          <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
