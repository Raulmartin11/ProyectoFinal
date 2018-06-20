<?php
include_once "conexion.php";

$Eliminar = $lnk->query("DELETE FROM noticias WHERE idNoticia = '{$_GET['idNoticia']}';");
?>
