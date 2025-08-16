<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$page = $_GET['page'] ?? 'home';
// LOGIN_FIRST_GUARD
if (empty($_SESSION['user']) && $page !== 'login') {
  header('Location: ?page=login');
  exit;
}

// 1) Logout debe ejecutarse sin imprimir nada antes
if ($page === 'logout') {
  require_once __DIR__ . '/../auth/logout.php';
  exit;
}

// 2) Páginas protegidas: validar sesión ANTES de imprimir header
$protected = ['personas','vehiculos','usuarios','reportes'];
if (in_array($page, $protected, true)) {
  require_once __DIR__ . '/../includes/auth_check.php';
}

// 3) Renderizar layout
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

switch ($page) {
  case 'login':
    require_once __DIR__ . '/../auth/login.php';
    break;
  case 'personas':
    require_once __DIR__ . '/../controllers/personas.php';
    break;
  case 'vehiculos':
    require_once __DIR__ . '/../controllers/vehiculos.php';
    break;
  case 'usuarios':
    require_once __DIR__ . '/../controllers/users.php';
    break;
  case 'reportes':
    require_once __DIR__ . '/../controllers/reportes.php';
    break;
  default:
    echo "<div class='container mt-4'><h1>Bienvenido al Sistema de Transporte</h1></div>";
    break;
}

require_once __DIR__ . '/../includes/footer.php';
