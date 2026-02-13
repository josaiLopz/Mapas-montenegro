<?php
/** @var \App\View\AppView $this */

$this->assign('title', 'Dashboard');
$this->Html->script('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js', ['block' => true]);

$cards = [
    ['label' => 'Escuelas totales (' . (string)$selectedUserName . ')', 'value' => (int)$totalSchools],
    ['label' => 'Escuelas asignadas', 'value' => (int)$assignedSchools],
    ['label' => 'Sin asignar', 'value' => (int)$unassignedSchools],
    ['label' => 'Tasa de asignacion', 'value' => number_format((float)$assignmentRate, 1) . '%'],
    ['label' => 'Ventas Montenegro', 'value' => (int)$montenegroSales],
    ['label' => 'Escuelas verificadas', 'value' => (int)$verifiedSchools],
    ['label' => 'Conversion', 'value' => number_format((float)$salesRate, 1) . '%'],
    ['label' => 'Ventas ' . (string)$selectedYear, 'value' => (int)$salesThisYear],
    ['label' => 'Ventas ' . (string)$previousYear, 'value' => (int)$salesPreviousYear],
    ['label' => 'Variacion ventas YoY', 'value' => number_format((float)$salesYoYDelta, 1) . '%'],
];

$materialCards = [
    ['label' => 'Asignaciones material ' . (string)$selectedYear, 'value' => (int)$materialsAssigned],
    ['label' => 'Asignaciones material ' . (string)$previousYear, 'value' => (int)$materialsAssignedPrev],
    ['label' => 'Variacion asignaciones YoY', 'value' => number_format((float)$materialAssignmentsYoY, 1) . '%'],
    ['label' => 'Proyeccion ' . (string)$selectedYear, 'value' => '$' . number_format((float)$materialsProjection, 2)],
    ['label' => 'Proyeccion ' . (string)$previousYear, 'value' => '$' . number_format((float)$materialsProjectionPrev, 2)],
    ['label' => 'Variacion proyeccion YoY', 'value' => number_format((float)$materialsProjectionYoY, 1) . '%'],
];

$chartPayload = [
    'status' => [
        'labels' => $statusLabels,
        'values' => $statusValues,
    ],
    'salesSplit' => [
        'labels' => ['Venta Montenegro', 'Sin venta Montenegro'],
        'values' => [(int)$montenegroSales, (int)$withoutMontenegroSales],
    ],
    'assignee' => [
        'labels' => $assigneeLabels,
        'values' => $assigneeValues,
    ],
    'verificationSplit' => [
        'labels' => ['Verificadas', 'No verificadas'],
        'values' => [(int)$verifiedSchools, (int)$unverifiedSchools],
    ],
    'sector' => [
        'labels' => $sectorLabels,
        'values' => $sectorValues,
    ],
    'trend' => [
        'labels' => $salesTrend['labels'] ?? [],
        'current' => $salesTrend['current'] ?? [],
        'previous' => $salesTrend['previous'] ?? [],
        'years' => [(string)$previousYear, (string)$selectedYear],
    ],
    'materialsTop' => [
        'labels' => $materialLabels,
        'values' => $materialValues,
    ],
    'materialsYoY' => [
        'labels' => [(string)$previousYear, (string)$selectedYear],
        'assignments' => [(int)$materialsAssignedPrev, (int)$materialsAssigned],
        'projection' => [(float)$materialsProjectionPrev, (float)$materialsProjection],
    ],
];
?>

