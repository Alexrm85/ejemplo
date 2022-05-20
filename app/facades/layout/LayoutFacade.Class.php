<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["idUsuario"]) && $_SESSION["idUsuario"] !== "") {
    include_once __DIR__ . '/../../controller/layout/LayoutController.Class.php';

    class LayoutFacade {

        public function enviarLayout($file) {
            $layoutController = new LayoutController();
            $rs = $layoutController->enviarLayout($file);
            return $rs;
        }
        
        public function cargarGeneros($params){
            $layoutController = new LayoutController();
            $rs = $layoutController->cargarGeneros($params);
            return $rs;
        }
        
        public function consultarRegistros($params){
            $layoutController = new LayoutController();
            $rs = $layoutController->consultarRegistros($params);
            return $rs;
        }
        
        public function eliminarRegistro($params) {
            $layoutController = new LayoutController();
            $rs = $layoutController->eliminarRegistro($params);
            return $rs;
        }
        
        public function modificarRegistro($params){
            $layoutController = new LayoutController();
            $rs = $layoutController->modificarRegistro($params);
            return $rs;
        }
        
    }

    @$accion = $_POST['accion'];
    @$params['id'] = @$_POST['id'];
    @$params['nombre'] = @$_POST['nombre'];
    @$params['paterno'] = @$_POST['paterno'];
    @$params['materno'] = @$_POST['materno'];
    @$params['cveGenero'] = @$_POST['cveGenero'];
    @$params['edad'] = @$_POST['edad'];
    @$params['activo'] = @$_POST['activo'];
    @$params['fechaInicio'] = @$_POST['fechaInicio'];
    @$params['fechaFin'] = @$_POST['fechaFin'];
    @$params['sueldoMensual'] = @$_POST['sueldoMensual'];
    
    $layoutFacade = new LayoutFacade();
    if ($accion == 'enviarLayout') {
        $rs = $layoutFacade->enviarLayout($_FILES["layout"]);
        echo json_encode($rs);
    } else if ( $accion == 'cargarGeneros' ) {
        $rs = $layoutFacade->cargarGeneros($params);
        echo json_encode($rs);
    } else if ( $accion == 'consultarRegistros' ) {
        $rs = $layoutFacade->consultarRegistros($params);
        echo json_encode($rs);
    } else if ( $accion == 'eliminarRegistro' ) {
        $rs = $layoutFacade->eliminarRegistro($params);
        echo json_encode($rs);
    } else if ( $accion == 'modificarRegistro' ) {
        $rs = $layoutFacade->modificarRegistro($params);
        echo json_encode($rs);
    }
} else {
    header("HTTP/1.0 440 la sesion caduco");
    header("Status: 440 Login Timeout");
}