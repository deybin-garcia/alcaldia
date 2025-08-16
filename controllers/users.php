<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/role_check.php';
require_once __DIR__ . '/../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD']==='POST' && is_admin()){
  $a = $_POST['accion'] ?? '';
  if ($a==='crear'){ usuario_create($_POST['usuario'], $_POST['contrasena'], $_POST['rol']); flash('ok','Usuario creado'); }
  if ($a==='actualizar'){ usuario_update($_POST['id'], $_POST['usuario'], $_POST['contrasena'] ?? '', $_POST['rol']); flash('ok','Usuario actualizado'); }
  if ($a==='eliminar'){ usuario_delete($_POST['id']); flash('ok','Usuario eliminado'); }
  header("Location: ?page=usuarios&".http_build_query($_GET)); exit;
}
$filter = ['usuario'=>$_GET['usuario'] ?? '', 'rol'=>$_GET['rol'] ?? ''];
$rows = usuarios_all($filter);
$qstr = http_build_query(array_filter($filter));
?>
<div class="container py-3">
  <div class="card card-rounded p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2 sticky-actions">
      <h4 class="m-0"><i class="bi bi-people me-2"></i>Usuarios</h4>
      <div class="toolbar d-flex">
        <a class="btn btn-outline-secondary btn-sm btn-rounded" data-bs-toggle="collapse" href="#filtros"><i class="bi bi-funnel me-1"></i>Filtros</a>
        <a class="btn btn-outline-dark btn-sm btn-rounded" href="?page=usuarios"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar</a>
        <?php if(is_admin()): ?><button class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="modal" data-bs-target="#modalUsuario"><i class="bi bi-plus-circle me-1"></i>Nuevo</button><?php endif; ?>
        <a class="btn btn-success btn-sm btn-rounded" data-export href="?page=export&type=usuarios&fmt=excel&<?php echo $qstr; ?>"><i class="bi bi-download icon-export me-1"></i><span class="spinner-border spinner-border-sm spinner-export"></span> Excel</a>
        <a class="btn btn-outline-secondary btn-sm btn-rounded" target="_blank" data-export href="?page=export&type=usuarios&fmt=pdf&<?php echo $qstr; ?>"><i class="bi bi-filetype-pdf icon-export me-1"></i><span class="spinner-border spinner-border-sm spinner-export"></span> PDF</a>
      </div>
    </div>

    <div id="filtros" class="collapse mb-3">
      <form method="get" class="row g-2">
        <input type="hidden" name="page" value="usuarios">
        <div class="col-md-4 form-floating">
          <input class="form-control" id="f_usuario" name="usuario" value="<?php echo htmlspecialchars($filter['usuario']); ?>" placeholder="Usuario">
          <label for="f_usuario"><i class="bi bi-person me-1"></i>Usuario</label>
        </div>
        <div class="col-md-3 form-floating">
          <select class="form-select" id="f_rol" name="rol">
            <option value="">Todos</option>
            <option <?php echo $filter['rol']=='Administrador'?'selected':''; ?>>Administrador</option>
            <option <?php echo $filter['rol']=='Invitado'?'selected':''; ?>>Invitado</option>
          </select>
          <label for="f_rol"><i class="bi bi-shield-lock me-1"></i>Rol</label>
        </div>
        <div class="col-md-2 d-grid"><button class="btn btn-primary btn-rounded"><i class="bi bi-funnel me-1"></i>Aplicar</button></div>
      </form>
    </div>

    <div class="table-responsive">
      <table id="tblUsuarios" class="table table-sm align-middle">
        <thead><tr><th>ID</th><th>Usuario</th><th>Rol</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($rows as $r): ?>
          <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['usuario']) ?></td>
            <td><span class="badge badge-soft"><?= $r['rol'] ?></span></td>
            <td>
              <?php if(is_admin()): ?>
              <button class="btn btn-outline-primary btn-sm btn-rounded" data-bs-toggle="modal" data-bs-target="#modalUsuario"
                data-id="<?= $r['id'] ?>" data-usuario="<?= htmlspecialchars($r['usuario']) ?>" data-rol="<?= $r['rol'] ?>" title="Editar"><i class="bi bi-pencil-square"></i></button>
              <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?')">
                <input type="hidden" name="id" value="<?= $r['id'] ?>"><input type="hidden" name="accion" value="eliminar">
                <button class="btn btn-outline-danger btn-sm btn-rounded" title="Eliminar"><i class="bi bi-trash"></i></button>
              </form>
              <?php else: ?><span class="text-muted small">Solo lectura</span><?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if(is_admin()): ?>
<div class="modal fade" id="modalUsuario" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <form method="post" class="needs-validation" novalidate>
      <div class="modal-header"><h5 class="modal-title">Usuario</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body row g-2">
        <input type="hidden" name="id" id="u_id"><input type="hidden" name="accion" id="u_accion" value="crear">
        <div class="col-12 form-floating"><input class="form-control" name="usuario" id="u_usuario" placeholder="Usuario" required><label for="u_usuario">Usuario</label><div class="invalid-feedback">Requerido</div></div>
        <div class="col-12 form-floating"><input type="password" class="form-control" name="contrasena" id="u_contrasena" placeholder="Contraseña"><label for="u_contrasena">Contraseña</label></div>
        <div class="col-12 form-floating">
          <select class="form-select" name="rol" id="u_rol"><option>Administrador</option><option>Invitado</option></select>
          <label for="u_rol">Rol</label>
        </div>
        <div class="text-muted small">Si deja contraseña vacía al editar, se conserva la actual.</div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary btn-rounded"><i class="bi bi-save me-1"></i>Guardar</button></div>
    </form>
  </div></div>
</div>
<script>
const modal=document.getElementById('modalUsuario');
modal.addEventListener('show.bs.modal', e=>{
  const b=e.relatedTarget; const id=b?.getAttribute('data-id');
  u_id.value=id||''; u_usuario.value=b?.getAttribute('data-usuario')||''; u_rol.value=b?.getAttribute('data-rol')||'Invitado';
  u_contrasena.value=''; u_accion.value=id?'actualizar':'crear';
});
</script>
<?php endif; ?>