<div class="dashboard-wrap">
    <div class="dashboard-header">
        <h2>Reportes graficos de escuelas</h2>
        <p>Estadisticas por usuario, ventas, asignaciones y materials con comparacion anual.</p>
    </div>

    <section class="filters-card">
        <?= $this->Form->create(null, ['type' => 'get', 'valueSources' => ['query']]) ?>
            <div class="filters-grid">
                <div>
                    <?= $this->Form->control('user_id', [
                        'label' => 'Usuario',
                        'type' => 'select',
                        'options' => $userOptions,
                        'empty' => false,
                        'default' => '',
                        'value' => $selectedUserId !== null ? (string)$selectedUserId : '',
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('year', [
                        'label' => 'Año',
                        'type' => 'select',
                        'options' => $yearOptions,
                        'empty' => false,
                        'value' => (string)$selectedYear,
                    ]) ?>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="button">Aplicar filtros</button>
                    <?= $this->Html->link('Limpiar', ['controller' => 'Dashboard', 'action' => 'index'], ['class' => 'button button-outline']) ?>
                </div>
            </div>
        <?= $this->Form->end() ?>
    </section>

    <section class="kpi-grid">
        <?php foreach ($cards as $card): ?>
            <article class="kpi-card">
                <span class="kpi-label"><?= h($card['label']) ?></span>
                <strong class="kpi-value"><?= h((string)$card['value']) ?></strong>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="charts-grid">
        <article class="chart-card">
            <h3>Escuelas por estatus</h3>
            <canvas id="statusChart"></canvas>
        </article>

        <article class="chart-card">
            <h3>Ventas Montenegro vs no ventas</h3>
            <canvas id="salesSplitChart"></canvas>
        </article>

        <article class="chart-card">
            <h3>Asignaciones por distribuidor</h3>
            <canvas id="assigneeChart"></canvas>
        </article>

        <article class="chart-card">
            <h3>Ventas por mes <?= h((string)$selectedYear) ?> vs <?= h((string)$previousYear) ?></h3>
            <canvas id="trendChart"></canvas>
        </article>

        <article class="chart-card">
            <h3>Escuelas verificadas</h3>
            <canvas id="verificationChart"></canvas>
        </article>

        <article class="chart-card">
            <h3>Escuelas por sector</h3>
            <canvas id="sectorChart"></canvas>
        </article>
    </section>

    <div class="dashboard-header">
        <h2>Estadisticas de materials</h2>
        <p>Resumen de materiales para <?= h((string)$selectedUserName) ?> con comparacion anual.</p>
    </div>

    <section class="kpi-grid">
        <?php foreach ($materialCards as $card): ?>
            <article class="kpi-card">
                <span class="kpi-label"><?= h($card['label']) ?></span>
                <strong class="kpi-value"><?= h((string)$card['value']) ?></strong>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="charts-grid">
        <article class="chart-card">
            <h3>Top materials por asignacion (<?= h((string)$selectedYear) ?>)</h3>
            <canvas id="materialsTopChart"></canvas>
        </article>

        <article class="chart-card">
            <h3>Comparacion materials <?= h((string)$previousYear) ?> vs <?= h((string)$selectedYear) ?></h3>
            <canvas id="materialsYoYChart"></canvas>
        </article>
    </section>
</div>

<style>
.dashboard-wrap {
    display: grid;
    gap: 18px;
}

.dashboard-header h2 {
    margin: 0;
    font-size: 2.2rem;
    color: #2a241d;
}

.dashboard-header p {
    margin: 6px 0 0;
    color: #6f6860;
}

.filters-card {
    background: #ffffff;
    border: 1px solid #ece1d3;
    border-radius: 14px;
    padding: 14px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
    align-items: end;
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.kpi-card {
    background: #fff9f3;
    border: 1px solid #efdfcf;
    border-radius: 12px;
    padding: 12px 14px;
}

.kpi-label {
    display: block;
    font-size: 1.2rem;
    color: #6f6860;
}

.kpi-value {
    display: block;
    margin-top: 6px;
    font-size: 2.2rem;
    color: #241c14;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 14px;
}

.chart-card {
    background: #ffffff;
    border: 1px solid #ece1d3;
    border-radius: 14px;
    padding: 14px;
}

.chart-card h3 {
    margin: 0 0 12px;
    font-size: 1.7rem;
    color: #2b251f;
}

.chart-card canvas {
    width: 100%;
    min-height: 280px;
    max-height: 320px;
}

@media (max-width: 760px) {
    .chart-card canvas {
        min-height: 240px;
    }
}
</style>

<script>
(() => {
    const payload = <?= json_encode($chartPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    const palette = {
        red: '#aa2334',
        sand: '#ddb892',
        deep: '#2f4858',
        blue: '#33658a',
        green: '#4f772d',
        orange: '#e36414',
        gray: '#6c757d'
    };

    const commonOpts = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: payload.status.labels,
                datasets: [{
                    label: 'Escuelas',
                    data: payload.status.values,
                    borderRadius: 6,
                    backgroundColor: [palette.red, palette.deep, palette.sand, palette.blue, palette.green, palette.orange]
                }]
            },
            options: {
                ...commonOpts,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const splitCtx = document.getElementById('salesSplitChart');
    if (splitCtx) {
        new Chart(splitCtx, {
            type: 'doughnut',
            data: {
                labels: payload.salesSplit.labels,
                datasets: [{
                    data: payload.salesSplit.values,
                    backgroundColor: [palette.green, palette.gray],
                    borderWidth: 1
                }]
            },
            options: commonOpts
        });
    }

    const assigneeCtx = document.getElementById('assigneeChart');
    if (assigneeCtx) {
        new Chart(assigneeCtx, {
            type: 'bar',
            data: {
                labels: payload.assignee.labels,
                datasets: [{
                    label: 'Escuelas asignadas',
                    data: payload.assignee.values,
                    borderRadius: 6,
                    backgroundColor: palette.deep
                }]
            },
            options: {
                ...commonOpts,
                indexAxis: 'y',
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: payload.trend.labels,
                datasets: [{
                    label: 'Ventas ' + payload.trend.years[1],
                    data: payload.trend.current,
                    fill: false,
                    tension: 0.35,
                    borderColor: palette.red,
                    pointRadius: 4,
                    pointHoverRadius: 5
                }, {
                    label: 'Ventas ' + payload.trend.years[0],
                    data: payload.trend.previous,
                    fill: false,
                    tension: 0.35,
                    borderColor: palette.gray,
                    pointRadius: 4,
                    pointHoverRadius: 5
                }]
            },
            options: {
                ...commonOpts,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const verificationCtx = document.getElementById('verificationChart');
    if (verificationCtx) {
        new Chart(verificationCtx, {
            type: 'pie',
            data: {
                labels: payload.verificationSplit.labels,
                datasets: [{
                    data: payload.verificationSplit.values,
                    backgroundColor: [palette.blue, palette.sand],
                    borderWidth: 1
                }]
            },
            options: commonOpts
        });
    }

    const sectorCtx = document.getElementById('sectorChart');
    if (sectorCtx) {
        new Chart(sectorCtx, {
            type: 'bar',
            data: {
                labels: payload.sector.labels,
                datasets: [{
                    label: 'Escuelas por sector',
                    data: payload.sector.values,
                    borderRadius: 6,
                    backgroundColor: palette.orange
                }]
            },
            options: {
                ...commonOpts,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const materialsTopCtx = document.getElementById('materialsTopChart');
    if (materialsTopCtx) {
        new Chart(materialsTopCtx, {
            type: 'bar',
            data: {
                labels: payload.materialsTop.labels,
                datasets: [{
                    label: 'Asignaciones',
                    data: payload.materialsTop.values,
                    borderRadius: 6,
                    backgroundColor: palette.deep
                }]
            },
            options: {
                ...commonOpts,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const materialsYoYCtx = document.getElementById('materialsYoYChart');
    if (materialsYoYCtx) {
        new Chart(materialsYoYCtx, {
            type: 'bar',
            data: {
                labels: payload.materialsYoY.labels,
                datasets: [{
                    label: 'Asignaciones materials',
                    data: payload.materialsYoY.assignments,
                    borderRadius: 6,
                    backgroundColor: palette.orange
                }, {
                    label: 'Proyeccion de venta',
                    data: payload.materialsYoY.projection,
                    borderRadius: 6,
                    backgroundColor: palette.green
                }]
            },
            options: {
                ...commonOpts,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
})();
</script>
