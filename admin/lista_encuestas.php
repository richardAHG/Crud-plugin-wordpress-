<?php
global $wpdb;
// $tabla = "{$wpdb->prefix}encuestas";
// $tabla2 = "{$wpdb->prefix}encuestas_detalle";

// if (isset($_POST['btnSave'])) {
 
//  $nombre = $_POST['newTask'];
// $query = "SELECT max(`encuestaid`)as id FROM $tabla";
// $id_result =$wpdb->get_results($query,ARRAY_A);
// $new_id = $id_result[0]['id']+1;
// $new_shortCode ='[ENC_'.$new_id.']';
// $datos = [
//   'encuestaid' => null,
//   'nombre' => $nombre,
//   'shortcode' => $new_shortCode
// ];

// $confirm = $wpdb->insert($tabla,$datos);

// if ($confirm) {
//   $preguntas = $_POST['questions'];
//   foreach ($preguntas as $key => $value) {
//     $datos_detalle = [
//       'detalleid' => null,
//       'encuestaid' => $new_id,
//       'pregunta' => $value,
//       'tipo' =>$_POST['types'][$key]
//     ];
//     // print_r($datos_detalle);
//     $confirm2 = $wpdb->insert($tabla2,$datos_detalle);
//   }
// }
// }


$query = "SELECT * from {$wpdb->prefix}encuestas";
//get_results() devuelve un objeto, 
//si se envia el segundo parametro devuele un array asociativo
$listaEncuesta = $wpdb->get_results($query, ARRAY_A);
if (empty($listaEncuesta)) {
  $listaEncuesta = [];
}
?>
<div class="wrap">
  <?php
  echo '<h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>';
  ?>
  <a id="btnNuevo" class="page-title-action">Añadir nuevo</a>
  <br>
  <div id="tblEncuestas">

  
  <table class="table table-hover table-stripped" >
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Shorcode</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($listaEncuesta as $key => $value) {
        $id = $value['encuestaid'];
        $nombre = $value['nombre'];
        $shorcode = $value['shortcode'];
        echo '<tr>
                    <td scope="row">' . $nombre . '</td>
                    <td>' . $shorcode . '</td>
                    <td>
                    <a class="page-title-action">Ver estadistica</a>
                    <a data-id='.$id.' class="page-title-action borrar">Borrar</a>
                    </td>
                </tr>';
      }
      ?>


    </tbody>
  </table>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post"  id="FrmTask"  enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-lg-12 col-md-12">
              <label for="">Ingrese nueva encuesta</label>
              <input type="text" class="form-control" id="newTask"  name="newTask">
            </div>
            <div class="form-group col-lg-12 col-md-12">
              <label for="">Preguntas</label>
              <button class="btn btn-primary btn-sm" id="btnNewquestions">+</button>
            </div>
          </div>

          <div class="row" id="ContentQuestions">
                
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" id="btnSave">Guardar</button>
          </div>

        </div>
      </form>

    </div>
  </div>
</div>