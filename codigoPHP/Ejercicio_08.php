<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicio 08 - Israel García Cabañeros</title>
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once '../core/validacionFormularios.php';
        require_once '../config/config.php';

        try {
            $miDB = new PDO(HOST_DB, USER_DB, PASS_DB);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $archivo = new DOMDocument();
            $archivo->formatOutput = true;
            $departamentos = $archivo->createElement('departamentos');
            $departamentos = $archivo->appendChild($departamentos);
            $statement = $miDB->query('SELECT * FROM Departamento');

            while ($registro = $statement->fetchObject()) {
                $departamento = $archivo->createElement('departamento');
                $departamento = $departamentos->appendChild($departamento);
                $codDepartamento = $archivo->createElement('CodDepartamento', $registro->CodDepartamento);
                $codDepartamento = $departamento->appendChild($codDepartamento);
                $descDepartamento = $archivo->createElement('DescDepartamento', $registro->DescDepartamento);
                $descDepartamento = $departamento->appendChild($descDepartamento);
            }

            $archivo->saveXML();

            if ($archivo->save("../tmp/backup.xml") != 0) {
                header('Content-Type: text/xml');
                header('Content-Disposition: attachment; filename="backup.xml"');
                readfile("../tmp/backup.xml");
            } else {
                ?>
                <p>No pudo guardarse la base de datos.</p>
                <?php
            }
        } catch (PDOException $pdoe) {
            echo $pdoe->getMessage();
        } finally {
            unset($miDB);
        }
        ?>
    </body>
</html>