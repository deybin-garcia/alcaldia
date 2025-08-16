<?php
require_once __DIR__ . '/../config/db.php';

function usuarios_all($f=[]) {
  $sql="SELECT id, usuario, rol FROM usuarios WHERE 1=1";
  $p=[];
  if(!empty($f['usuario'])){ $sql.=" AND usuario LIKE ?"; $p[]='%'.$f['usuario'].'%'; }
  if(!empty($f['rol'])){ $sql.=" AND rol=?"; $p[]=$f['rol']; }
  $sql.=" ORDER BY id ASC";
  $st=db()->prepare($sql); $st->execute($p); return $st->fetchAll();
}
function usuario_get($id){ $st=db()->prepare("SELECT id, usuario, rol FROM usuarios WHERE id=?"); $st->execute([$id]); return $st->fetch(); }
function usuario_create($usuario, $contrasena, $rol){ $hash=password_hash($contrasena, PASSWORD_BCRYPT); $st=db()->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?,?,?)"); $st->execute([$usuario,$hash,$rol]); }
function usuario_update($id, $usuario, $contrasena, $rol){
  if ($contrasena) { $hash=password_hash($contrasena,PASSWORD_BCRYPT); $st=db()->prepare("UPDATE usuarios SET usuario=?, contrasena=?, rol=? WHERE id=?"); $st->execute([$usuario,$hash,$rol,$id]); }
  else { $st=db()->prepare("UPDATE usuarios SET usuario=?, rol=? WHERE id=?"); $st->execute([$usuario,$rol,$id]); }
}
function usuario_delete($id){ $st=db()->prepare("DELETE FROM usuarios WHERE id=?"); $st->execute([$id]); }
?>