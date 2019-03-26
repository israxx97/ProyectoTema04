<!DOCTYPE html>
<html>
    <head>
        <title>
            Ejercicio 03 - Israel García Cabañeros
        </title>
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once '../core/validacionFormularios.php';
        require_once '../config/config.php';

        $entradaOK = true;

        $a_errores = [
            'codDepartamento' => null,
            'descDepartamento' => null
        ];

        $a_respuesta = [
            'codDepartamento' => null,
            'descDepartamento' => null
        ];

        switch (true) {
            case (isset($_POST['enviar'])):
                $a_errores['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento'], 3, 3, 1);
                $a_errores['descDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento'], 100, 5, 1);

                foreach ($a_errores as $campo => $error) {
                    if ($error != null) {
                        $entradaOK = false;
                        $_POST[$campo] = null;
                    }
                }

                break;

            default:
                $entradaOK = false;

                break;
        }

        switch (true) {
            case $entradaOK:
                $a_respuesta['codDepartamento'] = strtoupper($_POST['codDepartamento']);
                $a_respuesta['descDepartamento'] = $_POST['descDepartamento'];

                try {
                    $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    ?>
                    <p>Conexión correcta.</p>
                    <?php
                    $statement = $miDB->prepare('INSERT INTO Departamento (CodDepartamento, DescDepartamento) VALUES (:codDepartamento, :descDepartamento)');
                    $statement->bindParam(':codDepartamento', $a_respuesta['codDepartamento']);
                    $statement->bindParam(':descDepartamento', $a_respuesta['descDepartamento']);

                    if ($statement->execute()) {
                        ?><p>El departamento con código <?php echo $a_respuesta['codDepartamento']; ?> y descripción <?php echo $a_respuesta['descDepartamento']; ?> se ha añadido correctamente.</p><?php
                    } else {
                        ?><p>El departamento con código <?php echo $a_respuesta['codDepartamento']; ?> y descripción <?php echo $a_respuesta['descDepartamento']; ?> no se ha podido añadir.</p><?php
                    }
                } catch (PDOException $pdoe) {
                    ?>
                    <p><?php echo $pdoe->getMessage(); ?></p>
                    <?php
                } finally {
                    unset($miDB);
                }

                break;

            default:
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <label for="codDepartamento">Código departamento:</label>
                    <input type="text" name="codDepartamento" value="<?php
                    if (isset($_REQUEST['codDepartamento']) && is_null($a_errores['codDepartamento'])) {
                        echo $_REQUEST['codDepartamento'];
                    }
                    ?>" placeholder="AAA"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['codDepartamento']; ?></font>
                    <br>
                    <span for="descDepartamento">Descripción departamento:&nbsp;</span>
                    <textarea rows="5" cols="20" name="descDepartamento" placeholder="Mi departamento AAA"><?php
                        if (isset($_REQUEST['descDepartamento']) && is_null($a_errores['descDepartamento'])) {
                            echo $_REQUEST['descDepartamento'];
                        }
                        ?></textarea>
                    <font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['descDepartamento']; ?></font>
                    <br><br>
                    <input type="submit" name="enviar" value="Enviar">
                </form>
                <?php
                break;
        }
        ?>
    </body>
</html>