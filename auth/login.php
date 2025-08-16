<?php
// auth/login.php
require_once __DIR__ . '/../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $usuario = trim($_POST['usuario'] ?? '');
  $contrasena = $_POST['contrasena'] ?? '';
  if ($usuario && $contrasena) {
    // Si no existe ningún usuario, crear admin por primera vez con admin/admin123
    $count = db()->query("SELECT COUNT(*) c FROM usuarios")->fetch()['c'] ?? 0;
    if ((int)$count === 0 && $usuario === 'admin' && $contrasena === 'admin123') {
      $hash = password_hash('admin123', PASSWORD_BCRYPT);
      $st = db()->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?,?,?)");
      $st->execute(['admin', $hash, 'Administrador']);
    }
    $stmt = db()->prepare("SELECT * FROM usuarios WHERE usuario=? LIMIT 1");
    $stmt->execute([$usuario]);
    $u = $stmt->fetch();
    if ($u && password_verify($contrasena, $u['contrasena'])) {
      $_SESSION['user'] = ['id'=>$u['id'], 'usuario'=>$u['usuario'], 'rol'=>$u['rol']];
      header("Location: ?page=personas"); exit;
    } else {
      $error = 'Credenciales inválidas';
    }
  } else { $error = 'Ingrese usuario y contraseña'; }
}
?>


<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
      <div class="card card-rounded shadow-sm p-4">
        <div class="text-center mb-3">
          <i class="bi bi-hexagon-fill text-primary display-6 d-block mb-2"></i>
          <h3 class="mb-0">Bienvenido</h3>
          <div class="text-muted">Inicia sesión para continuar</div>
        </div>
        <?php if(!empty($error)): ?>
          <div class="alert alert-danger alert-soft"><i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" class="needs-validation" novalidate>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required autofocus>
            <label for="usuario"><i class="bi bi-person me-1"></i>Usuario</label>
            <div class="invalid-feedback">Ingresa tu usuario</div>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required>
            <label for="contrasena"><i class="bi bi-lock me-1"></i>Contraseña</label>
            <div class="invalid-feedback">Ingresa tu contraseña</div>
          </div>
          <div class="d-grid gap-2">
            <button class="btn btn-primary btn-rounded py-2" type="submit"><i class="bi bi-box-arrow-in-right me-1"></i>Ingresar</button>
          </div>
        </form>
      </div>
      <div class="text-center small text-muted mt-3">© <?php echo date('Y'); ?> Alcaldía Municipal de Rosita</div>
    </div>
  </div>
</div>
<script>
// Bootstrap validation
(()=>{
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form=>{
    form.addEventListener('submit', event=>{
      if (!form.checkValidity()){ event.preventDefault(); event.stopPropagation(); }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>

