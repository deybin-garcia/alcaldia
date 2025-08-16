<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/role_check.php';
require_once __DIR__ . '/../models/Persona.php';

if ($_SERVER['REQUEST_METHOD']==='POST' && is_admin()){
  $a = $_POST['accion'] ?? '';
  if ($a==='guardar'){ persona_save($_POST); flash('ok','Persona guardada'); }
  if ($a==='eliminar'){ persona_delete($_POST['id']); flash('ok','Persona eliminada'); }
  header("Location: ?page=personas&".http_build_query($_GET)); exit;
}
$filter = [
  'cedula'=>$_GET['cedula'] ?? '',
  'nombres'=>$_GET['nombres'] ?? '',
  'apellidos'=>$_GET['apellidos'] ?? '',
  'telefono'=>$_GET['telefono'] ?? ''
];
$rows = personas_all($filter);
$qstr = http_build_query(array_filter($filter));
?>
<div class="container py-3">
  <div class="card card-rounded p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2 sticky-actions">
      <h4 class="m-0"><i class="bi bi-person-vcard me-2"></i>Personas</h4>
      <div class="toolbar d-flex">
        <a class="btn btn-outline-secondary btn-sm btn-rounded" data-bs-toggle="collapse" href="#filtros"><i class="bi bi-funnel me-1"></i>Filtros</a>
        <a class="btn btn-outline-dark btn-sm btn-rounded" href="?page=personas"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar</a>
        <?php if(is_admin()): ?><a class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="collapse" href="#formPersona"><i class="bi bi-plus-circle me-1"></i>Nuevo</a><?php endif; ?>
        <a class="btn btn-success btn-sm btn-rounded" data-export href="?page=export&type=personas&fmt=excel&<?php echo $qstr; ?>"><i class="bi bi-download icon-export me-1"></i><span class="spinner-border spinner-border-sm spinner-export"></span> Excel</a>
        <a class="btn btn-outline-secondary btn-sm btn-rounded" target="_blank" data-export href="?page=export&type=personas&fmt=pdf&<?php echo $qstr; ?>"><i class="bi bi-filetype-pdf icon-export me-1"></i><span class="spinner-border spinner-border-sm spinner-export"></span> PDF</a>
      </div>
    </div>

    <div id="filtros" class="collapse mb-3">
      <form method="get" class="row g-2">
        <input type="hidden" name="page" value="personas">
        <div class="col-md-2 form-floating"><input class="form-control" id="f_cedula" name="cedula" value="<?php echo htmlspecialchars($filter['cedula']); ?>" placeholder="Cédula"><label for="f_cedula">Cédula</label></div>
        <div class="col-md-3 form-floating"><input class="form-control" id="f_nombres" name="nombres" value="<?php echo htmlspecialchars($filter['nombres']); ?>" placeholder="Nombres"><label for="f_nombres">Nombres</label></div>
        <div class="col-md-3 form-floating"><input class="form-control" id="f_apellidos" name="apellidos" value="<?php echo htmlspecialchars($filter['apellidos']); ?>" placeholder="Apellidos"><label for="f_apellidos">Apellidos</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" id="f_telefono" name="telefono" value="<?php echo htmlspecialchars($filter['telefono']); ?>" placeholder="Teléfono"><label for="f_telefono">Teléfono</label></div>
        <div class="col-md-2 d-grid"><button class="btn btn-primary btn-rounded"><i class="bi bi-funnel me-1"></i>Aplicar</button></div>
      </form>
    </div>

    <?php if(is_admin()): ?>
    <div id="formPersona" class="collapse">
      <form method="post" class="row g-2 needs-validation" novalidate>
        <input type="hidden" name="id" value=""><input type="hidden" name="accion" value="guardar">
        <div class="col-md-2 form-floating"><input class="form-control" name="cedula" placeholder="Cédula" required><label>Cédula</label><div class="invalid-feedback">Requerido</div></div>
        <div class="col-md-3 form-floating"><input class="form-control" name="nombres" placeholder="Nombres" required><label>Nombres</label><div class="invalid-feedback">Requerido</div></div>
        <div class="col-md-3 form-floating"><input class="form-control" name="apellidos" placeholder="Apellidos" required><label>Apellidos</label></div>
        <div class="col-md-2 form-floating"><input class="form-control" name="telefono" placeholder="Teléfono"><label>Teléfono</label></div>
        <div class="col-12 form-floating"><input class="form-control" name="direccion" placeholder="Dirección"><label>Dirección</label></div>
        <div class="col-12 d-grid d-md-block"><button class="btn btn-primary btn-rounded"><i class="bi bi-save me-1"></i>Guardar</button></div>
      </form>
    </div>
    <?php endif; ?>

    <div class="table-responsive mt-2">
      <table id="tblPersonas" class="table table-sm align-middle">
        <thead><tr><th>ID</th><th>Cédula</th><th>Nombres</th><th>Apellidos</th><th>Teléfono</th><th>Dirección</th><th>Acciones</th></tr></thead>
        <tbody>
          <?php foreach($rows as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td><td><?= htmlspecialchars($p['cedula']) ?></td><td><?= htmlspecialchars($p['nombres']) ?></td>
            <td><?= htmlspecialchars($p['apellidos']) ?></td><td><?= htmlspecialchars($p['telefono']) ?></td><td><?= htmlspecialchars($p['direccion']) ?></td>
            <td>
              <?php if(is_admin()): ?>
              <button class="btn btn-outline-primary btn-sm btn-rounded" data-bs-toggle="collapse" data-bs-target="#edit<?= $p['id'] ?>" title="Editar"><i class="bi bi-pencil-square"></i></button>
              <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar persona?')">
                <input type="hidden" name="id" value="<?= $p['id'] ?>"><input type="hidden" name="accion" value="eliminar">
                <button class="btn btn-outline-danger btn-sm btn-rounded" title="Eliminar"><i class="bi bi-trash"></i></button>
              </form>
              <?php else: ?><span class="text-muted small">Solo lectura</span><?php endif; ?>
            </td>
          </tr>
          <?php if(is_admin()): ?>
          <tr class="collapse" id="edit<?= $p['id'] ?>"><td colspan="7">
            <form method="post" class="row g-2 needs-validation" novalidate>
              <input type="hidden" name="id" value="<?= $p['id'] ?>"><input type="hidden" name="accion" value="guardar">
              <div class="col-md-2 form-floating"><input class="form-control" name="cedula" value="<?= htmlspecialchars($p['cedula']) ?>" required><label>Cédula</label></div>
              <div class="col-md-3 form-floating"><input class="form-control" name="nombres" value="<?= htmlspecialchars($p['nombres']) ?>" required><label>Nombres</label></div>
              <div class="col-md-3 form-floating"><input class="form-control" name="apellidos" value="<?= htmlspecialchars($p['apellidos']) ?>" required><label>Apellidos</label></div>
              <div class="col-md-2 form-floating"><input class="form-control" name="telefono" value="<?= htmlspecialchars($p['telefono']) ?>"><label>Teléfono</label></div>
              <div class="col-12 form-floating"><input class="form-control" name="direccion" value="<?= htmlspecialchars($p['direccion']) ?>"><label>Dirección</label></div>
              <div class="col-12"><button class="btn btn-primary btn-rounded"><i class="bi bi-save me-1"></i>Actualizar</button></div>
            </form>
          </td></tr>
          <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
