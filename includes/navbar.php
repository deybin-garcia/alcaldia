<?php
?>
<!-- Botón Menú -->
<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      ☰ Menú
    </button>
  </div>
</nav>

<!-- Sidebar Offcanvas -->
<div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Navegación</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <a href="index.php?page=home" class="btn btn-outline-primary w-100 mb-2">Inicio</a>
    <a href="index.php?page=personas" class="btn btn-outline-primary w-100 mb-2">Personas</a>
    <a href="index.php?page=vehiculos" class="btn btn-outline-primary w-100 mb-2">Vehículos</a>
    <a href="index.php?page=usuarios" class="btn btn-outline-primary w-100 mb-2">Usuarios</a>
    <a href="index.php?page=reportes" class="btn btn-outline-primary w-100 mb-2">Reportes</a>
    <a href="index.php?page=login" class="btn btn-outline-secondary w-100 mb-2">Login</a>
    <a href="index.php?page=logout" class="btn btn-danger w-100 mb-2">Cerrar Sesión</a>
  </div>
</div>
