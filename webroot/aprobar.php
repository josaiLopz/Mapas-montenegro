<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'para_modificar';
$username = 'root';
$password = 'root';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Número de registros por página
    $records_per_page = 10;

    // Calcular la página actual
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start_from = ($page - 1) * $records_per_page;

    // Consulta con LIMIT para paginación
    $sql = "
        SELECT 
            bh.*,
            b.id AS base_id,
            b.cct AS base_cct,
            b.nombre AS base_nombre,
            b.turno AS base_turno,
            b.tipo AS base_tipo,
            b.sector AS base_sector,
            b.estado AS base_estado,
            b.estado_n AS base_estado_n,
            b.municipio AS base_municipio,
            b.municipio_n AS base_municipio_n,
            b.alumnos AS base_alumnos,
            b.lat AS base_lat,
            b.lng AS base_lng,
            b.id_distribuidor AS base_id_distribuidor,
            b.verificada AS base_verificada,
            b.estatus AS base_estatus
        FROM 
            bases_historial bh
        LEFT JOIN 
            bases b ON bh.id_original = b.id
        ORDER BY 
            bh.id_original, bh.fecha_modificacion DESC
        LIMIT $start_from, $records_per_page
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Organizar los datos por ID original
    $resultados = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_original = $row['id_original'];
        if (!isset($resultados[$id_original])) {
            $resultados[$id_original] = [
                'actual' => null,
                'historicos' => []
            ];
        }

        // El primer registro para cada ID será el más reciente
        if ($row['base_id'] && !$resultados[$id_original]['actual']) {
            $resultados[$id_original]['actual'] = [
                'cct' => $row['base_cct'],
                'nombre' => $row['base_nombre'],
                'turno' => $row['base_turno'],
                'tipo' => $row['base_tipo'],
                'sector' => $row['base_sector'],
                'estado' => $row['base_estado'],
                'estado_n' => $row['base_estado_n'],
                'municipio' => $row['base_municipio'],
                'municipio_n' => $row['base_municipio_n'],
                'alumnos' => $row['base_alumnos'],
                'lat' => $row['base_lat'],
                'lng' => $row['base_lng'],
                'id_distribuidor' => $row['base_id_distribuidor'],
                'verificada' => $row['base_verificada'],
                'estatus' => $row['base_estatus']
            ];
        }

        $resultados[$id_original]['historicos'][] = [
            'id' => $row['id'],
            'cct' => $row['cct'],
            'nombre' => $row['nombre'],
            'turno' => $row['turno'],
            'tipo' => $row['tipo'],
            'sector' => $row['sector'],
            'estado' => $row['estado'],
            'estado_n' => $row['estado_n'],
            'municipio' => $row['municipio'],
            'municipio_n' => $row['municipio_n'],
            'alumnos' => $row['alumnos'],
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'id_distribuidor' => $row['id_distribuidor'],
            'verificada' => $row['verificada'],
            'estatus' => $row['estatus'],
            'fecha_modificacion' => $row['fecha_modificacion'],
            'usuario_modificacion' => $row['usuario_modificacion']
        ];
    }

    // Calcular total de registros
    $sql_count = "SELECT COUNT(*) FROM bases_historial";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->execute();
    $total_records = $stmt_count->fetchColumn();
    $total_pages = ceil($total_records / $records_per_page);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Comparativa Historial - Bases</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .actual {
            background-color: #e6ffe6;
        }

        .historico {
            background-color: #fff3e6;
        }

        .cambio {
            background-color: #ffebee;
            font-weight: bold;
        }

        h2 {
            color: #333;
        }

        .grupo {
            margin-bottom: 40px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            border: 1px solid #ddd;
            margin: 0 5px;
            text-decoration: none;
            color: #007BFF;
        }

        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h1>Comparativa entre Bases Actuales y Historial</h1>

    <table>
        <thead>
            <tr>
                <th>Campo</th> <!-- Título para cada campo -->
                <?php
                // Mostrar encabezados de todas las columnas
                $fields = [
                    'cct', 'nombre', 'turno', 'tipo', 'sector', 'estado', 'estado_n',
                    'municipio', 'municipio_n', 'alumnos', 'lat', 'lng', 'id_distribuidor',
                    'verificada', 'estatus'
                ];
                foreach ($fields as $campo): ?>
                    <th><?php echo ucfirst(str_replace('_', ' ', $campo)); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($resultados as $id_original => $grupo): ?>
                <!-- Fila Original -->
                <tr class="actual">
                    <td><strong>Original</strong></td>
                    <?php foreach ($fields as $campo): ?>
                        <td><?php echo isset($grupo['actual'][$campo]) ? htmlspecialchars($grupo['actual'][$campo]) : ''; ?></td>
                    <?php endforeach; ?>
                </tr>

                <!-- Filas de Cambio -->
                <?php foreach ($grupo['historicos'] as $registro): ?>
                    <tr class="historico">
                        <td><strong>Cambio</strong></td>
                        <?php foreach ($fields as $campo): ?>
                            <?php
                            $valor_hist = isset($registro[$campo]) ? $registro[$campo] : '';
                            $clase = ($valor_hist != $grupo['actual'][$campo]) ? 'cambio' : '';
                            ?>
                            <td class="<?php echo $clase; ?>"><?php echo htmlspecialchars($valor_hist); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td colspan="<?php echo count($fields) + 1; ?>">
                            <strong>Fecha:</strong> <?php echo $registro['fecha_modificacion']; ?> |
                            <strong>Usuario:</strong> <?php echo $registro['usuario_modificacion']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>

</html>
