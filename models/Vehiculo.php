<?php
require_once __DIR__ . '/../config/db.php';

function vehiculos_all($f=[]) {
  $sql="SELECT v.*, p.nombres, p.apellidos FROM vehiculos v LEFT JOIN personas p ON p.id=v.propietario_id WHERE 1=1";
  $p=[];
  if(!empty($f['placa'])){ $sql.=" AND v.placa LIKE ?"; $p[]='%'.$f['placa'].'%'; }
  if(!empty($f['tipo'])){ $sql.=" AND v.tipo_vehiculo LIKE ?"; $p[]='%'.$f['tipo'].'%'; }
  if(!empty($f['estado'])){
    if($f['estado']=='resguardo'){ $sql.=" AND v.fecha_salida IS NULL"; }
    if($f['estado']=='entregado'){ $sql.=" AND v.fecha_salida IS NOT NULL"; }
  }
  if(!empty($f['propietario'])){ $sql.=" AND CONCAT(COALESCE(p.nombres,''),' ',COALESCE(p.apellidos,'')) LIKE ?"; $p[]='%'.$f['propietario'].'%'; }
  if(!empty($f['desde'])){ $sql.=" AND v.fecha_entrada >= ?"; $p[]=$f['desde']; }
  if(!empty($f['hasta'])){ $sql.=" AND v.fecha_entrada <= ?"; $p[]=$f['hasta']; }
  $sql.=" ORDER BY v.id ASC";
  $st=db()->prepare($sql); $st->execute($p); return $st->fetchAll();
}
function vehiculo_get($id){ $st=db()->prepare("SELECT * FROM vehiculos WHERE id=?"); $st->execute([$id]); return $st->fetch(); }
function vehiculo_save($d){
  if (empty($d['id'])) {
    $st=db()->prepare("INSERT INTO vehiculos (fecha_entrada,propietario_id,tipo_vehiculo,marca,modelo,motor,chasis,placa) VALUES (?,?,?,?,?,?,?,?)");
    $st->execute([$d['fecha_entrada'],$d['propietario_id']?:NULL,$d['tipo_vehiculo'],$d['marca'],$d['modelo'],$d['motor'],$d['chasis'],$d['placa']]);
  } else {
    $st=db()->prepare("UPDATE vehiculos SET fecha_entrada=?,propietario_id=?,tipo_vehiculo=?,marca=?,modelo=?,motor=?,chasis=?,placa=? WHERE id=?");
    $st->execute([$d['fecha_entrada'],$d['propietario_id']?:NULL,$d['tipo_vehiculo'],$d['marca'],$d['modelo'],$d['motor'],$d['chasis'],$d['placa'],$d['id']]);
  }
}
function vehiculo_delete($id){ $st=db()->prepare("DELETE FROM vehiculos WHERE id=?"); $st->execute([$id]); }
function vehiculo_alta($id,$fecha_salida,$numero_caja,$entregado_por,$hora_entrega){
  $v=vehiculo_get($id); if(!$v) return;
  $entrada=new DateTime($v['fecha_entrada']); $salida=new DateTime($fecha_salida);
  $diff=$salida->diff($entrada); $dias=max(0,(int)ceil($diff->days)); $monto=$dias*100;
  $st=db()->prepare("UPDATE vehiculos SET fecha_salida=?, dias=?, monto_total=?, numero_caja=?, entregado_por=?, hora_entrega=? WHERE id=?");
  $st->execute([$fecha_salida,$dias,$monto,$numero_caja,$entregado_por?:NULL,$hora_entrega,$id]);
}
?>