<?php if (!empty($errors)): ?>
    <h3>Errores al importar</h3>
    <table>
        <thead>
            <tr>
                <th>Fila</th>
                <th>Errores</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($errors as $error): ?>
            <tr>
                <td><?= h(json_encode($error['row'])) ?></td>
                <td>
                    <?php foreach ($error['errors'] as $field => $fieldErrors): ?>
                        <?= h($field . ': ' . implode(', ', $fieldErrors)) ?><br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p>Escuelas guardadas: <?= $saved ?></p>
