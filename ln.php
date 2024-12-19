<?php

function quitar_tildes($cadena) {
    return transliterator_transliterate('Any-Latin; Latin-ASCII', $cadena);
}

function procesa_consulta($consulta, $conexion, &$sql) {
    $consulta = trim($consulta);
    $consulta = strtolower($consulta); 
    $consulta = quitar_tildes($consulta);
    $patrones = [
        '/deseo un (\w+) con mas de (\d+) dormitorios en el (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND ndormitorios > %2 AND zona = '%3'",
        '/busco un (\w+) con mas de (\d+) dormitorios en (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND ndormitorios > %2 AND zona = '%3'",
        '/busco un (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1'",
        '/busco una (\w+) en (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND zona = '%2'",
        '/busco una (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1'",
        '/deseo un (\w+) en el (\w+) o en (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND (zona = '%2' OR zona = '%3')",
        '/deseo una (\w+) en el (\w+) o en (\w+)/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND (zona = '%2' OR zona = '%3')",
        '/deseo un (\w+) de mas de (\d+) metros cuadrados/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND metros_cuadrados > %2",
        '/deseo una (\w+) de mas de (\d+) metros cuadrados/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND metros_cuadrados > %2",
        '/deseo un (\w+) barato/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND precio < 100000",
        '/deseo un (\w+) en (\w+) con garaje/' => "SELECT * FROM viviendas WHERE tipo = '%1' AND zona = '%2' AND foto IS NOT NULL"
    ];

    // Iterar sobre cada patrón y comparar con la consulta
    foreach ($patrones as $patron => $consulta_sql) {
        if (preg_match($patron, $consulta, $matches)) {
            $sql = $consulta_sql;
            for ($i = 1; $i < count($matches); $i++) {
                $sql = str_replace("%$i", $matches[$i], $sql);
            }

            if (isset($matches[2]) || isset($matches[3])) { 
                $zona = isset($matches[2]) ? $matches[2] : $matches[3];
                $query_zona = "SELECT COUNT(*) FROM viviendas WHERE zona = '%s'";
                $result_zona = $conexion->query(sprintf($query_zona, $zona));
                $zona_count = $result_zona->fetch_row()[0];
                if ($zona_count === 0) {
                    $sql = '';
                    return false;
                }
            }
            return true; 
        }
    }
    $sql = '';
    return false;
}
?>