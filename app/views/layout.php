<!DOCTYPE html>
<html>
<head>
  <title>EXAMEN</title>
  <script type="text/javascript" src="../web/js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="../web/js/jquery/jquery-ui/jquery-ui-1.9.2.min.js"></script>
  <link rel="stylesheet" href="../web/css/estilos.css" />
  <link rel="stylesheet" href="../web/css/bootstrap.min.css" />
</head>

<body>

    <div id="cabecera">
        <!--<h1>S</h1>-->
    </div>

    <nav id="menu">
        <ul class="nav navbar-nav">
            <li><a href="index.php?ctl=inicio">inicio</a></li>
            <li><a href="index.php?ctl=cargarLayout">Cargar Layout</a></li>
            <li><a href="index.php?ctl=logout">Cerrar Sesi&oacute;n</a></li>
        </ul>
    </nav>
    <br>
    <div style="clear: both;"></div>
         <div id="contenido">
             <?php echo $contenido ?>
         </div>

         <div id="pie">
             <hr/>
             <div align="center">- (c) -</div>
         </div>

</body>
</html>