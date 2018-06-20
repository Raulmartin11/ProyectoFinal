<?php
  session_start();

  if ($_SESSION['logueado'] == true && $_SESSION['admin']):

    include_once "conexion.php";

    if (isset($_POST['delete']))
      $Eliminar =  $lnk->query("DELETE FROM noticias WHERE idNoticia = '{$_POST['delete']}';");

    if (isset($_POST['search']) || (!isset($_POST['search']) == "")) {
     $Noticias = $lnk->query("SELECT * FROM noticias WHERE titulo LIKE '%{$_POST['search']}%' ORDER BY fecha DESC;");
    } else {
     $Noticias =  $lnk->query("SELECT * FROM noticias ORDER BY fecha DESC;");
    }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
    <link rel="stylesheet" type="text/css" href="css/admin.css"/>
    <link rel="stylesheet" type="text/css" href="css/estilo.css"/>

  </head>
  <body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
      <form action="noticia.php" method="POST">
        <button type="submit" class="btn btn-primary">New notice</button>
      </form>
      <form action="index.php" method="POST">
        <button type="submit" class="btn btn-primary" name="out">Log out</button>
      </form>
        <form action="index.php" method="POST">
          <button type="submit" class="btn btn-primary">Principal</button>
        </form>
      <a class="titulo" href="index.php"><h2>La Notisia</h2><img src="imagenes/1f44c.png" class="imgtitulo"/></a>
      <form class="form-inline" method="POST" action="admin.php" >
        <input class="form-control" type="text" placeholder="Search" name="search">
        <button class="btn btn-success" type="submit">Search</button>
      </form>
    </nav>
    <div id="main">
      <div class="jumbotron" style="text-align: center;">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th>Titulo</th>
              <th>Autor</th>
              <th>Fecha</th>
              <th>Portada</th>
              <th>Nº Comentarios</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php while ($Noticia = $Noticias->fetch_object()):?>
              <?php
                $usuarios = $lnk->query("SELECT nombre FROM usuarios WHERE idUsuario = $Noticia->idUsuario;");
                $usuario = $usuarios->fetch_object();
                $nComentarios = mysqli_num_rows($lnk->query("SELECT * FROM comentarios WHERE idNoticia = $Noticia->idNoticia;"));
              ?>
            <tr id="noticia_<?=$Noticia->idNoticia?>" align="center" data-idnoticia="<?=$Noticia->idNoticia?>">
              <td><?= $Noticia->titulo ?></td>
              <td><?= $usuario->nombre ?></td>
              <td><?= $Noticia->fecha ?></td>
              <?php if ($Noticia->portada): ?>
              <td>
                <img src="imagenes/<?= $Noticia->portada ?>" class="imgport"/>
              </td>
            <?php else: ?>
              <td>Null</td>
            <?php endif; ?>
              <td><?= $nComentarios ?></td>
                <form action="noticia.php" method="POST">
                  <td width="5%"><button type="submit" class="btn btn-primary" name="edit" value="<?= $Noticia->idNoticia ?>">Editar</button></td>
                </form>
              <td><button class="close" value="Borrar">&times;</button></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div id="dialogoborrar" title="Eliminar">
      <p>¿Esta seguro que desea eliminar la noticia?</p>
    </div>
    <script type="text/javascript">

      $(document).ready(function() {
        var idNoticia;
          //DIALOGO DE BORRADO
          $( "#dialogoborrar" ).dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            buttons: {
            //BOTON DE BORRAR
            "Borrar": function() {
              //Ajax con get
              $.get("borrar.php", {"idNoticia":idNoticia},function(data,status){
                $("#noticia_" + idNoticia).fadeOut(500);
              })//get
              //Cerrar la ventana de dialogo
              $(this).dialog("close");
            },
            "Cancelar": function() {
                //Cerrar la ventana de dialogo
                $(this).dialog("close");
            }
            }//buttons
          });

        //Evento click que pulsa el boton borrar
        $(document).on("click",".close",function(){
          //a traves del atributo idrecord del tr
          idNoticia = $(this).parents("tr").data("idnoticia");
          //Accion para mostrar el dialogo de borrar
           $( "#dialogoborrar" ).dialog("open");
        });
      //---------------------------------------------------
      });
      </script>
  </body>
</html>
<?php else:
  header("location: index.php");
  endif;
?>
