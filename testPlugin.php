<?php

/*
Plugin Name: testPlugin
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Este es un plugin de prueba
Author: Richard Huaman
Version: 0.0.1
*/

function activar()
{
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas(
        `encuestaid` INT NOT NULL AUTO_INCREMENT , 
        `nombre` VARCHAR(45) NULL , 
        `shortcode` VARCHAR(45) NULL , 
        PRIMARY KEY(encuestaid));";
    $wpdb->query($sql);

    $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_detalle(
        `detalleid` INT NOT NULL AUTO_INCREMENT , 
        `encuestaid` int NULL , 
        `pregunta` VARCHAR(45) NULL , 
        `tipo` VARCHAR(45) NULL , 
        PRIMARY KEY(detalleid));";
    $wpdb->query($sql2);

    $sql3 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_respuesta(
        `respuestaid` INT NOT NULL AUTO_INCREMENT , 
        `detalleid` int NULL , 
        `respuesta` VARCHAR(45) NULL , 
        PRIMARY KEY(respuestaid));";
    $wpdb->query($sql3);
}

function desactivar()
{
    # code...
}

echo 'Hola Plugin';

register_activation_hook(__FILE__, 'activar');
register_deactivation_hook(__FILE__, 'desactivar');

add_action('admin_menu', 'crearMenu');

function crearMenu()
{
    add_menu_page(
        'Super Encuestas', //titulo de la pagina
        'super encuestas Menu', //titulo del menu 
        'manage_options', //capabality (nievel de acceso) 
        plugin_dir_path(__FILE__) . 'admin/lista_encuestas.php', //slug 
        null, //funcion q muestra contenido de la  pagina
        plugin_dir_url(__FILE__) . 'admin/img/icon.png', //ruta de la imagen 
        1
    ); //posicion del menu
}
//encolar jquery actual
function encolarBootstrapJquery($slug)
{
    //echo "<script>console.log('$slug')</script>";
    if ($slug == 'testPlugin/admin/lista_encuestas.php') {
        wp_enqueue_script(
            'NewJquery', //.alias
            plugins_url(
                'admin/jquery/jquery.min.js', //ruta del archivo
                __FILE__ //desde q ubicacion se ejecutara
            )
        );
    }
}
add_action('admin_enqueue_scripts', 'encolarBootstrapJquery');

//encolar bootstrap
function encolarBootstrapJs($slug)
{
    //echo "<script>console.log('$slug')</script>";
    if ($slug == 'testPlugin/admin/lista_encuestas.php') {
        wp_enqueue_script(
            'bootstrapJs', //.alias
            plugins_url(
                'admin/bootstrap/js/bootstrap.min.js', //ruta del archivo
                __FILE__ //desde q ubicacion se ejecutara
            ),
            array('jquery'), //dependencias
        );
    }
}
add_action('admin_enqueue_scripts', 'encolarBootstrapJs');

function encolarBootstrapCss($slug)
{
    //echo "<script>console.log('$slug')</script>";
    if ($slug == 'testPlugin/admin/lista_encuestas.php') {
        wp_enqueue_style(
            'bootstrapCss', //.alias
            plugins_url(
                'admin/bootstrap/css/bootstrap.min.css', //ruta del archivo
                __FILE__ //desde q ubicacion se ejecutara
            )
        );
    }
}
//agregamos a wordpress
//TODO: add_action envia el hook en la funcion de forma automatica
//por ello podemos obtener esa informacion en un parametro
add_action('admin_enqueue_scripts', 'encolarBootstrapCss');

//encolar js propio
function encolar_js($slug)
{
    //echo "<script>console.log('$slug')</script>";
    if ($slug == 'testPlugin/admin/lista_encuestas.php') {
        wp_enqueue_script(
            'JsExterno', //.alias
            plugins_url(
                'admin/js/lista_encuesta.js', //ruta del archivo
                __FILE__ //desde q ubicacion se ejecutara
            ),
            array('jquery')
        );

        wp_localize_script(
            'JsExterno', //alias del js asociado
            'solicitudAjax', //nombre del objeto q usaremos en el js
            [
                'url' => admin_url('admin-ajax.php'), //archivo propio de wp q adminsitra peticones ajax
                'seguridad' => wp_create_nonce('seg')  // creamos un token de seguridad , parametro seg e sun alias a usar               
            ]
        );
        wp_localize_script(
            'JsExterno', //alias del js asociado
            'solicitudAjax_Guardar', //nombre del objeto q usaremos en el js
            [
                'url' => admin_url('admin-ajax.php'), //archivo propio de wp q adminsitra peticones ajax
                'seguridad' => wp_create_nonce('seg')  // creamos un token de seguridad , parametro seg e sun alias a usar               
            ]
        );
    }
}
//agregamos a wordpress
//TODO: add_action envia el hook en la funcion de forma automatica
//por ello podemos obtener esa informacion en un parametro
add_action('admin_enqueue_scripts', 'encolar_js');

//ajax

function eliminarEncuesta()
{
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'seg')) {
        die('No tiene permisos para ejecutar ese ajax');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuestas_detalle";

    $wpdb->delete($tabla, ['encuestaid' => $id]);
    $wpdb->delete($tabla2, ['encuestaid' => $id]);
    return true;
}
//TODO: wp_ajax_ es un alias , peticionEliminar es el nombre q se envia 
//en el ajax datos {action = peticionEliminar}
add_action(
    'wp_ajax_peticionEliminar',
    'eliminarEncuesta',
);

//jax guardar

function guardarEncuesta()
{
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'seg')) {
        die('No tiene permisos para ejecutar ese ajax');
    }
    global $wpdb;
    //print_r($_POST);
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuestas_detalle";

    $nombre = $_POST['newTask'];
    $query = "SELECT max(`encuestaid`)as id FROM $tabla";
    $id_result = $wpdb->get_results($query, ARRAY_A);
    $new_id = $id_result[0]['id'] + 1;
    $new_shortCode = '[ENC_' . $new_id . ']';
    $datos = [
        'encuestaid' => null,
        'nombre' => $nombre,
        'shortcode' => $new_shortCode
    ];

    $confirm = $wpdb->insert($tabla, $datos);

    if ($confirm) {
        $preguntas = $_POST['questions'];
        foreach ($preguntas as $key => $value) {
            $datos_detalle = [
                'detalleid' => null,
                'encuestaid' => $new_id,
                'pregunta' => $value,
                'tipo' => $_POST['types'][$key]
            ];
            // print_r($datos_detalle);
            $confirm2 = $wpdb->insert($tabla2, $datos_detalle);
        }
    }
    return $confirm2;
}
//TODO: wp_ajax_ es un alias , peticionEliminar es el nombre q se envia 
//en el ajax datos {action = peticionEliminar}
add_action(
    'wp_ajax_peticionGuardar',
    'guardarEncuesta',
);
