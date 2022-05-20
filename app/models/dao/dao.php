<?php

include_once __DIR__ . '/../../lib/conexion.php';
error_reporting(E_ALL);

class dao {

    protected $pdo = null;

    public function __construct() {
        
    }

    public function validarUsuario($user, $pass, $proveedor = null) {
        if ($proveedor == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $proveedor;
        }
        //var_dump($this->pdo);
        $sql = "SELECT *
                FROM tblusuarios
                WHERE login = '" . addslashes($user) . "'
                AND password = '" . addslashes($pass) . "'";

        $query = $this->pdo->query($sql);
        $result = $query->fetch();

        $registro = $query->rowCount();
        if ($registro == 0) {
            $mensaje['error'] = 'Usuario y/o contraseña incorrecta';
        } else {
            if ($result['activo'] == 'S') {
                $mensaje = $result;
            } else {
                $mensaje['error'] = 'El usuario no está activo, favor de verificar!';
            }
        }
        return $mensaje;
    }

    public function listarEmpleados($params = '', $proveedor = null) {
        if ($proveedor == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $proveedor;
        }
        $empleado = array();
        $sql = "SELECT *
                FROM tblusuarios
                WHERE activo='S'
                ";
        if ($params != '') {
            if (isset($params['idUsuario']) && $params['idUsario'] != '') {
                $sql .= " AND idUsuario = " . $params['idUsuario'];
            }
            if (isset($params['nombre']) && $params['nombre'] != '') {
                $sql .= " AND nombre LIKE '%" . $params['nombre'] . "%'";
            }
            if (isset($params['paterno']) && $params['paterno'] != '') {
                $sql .= " AND paterno LIKE '%" . $params['paterno'] . "%'";
            }
        }
        $sql .= " ORDER BY nombre";
        $query = $this->pdo->query($sql);
        foreach ($query as $e) {
            $empleado[] = $e;
        }
        return $empleado;
    }

    public function consultarGeneros($params = '', $p = null) {
        if ($p == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $p;
        }
        $sql = "SELECT cveGenero, desGenero, activo FROM tblgeneros WHERE activo = 'S' ";
        if ($params != '') {
            if (isset($params['cveGenero']) && $params['cveGenero'] != '') {
                $sql .= " AND cveGenero = " . $params['cveGenro'];
            }
            if (isset($params['desGenero']) && $params['desGenero'] != '') {
                $sql .= " AND desGenero LIKE '%" . $params['desGenero'] . "%'";
            }
        }
        $query = $this->pdo->query($sql);
        foreach ($query as $q) {
            $generos[] = $q;
        }
        return $generos;
    }

