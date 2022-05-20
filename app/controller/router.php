<?php

include_once __DIR__ . '/../lib/conexion.php';
include_once __DIR__ . '/../models/dao/dao.php';

class controller {

    protected $pdo = null;

    public function inicio() {
        if (isset($_SESSION['idUsuario'])) {
            $mensaje = 'Bienvenido ' . $_SESSION['nombre'] . ' ' . $_SESSION['paterno'];
            $params = array(
                'mensaje' => $mensaje,
                'fecha' => date('Y-m-d H:i:s'),
            );
            require __DIR__ . '/../views/inicio.php';
        } else {
            header('Location: index.php');
        }
    }

    public function login() {
        require __DIR__ . '/../views/frmLogin.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
    }

    public function sesion() {
        $dao = new dao();
        $datos = $dao->validarUsuario($_POST['usuario'], $_POST['password']);
        if (!array_key_exists('error', $datos)) {
            ini_set("session.cookie_lifetime", 300);
            ini_set("session.gc_maxlifetime", 300);
            session_start();
            $_SESSION['idUsuario'] = $datos['idUsuario'];
            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['paterno'] = $datos['paterno'];
            $_SESSION['materno'] = $datos['materno'];
            $_SESSION['tipo'] = $datos['tipo'];
            $params = array(
                'mensaje' => 'Bienvenido' . $_SESSION['nombre'] . ' ' . $_SESSION['paterno'],
                'fecha' => date('d-m-Y'),
            );
            header('Location: index.php?ctl=inicio');
        } else {
            $params = array(
                'datos' => $datos['error']
            );
            require __DIR__ . '/../views/frmLogin.php';
        }
    }

    public function cargarLayout() {
        require __DIR__ . '/../views/frmCargarLayout.php';
    }
    
}
