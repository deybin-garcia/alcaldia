# SISTRAN – VERSION3 (Reestructurado UI)

Este paquete contiene la misma **lógica funcional** original (modelos, controladores y rutas), pero con una **interfaz modernizada** y estructura de assets organizada.

## Cambios clave
- `includes/header.php` y `includes/footer.php`: nueva cabecera, modo oscuro, toasts y layout consistente.
- `public/assets/custom.css`: estilos renovados (tablas con encabezado fijo, formularios, botones, modales).
- `public/assets/app.js`: toggle de tema, tooltips, estados de exportación.
- `auth/login.php`: formulario de inicio de sesión rediseñado con `form-floating` y validación.
- `includes/ui.php`: utilidades UI opcionales (p. ej. `empty_state()`).
- Controladores y modelos **no modifican su lógica**; el UI se mejora sin alterar consultas ni flujos.

## Ejecución
1. Configurar base de datos en `config/config.php` o variables de entorno.
2. Servir carpeta `public/` (ej.: `php -S localhost:8000 -t public`).
3. Primer acceso: si no existen usuarios, se crea `admin / admin123` al iniciar sesión.

## Estructura
- `public/` → punto de entrada (`index.php`) y assets.
- `controllers/`, `models/`, `includes/`, `auth/`, `exports/` → igual que el original.

## Notas
- No se añadieron dependencias nuevas.
- Puedes personalizar colores editando `:root` en `public/assets/custom.css`.
