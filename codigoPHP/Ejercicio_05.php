<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicio 05 - Israel García Cabañeros</title>
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once '../core/validacionFormularios.php';
        require_once '../config/config.php';

        $entradaOK = true;
        $transactionOK = true;

        $a_errores = [
            'codDepartamento1' => null,
            'codDepartamento2' => null,
            'codDepartamento3' => null,
            'descDepartamento1' => null,
            'descDepartamento2' => null,
            'descDepartamento3' => null
        ];

        $a_respuesta = [
            'codDepartamento1' => null,
            'codDepartamento2' => null,
            'codDepartamento3' => null,
            'descDepartamento1' => null,
            'descDepartamento2' => null,
            'descDepartamento3' => null
        ];

        switch (true) {
            case (isset($_POST['enviar'])):
                $a_errores['codDepartamento1'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento1'], 3, 3, 1);
                $a_errores['descDepartamento1'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento1'], 100, 5, 1);
                $a_errores['codDepartamento2'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento2'], 3, 3, 1);
                $a_errores['descDepartamento2'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento2'], 100, 5, 1);
                $a_errores['codDepartamento3'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento3'], 3, 3, 1);
                $a_errores['descDepartamento3'] = validacionFormularios::comprobarAlfaNumerico($_POST['descDepartamento3'], 100, 5, 1);

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
                $a_respuesta['codDepartamento1'] = strtoupper($_POST['codDepartamento1']);
                $a_respuesta['descDepartamento1'] = ($_POST['descDepartamento1']);

                $a_respuesta['codDepartamento2'] = strtoupper($_POST['codDepartamento2']);
                $a_respuesta['descDepartamento2'] = ($_POST['descDepartamento2']);

                $a_respuesta['codDepartamento3'] = strtoupper($_POST['codDepartamento3']);
                $a_respuesta['descDepartamento3'] = ($_POST['descDepartamento3']);

                try {
                    $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    ?>
                    <p>Conexión correcta.</p>
                    <?php
                    $miDB->beginTransaction();
                    $sql1 = 'INSERT INTO Departamento (CodDepartamento, DescDepartamento) VALUES (:codDepartamento1, :descDepartamento1)';
                    $sql2 = 'INSERT INTO Departamento (CodDepartamento, DescDepartamento) VALUES (:codDepartamento2, :descDepartamento2)';
                    $sql3 = 'INSERT INTO Departamento (CodDepartamento, DescDepartamento) VALUES (:codDepartamento3, :descDepartamento3)';

                    $statement1 = $miDB->prepare($sql1);
                    $statement1->bindParam(':codDepartamento1', $a_respuesta['codDepartamento1']);
                    $statement1->bindParam(':descDepartamento1', $a_respuesta['descDepartamento1']);

                    $statement2 = $miDB->prepare($sql2);
                    $statement2->bindParam(':codDepartamento2', $a_respuesta['codDepartamento2']);
                    $statement2->bindParam(':descDepartamento2', $a_respuesta['descDepartamento2']);

                    $statement3 = $miDB->prepare($sql3);
                    $statement3->bindParam(':codDepartamento3', $a_respuesta['codDepartamento3']);
                    $statement3->bindParam(':descDepartamento3', $a_respuesta['descDepartamento3']);

                    if ((!$statement1->execute()) || (!$statement2->execute()) || (!$statement3->execute())) {
                        $transactionOK = false;
                    }

                    if ($transactionOK) {
                        $miDB->commit();
                        ?>
                        <p>Transacción realizada con éxito.</p>
                        <?php
                    } else {
                        $miDB->rollBack();
                        ?>
                        <p>La transacción no ha podido ser realizada.</p>
                        <?php
                    }
                } catch (PDOException $pdoe) {
                    $miDB->rollBack();
                    ?>
                    <p>La transacción no ha podido ser realizada.</p>
                    <?php
                    echo $pdoe->getMessage();
                } finally {
                    unset($miDB);
                }

                break;

            default:
                ?>
                <h1>Alta de 3 departamentos o ninguno</h1>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <label for="codDepartamento1">Código departamento:</label>
                    <input size="3" type="text" name="codDepartamento1" value="<?php
                    if (isset($_REQUEST['codDepartamento1']) && is_null($a_errores['codDepartamento1'])) {
                        echo $_REQUEST['codDepartamento1'];
                    }
                    ?>" placeholder="AAA"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['codDepartamento1']; ?></font>
                    <br>
                    <label for="descDepartamento1">Descripción departamento:</label>
                    <input size="50" type="text" name="descDepartamento1" value="<?php
                    if (isset($_REQUEST['descDepartamento1']) && is_null($a_errores['descDepartamento1'])) {
                        echo $_REQUEST['descDepartamento1'];
                    }
                    ?>" placeholder="Mi departamento AAA"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['descDepartamento1']; ?></font>
                    <br>
                    <label for="codDepartamento2">Código departamento:</label>
                    <input size="3" type="text" name="codDepartamento2" value="<?php
                    if (isset($_REQUEST['codDepartamento2']) && is_null($a_errores['codDepartamento2'])) {
                        echo $_REQUEST['codDepartamento2'];
                    }
                    ?>" placeholder="BBB"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['codDepartamento2']; ?></font>
                    <br>
                    <label for="descDepartamento2">Descripción departamento:</label>
                    <input size="50" type="text" name="descDepartamento2" value="<?php
                    if (isset($_REQUEST['descDepartamento2']) && is_null($a_errores['descDepartamento2'])) {
                        echo $_REQUEST['descDepartamento2'];
                    }
                    ?>" placeholder="Mi departamento BBB"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['descDepartamento2']; ?></font>
                    <br>
                    <label for="codDepartamento3">Código departamento:</label>
                    <input size="3" type="text" name="codDepartamento3" value="<?php
                    if (isset($_REQUEST['codDepartamento3']) && is_null($a_errores['codDepartamento3'])) {
                        echo $_REQUEST['codDepartamento3'];
                    }
                    ?>" placeholder="CCC"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['codDepartamento3']; ?></font>
                    <br>
                    <label for="descDepartamento3">Descripción departamento:</label>
                    <input size="50" type="text" name="descDepartamento3" value="<?php
                    if (isset($_REQUEST['descDepartamento3']) && is_null($a_errores['descDepartamento3'])) {
                        echo $_REQUEST['descDepartamento3'];
                    }
                    ?>" placeholder="Mi departamento CCC"/><font color="red">&nbsp;*</font>
                    <font color="red"><?php echo $a_errores['descDepartamento3']; ?></font>
                    <br>
                    <br>
                    <input type="submit" name="enviar" value="Alta">
                </form>
                <?php
                break;
        }
        ?>
    </body>
</html>