    public function insertarEmpleado($params = '', $proveedor = null) {
        if ($proveedor == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $proveedor;
        }
        $resultado = '';
        $bandera = false;
        $sql = "INSERT INTO tblusuarios (
                            login,
                            password,
                            nombre,
                            paterno,
                            materno,
                            fechaRegistro,
                            tipo,
                            fechaActualizacion
                        ) VALUES(
                            '" . addslashes($params['login']) . "',
                            '" . addslashes($params['password']) . "',
                            '" . addslashes($params['nombre']) . "',
                            '" . addslashes($params['paterno']) . "',
                            '" . addslashes($params['materno']) . "',
                            NOW(),
                            '" . addslashes($params['tipo']) . "',
                            NOW() )";
        try {
            $query = $this->pdo->query($sql);
            $id = $this->pdo->lastInsertId();
            $empleadoAux['idUsuario'] = $id;
            $resultado = $this->listarEmpleados($empleadoAux, $this->pdo);
        } catch (Exception $e) {
            $bandera = false;
        }
        return $resultado;
    }

    public function validarEmpleado($params) {
        $pdo = new conexion();
        $this->pdo = $pdo->connect();
        $bandera = false;
        $sql = "SELECT *
                FROM tblusuarios
                WHERE nombre = '" . addslashes($params['nombre']) . "'
                AND paterno = '" . addslashes($params['paterno']) . "'
                AND materno = '" . addslashes($params['materno']) . "'
                AND tipo = '" . $params['tipo'] . "'";
        $query = $this->pdo->query($sql);
        $registros = $query->rowCount();
        if ($registros == 0) {
            $bandera = true;
        } else {
            $bandera = false;
        }
        return $bandera;
    }

    public function insertarLayout($params, $p = null) {
        if ($p == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $p;
        }

        $bandera = false;
        $sql = "INSERT INTO tbllayout (
                            idUsuario,
                            nombre,
                            paterno,
                            materno,
                            cveGenero,
                            edad,
                            sueldoMensual,
                            activo,
                            fechaRegistro,
                            fechaActualizacion) VALUES (
                            '" . $params['idUsuario'] . "',
                            '" . addslashes($params['nombre']) . "',
                            '" . addslashes($params['paterno']) . "',
                            '" . addslashes($params['materno']) . "',
                            '" . $params['cveGenero'] . "',
                            '" . $params['edad'] . "',
                            '" . $params['sueldoMensual'] . "',
                            'S',
                            NOW(),
                            NOW()
                            )";
        try {
            $query = $this->pdo->query($sql);
            $bandera = true;
        } catch (Exception $e) {
            $bandera = false;
        }
        return $bandera;
    }

    public function modificarRegistro($params, $p = null) {
        if ($p == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $p;
        }
        $bandera = false;
        $sql = "UPDATE tbllayout 
                SET nombre = '" . addslashes($params['nombre']) . "'
                , paterno = '" . addslashes($params['paterno']) . "' 
                , materno = '" . addslashes($params['materno']) . "' 
                , cveGenero = '" . $params['cveGenero'] . "' 
                , edad = '" . $params['edad'] . "' 
                , sueldoMensual = '" . $params['sueldoMensual'] . "'
                , fechaActualizacion = NOW()
                WHERE id = " . $params['id'];
        try {
            $query = $this->pdo->query($sql);
            $bandera = true;
        } catch (Exception $e) {
            $bandera = false;
        }
        return $bandera;
    }
    
    public function eliminarRegistro($params, $p = null){
        if ($p == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $p;
        }
        $bandera = false;
        $sql = "UPDATE tbllayout 
                SET activo = 'N'
                , fechaActualizacion = NOW()
                WHERE id = " . $params['id'];
        try {
            $query = $this->pdo->query($sql);
            $bandera = true;
        } catch (Exception $e) {
            $bandera = false;
        }
        return $bandera;
    }
    
    public function consultarRegistros($params, $p = null) {
        if ($p == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $p;
        }
        $datos = array();
        $sql = "SELECT l.id, l.idUsuario, u.nombre AS nombreUsario, u.paterno AS paternoUsuario, u.materno AS maternoUsuario,
                       l.nombre, l.paterno, l.materno, l.cveGenero, g.desGenero, l.edad, l.sueldoMensual, l.activo,
                       l.fechaRegistro, l.fechaActualizacion
                    FROM tbllayout l
                        INNER JOIN tblusuarios u ON u.idUsuario = l.idUsuario
                        INNER JOIN tblgeneros g ON g.cveGenero = l.cveGenero
                 WHERE l.activo = 'S'";
        if (isset($params['id']) && $params['id'] != '') {
            $sql .= " AND l.id = " . $params['id'];
        }
        if (isset($params['nombre']) && $params['nombre'] != '') {
            $sql .= " AND l.nombre LIKE '%" . $params['nombre'] . "%'";
        }
        if (isset($params['paterno']) && $params['paterno'] != '') {
            $sql .= " AND l.paterno LIKE '%" . $params['paterno'] . "%'";
        }
        if (isset($params['materno']) && $params['materno'] != '') {
            $sql .= " AND l.materno LIKE '%" . $params['materno'] . "%'";
        }
        if (isset($params['cveGenero']) && $params['cveGenero'] != '') {
            $sql .= " AND g.cveGenero = " . $params['cveGenero'];
        }
        if (isset($params['edad']) && $params['edad'] != '') {
            $sql .= " AND l.edad = " . $params['edad'];
        }
        if (isset($params['sueldoMensual']) && $params['sueldoMensual'] != '') {
            $sql .= " AND l.sueldoMensual = " . $params['sueldoMensual'];
        }
        if (isset($params['fechaInicio']) && $params['fechaInicio'] != '') {
            $fechaInicio = explode("/", $params['fechaInicio']);
            $fechaIni = $fechaInicio[2] . '-' . $fechaInicio[1] . '-' . $fechaInicio[0];
            $sql .= " AND l.fechaRegistro >= '" . $fechaIni . " 00:00:00' ";
        }
        if (isset($params['fechaFin']) && $params['fechaFin'] != '') {
            $fechaFin = explode("/", $params['fechaFin']);
            $fecFin = $fechaFin[2] . '-' . $fechaFin[1] . '-' . $fechaFin[0];
            $sql .= " AND l.fechaRegistro <= '" . $fecFin . " 23:59:59' ";
        }
        $query = $this->pdo->query($sql);
        foreach ($query as $q) {
            $datos[] = $q;
        }
        return $datos;
    }

    public function insertBitacora($param, $proveedor = null) {
        if ($proveedor == null) {
            $pdo = new conexion();
            $this->pdo = $pdo->connect();
        } else {
            $this->pdo = $proveedor;
        }
        $bandera = false;
        $sql = "INSERT INTO tblbitacora (
                        cveAccion,
                        movimiento,
                        cveUsuario,
                        activo,
                        fechaRegistro,
                        fechaActualizacion
                    ) VALUES (
                        '" . $param['cveAccion'] . "',
                        '" . $param['movimiento'] . "',
                        '" . $_SESSION['idUsuario'] . "',
                        'S',
                        NOW(),
                        NOW()
                    )";
        try {
            $query = $this->pdo->query($sql);
            $bandera = true;
        } catch (Exception $e) {
            $bandera = false;
        }
        return $bandera;
    }

}
