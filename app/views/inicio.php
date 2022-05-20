<?php ob_start(); ?>
<script type="text/javascript" src="../web/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../web/js/jquery-ui-1.8.16.custom.min.js"></script>
<div>
<h1>Inicio</h1>
<h3><?php echo $params['mensaje']?></h3>
<small>Fecha <?php echo $params['fecha']?></small>
</div>
<?php $contenido = ob_get_clean(); ?>
<?php include 'layout.php'; ?>