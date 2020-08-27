<?php

/*
Plugin Name: testPlugin_old
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Este es un plugin de prueba
Author: Richard Huaman
Version: 0.0.1
*/

function activar()
{
    
}

function desactivar()
{
    # code...
}

echo 'Hola Plugin';

register_activation_hook(__FILE__, 'activar');
register_deactivation_hook(__FILE__, 'desactivar');

add_action( 'admin_menu', 'crearMenu');

function crearMenu()
{
    add_menu_page( 'Super Encuestas', //titulo de la pagina
                     'super encuestas Menu', //titulo del menu 
                     'manage_options', //capabality (nievel de acceso) 
                     'sp_menu', //slug 
                     'mostrarContenido', //funcion q muestra contenido de la  pagina
                     plugin_dir_url( __FILE__ ).'admin/img/icon.png', //ruta de la imagen 
                     1 ); //posicion del menu


    // //funcion para agregr submenus

    // add_submenu_page( 'sp_menu', // slug del padre
    //                   'ajustes', //titulo de la pagina
    //                    'Ajustes', //titulo del emnu
    //                     'manage_options', //capabality
    //                      'sp_menu_ajustes', //slug
    //                       'submenu', //funcion
    //                 );
}

function mostrarContenido()
{
    echo '<h1>Contenidpo de la pagina</h1>';
}

// function submenu()
// {
//     echo '<h1>submenu</h1>';
// }
?>