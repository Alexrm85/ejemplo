<?php

include_once __DIR__ . '/../../lib/conexion.php';
include_once __DIR__ . '/../../models/dao/dao.php';

class LayoutController {

    protected $pdo = null;
    
    public function cargarGeneros($params, $p = null){
        $dao = new dao();
        $error = false;
        $result = array();
        $generos = $dao->consultarGeneros($params);
        $data = array();
        if ( count($generos) == 0 ) {
            $error = true;
        } else {
            foreach ( $generos as $genero ) {
                $data[] = array(
                    "cveGenero" => $genero['cveGenero'],
                    "desGenero" => $genero['desGenero'],
                );
            }
        }
        if ( !$error ) {
            $result = array(
                'estatus' => 'ok',
                'totalCount' => count($data),
                'data' => $data
            );
        } else {
            $result = array(
                'estatus' => 'error',
                'totalCount' => '0'
            );
        }
        return $result;
    }

    public function enviarLayout($file, $p = null) {
        if ($p == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
            $this->pdo->beginTransaction();
        } else {
            $this->pdo = $p;
        }
        $error = false;
        $arrayLayout = array();
        $resultado = array();
        $mensaje = '';
        $fechaActual = '';
        $dao = new dao();
        $sqlFecha = "select now() as fechaActual";
        $query = $this->pdo->query($sqlFecha);
        $result = $query->fetch();
        $fechaActual = $result['fechaActual'];
        $fp = fopen($file["tmp_name"], "r");
        while (!feof($fp)) {
            $arrayLayout[] = trim(fgets($fp));
        }
        fclose($fp);

        if (count($arrayLayout) < 21) {
            $error = true;
            $mensaje = " .El layout debe ontener por lo menos encabezado, y 20 registros como minimo";
        }
        
        if ( !$error ) {
            $encabezado = explode(",", $arrayLayout[0]);
            if ( $encabezado[0] != 'nombre' ) {
                $error = true;
                $mensaje .= 'El layout debe contener nombre';
            }
            if ( $encabezado[1] != 'paterno' ) {
                $error = true;
                $mensaje .= 'El layout debe contener paterno';
            }
            if ( $encabezado[2] != 'materno' ) {
                $error = true;
                $mensaje .= 'El layout debe contener materno';
            }
            if ( $encabezado[3] != 'cveGenero' ) {
                $error = true;
                $mensaje .= 'El layout debe contener cveGenero';
            }
            if ( $encabezado[4] != 'edad' ) {
                $error = true;
                $mensaje .= 'El layout debe contener edad';
            }
            if ( $encabezado[5] != 'sueldoMensual' ) {
                $error = true;
                $mensaje .= 'El layout debe contener sueldoMensual';
            }
        }
        //print_r($arrayLayout);
        if ( !$error ) {
            for ( $n = 1 ; $n < count($arrayLayout); $n++ ) {
                
                $datos = explode(',', $arrayLayout[$n]);
                //print_r($datos);
                for ( $x = 0; $x < count($datos); $x++ ) {
                    if ( is_numeric($datos[0]) || $datos[0] == '' ) {
                        $error = true;
                        $mensaje .= "El nombre no puede estar vacio y no debe ser numerico en la linea : " . $n;
                    }
                    if ( is_numeric($datos[1]) || $datos[1] == '' ) {
                        $error = true;
                        $mensaje .= "El apellido paterno no puede estar vacio y no debe ser numerico en la linea : " . $n;
                    }
                    if ( is_numeric($datos[2]) || $datos[2] == '' ) {
                        $error = true;
                        $mensaje .= "El apellido materno no puede estar vacio y no debe ser numerico en la linea : " . $n;
                    }
                    if ( !is_numeric($datos[3]) || $datos[3] == '' ) {
                        $error = true;
                        $mensaje .= "El genero debe ser numerico y no puede estar vacio en la linea : " . $n;
                    }
                    if ( !is_numeric($datos[4]) || $datos[4] == '' ) {
                        $error = true;
                        $mensaje .= "El genero debe ser numerico y no puede estar vacio en la linea : " . $n;
                    }
                    if ( !is_numeric($datos[5]) || $datos[5] == '' ) {
                        $error = true;
                        $mensaje .= "El sueldo debe ser numerico y no puede estar vacio en la linea : " . $n;
                    }
                    if ( $error ) {
                        break;
                    }
                }
            }
        }
        if ( !$error ) {
            for ( $n = 1 ; $n < count($arrayLayout); $n++ ) {
                $datos = explode(',', $arrayLayout[$n]);
                //print_r($datos);
                $param['nombre'] = addslashes($datos[0]);
                $param['paterno'] = addslashes($datos[1]);
                $param['materno'] = addslashes($datos[2]);
                $param['cveGenero'] = $datos[3];
                $param['edad'] = $datos[4];
                $param['sueldoMensual'] = $datos[5];
                $param['idUsuario'] = $_SESSION['idUsuario'];
                $rs = $dao->insertarLayout($param, $this->pdo);
                if ( $rs ) {
                    $error = false;
                } else {
                    $error = true;
                    $mensaje .= 'Ocurrio un error al guardar los datos del layout en la linea: ' . $n;
                }
                if ( $error ) {
                    break;
                }
            }
        }
        if ( !$error ) {
            $this->pdo->commit();
            $resultado = array(
                "estatus" => "ok",
                "totalCount" => "1",
                "mensaje" => "datos guardados correctamente"
            );
        } else {
            $this->pdo->rollBack();
            $resultado = array(
                "estatus" => "ok",
                "totalCount" => "1",
                "mensaje" => $mensaje
            );
        }
        if ($p == null) {
            $this->pdo = null;
        }
        return $resultado;
    }
    
    public function consultarRegistros($params){
        $dao = new dao();
        $error = false;
        $result = array();
        $mensaje = '';
        $registros = $dao->consultarRegistros($params);
        if ( count($registros) == 0 ) {
            $error = true;
            $mensaje = 'Sin resultados';
        } else {
            for ( $r = 0; $r < count($registros); $r++ ) {
                $registros[$r]['nombre'] = utf8_encode($registros[$r]['nombre']);
                $registros[$r]['paterno'] = utf8_encode($registros[$r]['paterno']);
                $registros[$r]['materno'] = utf8_encode($registros[$r]['materno']);
            }
        }
        if ( !$error ) {
            $result = array(
                'estatus' => 'ok',
                'totalCount' => count($registros),
                'data' => $registros
            );
        } else {
            $result = array(
                'estatus' => 'error',
                'totalCount' => '0',
                'mensaje' => $mensaje
            );
        }
        return $result;
    }
    
    public function eliminarRegistro($params) {
        $dao = new dao();
        $result = array();
        $registros = $dao->eliminarRegistro($params);
        if ( $registros ) {
            $result = array(
                'estatus' => 'ok',
                'totalCount' => '1',
                'mensaje' => 'Registro eliminado correctamente'
            );
        } else {
            $result = array(
                'estatus' => 'error',
                'totalCount' => '0',
                'mensaje' => 'No se pudo eliminar el registro'
            );
        }
        return $result;
    }
    
    public function modificarRegistro($params){
        $dao = new dao();
        $result = array();
        $registros = $dao->modificarRegistro($params);
        if ( $registros ) {
            $result = array(
                'estatus' => 'ok',
                'totalCount' => '1',
                'mensaje' => 'Registro modificado correctamente'
            );
        } else {
            $result = array(
                'estatus' => 'error',
                'totalCount' => '0',
                'mensaje' => 'No se pudo modificar el registro'
            );
        }
        return $result;
    }
    
}
