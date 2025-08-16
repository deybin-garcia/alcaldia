<?php
require_once __DIR__ . '/../config/db.php';
function personas_all($f=[]) {
  $sql="SELECT * FROM personas WHERE 1=1";
  $p=[];
  if(!empty($f['cedula'])){ $sql.=" AND cedula LIKE ?"; $p[]='%'.$f['cedula'].'%'; }
  if(!empty($f['nombres'])){ $sql.=" AND nombres LIKE ?"; $p[]='%'.$f['nombres'].'%'; }
  if(!empty($f['apellidos'])){ $sql.=" AND apellidos LIKE ?"; $p[]='%'.$f['apellidos'].'%'; }
  if(!empty($f['telefono'])){ $sql.=" AND telefono LIKE ?"; $p[]='%'.$f['telefono'].'%'; }
  $sql.=" ORDER BY id ASC";
  $st=db()->prepare($sql); $st->execute($p); return $st->fetchAll();
}
function persona_get($id){ $st=db()->prepare("SELECT * FROM personas WHERE id=?"); $st->execute([$id]); return $st->fetch(); }
function persona_save($d){
  if (empty($d['id'])) { $st=db()->prepare("INSERT INTO personas (cedula,nombres,apellidos,telefono,direccion) VALUES (?,?,?,?,?)"); $st->execute([$d['cedula'],$d['nombres'],$d['apellidos'],$d['telefono'],$d['direccion']]); }
  else { $st=db()->prepare("UPDATE personas SET cedula=?,nombres=?,apellidos=?,telefono=?,direccion=? WHERE id=?"); $st->execute([$d['cedula'],$d['nombres'],$d['apellidos'],$d['telefono'],$d['direccion'],$d['id']]); }
}
function persona_delete($id){ $st=db()->prepare("DELETE FROM personas WHERE id=?"); $st->execute([$id]); }
?>