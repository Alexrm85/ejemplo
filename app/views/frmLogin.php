<!DOCTYPE html>

<html>

<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/estilos.css" />
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
</head>

<body>

<div class="container">
    <div class="panel-body">
        <div class="row" style="text-align: center;">
            <div class="col-md-5" style="margin-left: auto !important; margin-right: auto !important; float: inherit;">
                <div class="panel-heading">
                    <h5 class="panel-title">Iniciar sesi&oacute;n</h5>
                </div>
                <form action="index.php?ctl=sesion" method="post">
                    <div class="inicio-sesion" style="background-color: #F7F7F7; padding: 15px; border-radius: 10px; box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.5);">
                        <div class="error"><?php echo (isset($datos['error'])) ? $datos['error'] : ''; ?></div><br>
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" /><br><br>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" /><br><br>
                        <input type="submit" id="entrar" value="Entrar" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>