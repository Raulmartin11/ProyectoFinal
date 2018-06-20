<?php
include_once "conexion.php";

$Eliminar = $lnk->query("DELETE FROM comentarios WHERE idComentario = '{$_GET['idComentario']}';");
?>
