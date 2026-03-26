<?php
include 'Calculadora/db.php'; // Incluir el archivo de conexión

// Parámetros de paginación
$limit = 10; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page > 0) ? $page : 1; // Asegura que la página es válida
$offset = ($page - 1) * $limit;

// Obtiene el término de búsqueda de la solicitud GET, si está presente
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepara la consulta SQL con el filtro de búsqueda
$sql = "SELECT u.username, u.nombre, m.id, m.meta, m.meta_plus, m.alumnado, m.descuento_base, m.antiguedad, m.meta_preescolar, m.meta_asomate, m.meta_rap, m.meta_escudaria 
        FROM metas m
        JOIN users u ON m.user_id = u.id";
        
// Agrega la condición de búsqueda si hay un término de búsqueda
if ($search !== '') {
    $sql .= " WHERE u.nombre LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sql .= " LIMIT $limit OFFSET $offset";

// Ejecuta la consulta
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

// Contar el total de registros para la paginación con el mismo filtro de búsqueda
$count_sql = "SELECT COUNT(*) AS total FROM metas m
              JOIN users u ON m.user_id = u.id";
              
if ($search !== '') {
    $count_sql .= " WHERE u.nombre LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$count_result = $conn->query($count_sql);
if ($count_result) {
    $total_rows = $count_result->fetch_assoc()['total'];
} else {
    die("Error en la consulta de conteo: " . $conn->error);
}
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Datos de Metas</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Evita que el contenido de la tabla se rompa en varias líneas */
        .table th, .table td {
            white-space: nowrap;
        }

        /* Contenedor de la tabla */
        .table-container {
            overflow-x: auto;
            position: relative;
        }

        /* Columna de acciones fija */
        .actions-col {
            position: -webkit-sticky;
            position: sticky;
            right: 0;
            background: #fff;
            z-index: 10;
        }

        /* Estilo de la columna fija */
        .table th.actions-col, .table td.actions-col {
            z-index: 11;
        }

        /* Asegura que la columna de acciones esté siempre al final */
        .table .actions-col {
            background-color: #f8f9fa;
        }

        /* Estilo para la paginación */
        .pagination {
            justify-content: center;
        }
        thead{
            background-color:#3c8dbc ;
            color:#f8f9fa;
        }
        td{
            align-content: center;
        }

    </style>
</head>
<body>
    <div class="container mt-5" style="margin-left: 5%;">
        <h1 class="mb-4" style=" margin-left:50%;">Meta de los usuarios.</h1>
        <form method="get" action="">
            <div class="form-group">
                <label for="search">Buscar por Nombre:</label>
                <input type="text" class="form-control" name="search" id="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        
        <form method="post" action="process_form.php">
            <?php
            if ($result->num_rows > 0) {
                echo '<table class="table table-bordered border-primary table-custom">';
                echo '<thead>
                        <tr>
                            <th class="col-nombre-completo">Nombre Completo</th>
                            <th>Nombre de Usuario</th>
                            <th>Meta</th>
                            <th>Meta Plus</th>
                            <th>Alumnado</th>
                            <th>Descuento Base</th>
                            <th>Antigüedad</th>
                            <th>Meta Preescolar</th>
                            <th>Meta Asómate</th>
                            <th>Meta RAP</th>
                            <th>Meta Escudaria</th>
                            <th class="actions-column">Acciones</th>
                        </tr>
                      </thead>';
                echo '<tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td class="col-nombre-completo">' . htmlspecialchars($row['nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['meta']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['meta_plus']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['alumnado']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['descuento_base']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['antiguedad']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['meta_preescolar']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['meta_asomate']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['meta_rap']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['meta_escudaria']) . '</td>';
                    echo '<td class="actions-column text-center">
                             <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-icon mr-2" data-toggle="modal" data-target="#editModal" data-id="' . htmlspecialchars($row['id']) . '" data-meta="' . htmlspecialchars($row['meta']) . '" data-meta-plus="' . htmlspecialchars($row['meta_plus']) . '" data-alumnado="' . htmlspecialchars($row['alumnado']) . '" data-descuento-base="' . htmlspecialchars($row['descuento_base']) . '" data-antiguedad="' . htmlspecialchars($row['antiguedad']) . '" data-meta-preescolar="' . htmlspecialchars($row['meta_preescolar']) . '" data-meta-asomate="' . htmlspecialchars($row['meta_asomate']) . '" data-meta-rap="' . htmlspecialchars($row['meta_rap']) . '" data-meta-escudaria="' . htmlspecialchars($row['meta_escudaria']) . '">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-icon" data-toggle="modal" data-target="#deleteModal" data-id="' . htmlspecialchars($row['id']) . '">
                                    <i class="fas fa-trash"></i>
                                </button>
                             </div>
                          </td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';

                // Mostrar paginación
                echo '<nav aria-label="Page navigation">';
                echo '<ul class="pagination">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
                    echo '<a class="page-link" href="?page=' . $i . '&search=' . urlencode($search) . '">' . $i . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</nav>';

            } else {
                echo '<p>No hay metas registradas.</p>';
            }
            $conn->close();
            ?>
        </form>

        <!-- Modal para Editar -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Meta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para Editar -->
                        <form id="editForm" method="post" action="edit_meta.php">
                            <input type="hidden" name="id" id="editId" />
                            <div class="form-group">
                                <label for="editMeta">Meta</label>
                                <input type="text" class="form-control" name="meta" id="editMeta" />
                            </div>
                            <div class="form-group">
                                <label for="editMetaPlus">Meta Plus</label>
                                <input type="text" class="form-control" name="meta_plus" id="editMetaPlus" />
                            </div>
                            <div class="form-group">
                                <label for="editAlumnado">Alumnado</label>
                                <input type="text" class="form-control" name="alumnado" id="editAlumnado" />
                            </div>
                            <div class="form-group">
                                <label for="editDescuentoBase">Descuento Base</label>
                                <input type="text" class="form-control" name="descuento_base" id="editDescuentoBase" />
                            </div>
                            <div class="form-group">
                                <label for="editAntiguedad">Antigüedad</label>
                                <input type="text" class="form-control" name="antiguedad" id="editAntiguedad" />
                            </div>
                            <div class="form-group">
                                <label for="editMetaPreescolar">Meta Preescolar</label>
                                <input type="text" class="form-control" name="meta_preescolar" id="editMetaPreescolar" />
                            </div>
                            <div class="form-group">
                                <label for="editMetaAsomate">Meta Asómate</label>
                                <input type="text" class="form-control" name="meta_asomate" id="editMetaAsomate" />
                            </div>
                            <div class="form-group">
                                <label for="editMetaRap">Meta RAP</label>
                                <input type="text" class="form-control" name="meta_rap" id="editMetaRap" />
                            </div>
                            <div class="form-group">
                                <label for="editMetaEscudaria">Meta Escudaria</label>
                                <input type="text" class="form-control" name="meta_escudaria" id="editMetaEscudaria" />
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Meta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar esta meta?</p>
                        <form id="deleteForm" method="post" action="delete_meta.php">
                            <input type="hidden" name="id" id="deleteId" />
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script para manejar la carga de datos en los modales -->
    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var modal = $(this);

            modal.find('#editId').val(button.data('id'));
            modal.find('#editMeta').val(button.data('meta'));
            modal.find('#editMetaPlus').val(button.data('meta-plus'));
            modal.find('#editAlumnado').val(button.data('alumnado'));
            modal.find('#editDescuentoBase').val(button.data('descuento-base'));
            modal.find('#editAntiguedad').val(button.data('antiguedad'));
            modal.find('#editMetaPreescolar').val(button.data('meta-preescolar'));
            modal.find('#editMetaAsomate').val(button.data('meta-asomate'));
            modal.find('#editMetaRap').val(button.data('meta-rap'));
            modal.find('#editMetaEscudaria').val(button.data('meta-escudaria'));
        });
    </script>
</body>
</html>
