<?php

ini_set("session.cookie_lifetime", 300);
ini_set("session.gc_maxlifetime", 300);
session_start();
require_once __DIR__ . '/../app/controller/router.php';

//enrutamiento 
$map = array(
    'inicio' => array('controller' => 'controller', 'action' => 'inicio'),
    'cargarLayout' => array('controller' => 'controller', 'action' => 'cargarLayout'),
    'consultarLayout' => array('controller' => 'controller', 'action' => 'consultarLayout'),
    'login' => array('controller' => 'controller', 'action' => 'login'),
    'logout' => array('controller' => 'controller', 'action' => 'logout'),
    'sesion' => array('controller' => 'controller', 'action' => 'sesion')
);

//Parse de enrutamiento
if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else {
        header('Status: 404 Not Found');
        echo '<html><body><h1>Error 404: No existe la ruta <i>' . $_GET['ctl'] . '</p></body></html>';
        exit;
    }
} else {
    if (isset($_SESSION['idUsuario'])) {
        $ruta = 'inicio';
    } else {
        $ruta = 'login';
    }
}

$controlador = $map[$ruta];
//ejecutar el controlador asociado a la ruta
if (method_exists($controlador['controller'], $controlador['action'])) {
    call_user_func(array(new $controlador['controller'], $controlador['action']));
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Error 404: El controlador <i>' . $controlador['controller'] .
    '->' . $controlador['action'] . '</i> no existe</h1></body></html>';
}