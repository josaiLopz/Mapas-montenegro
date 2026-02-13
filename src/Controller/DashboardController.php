<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;

/**
 * Dashboard Controller
 *
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        if (!$user) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $schools = $this->fetchTable('Schools');
        $usersTable = $this->fetchTable('Users');
        $schoolsMaterials = $this->fetchTable('SchoolsMaterials');

        $selectedUserId = $this->getSelectedUserId();
        $selectedYear = $this->getSelectedYear();
        $previousYear = $selectedYear - 1;
        [$yearStart, $yearEnd] = $this->buildYearRange($selectedYear);
        [$prevYearStart, $prevYearEnd] = $this->buildYearRange($previousYear);

        $dashboardUsers = $usersTable->find()
            ->select(['id', 'name'])
            ->orderBy(['name' => 'ASC'])
            ->enableHydration(false)
            ->toArray();
        $userOptions = ['' => 'Todos los usuarios'];
        foreach ($dashboardUsers as $row) {
            $id = (int)($row['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $userOptions[(string)$id] = (string)($row['name'] ?? 'Usuario');
        }
        if ($selectedUserId !== null && !isset($userOptions[(string)$selectedUserId])) {
            $selectedUserId = null;
        }
        $schoolFilter = $this->buildSchoolFilter($selectedUserId);
        $selectedUserName = $selectedUserId !== null
            ? ($userOptions[(string)$selectedUserId] ?? 'Usuario seleccionado')
            : 'Todos los usuarios';

        $totalSchools = $schools->find()->where($schoolFilter)->count();
        $assignedSchools = $schools->find()->where($schoolFilter)->where(['user_id IS NOT' => null])->count();
        $unassignedSchools = $schools->find()->where($schoolFilter)->where(['user_id IS' => null])->count();
        $assignmentRate = $totalSchools > 0 ? round(($assignedSchools / $totalSchools) * 100, 1) : 0.0;
        $montenegroSales = $schools->find()->where($schoolFilter)->where(['venta_montenegro' => true])->count();
        $withoutMontenegroSales = max($totalSchools - $montenegroSales, 0);
        $salesRate = $totalSchools > 0 ? round(($montenegroSales / $totalSchools) * 100, 1) : 0.0;
        $verifiedSchools = $schools->find()->where($schoolFilter)->where(['verificada' => true])->count();
        $unverifiedSchools = max($totalSchools - $verifiedSchools, 0);
        $salesThisYear = $schools->find()
            ->where($schoolFilter)
            ->where([
                'venta_montenegro' => true,
                'fecha_decision >=' => $yearStart,
                'fecha_decision <=' => $yearEnd,
            ])
            ->count();
        $salesPreviousYear = $schools->find()
            ->where($schoolFilter)
            ->where([
                'venta_montenegro' => true,
                'fecha_decision >=' => $prevYearStart,
                'fecha_decision <=' => $prevYearEnd,
            ])
            ->count();
        $salesYoYDelta = $salesPreviousYear > 0
            ? round((($salesThisYear - $salesPreviousYear) / $salesPreviousYear) * 100, 1)
            : ($salesThisYear > 0 ? 100.0 : 0.0);

        $statusRows = $this->groupedCountByField($schools, 'estatus', $schoolFilter);

        $statusLabels = [];
        $statusValues = [];
        foreach ($statusRows as $row) {
            $statusLabels[] = $this->formatEstatusLabel((string)($row['label'] ?? 'Sin estatus'));
            $statusValues[] = (int)($row['total'] ?? 0);
        }

        if (empty($statusLabels)) {
            $statusLabels = ['Sin datos'];
            $statusValues = [0];
        }

        $assigneeQuery = $schools->find();
        $assigneeRows = $assigneeQuery
            ->select([
                'label' => 'Users.name',
                'total' => $assigneeQuery->func()->count('*'),
            ])
            ->innerJoinWith('Users')
            ->where($schoolFilter)
            ->where(['Schools.user_id IS NOT' => null])
            ->groupBy(['Users.id', 'Users.name'])
            ->orderBy(['total' => 'DESC'])
            ->limit(10)
            ->enableHydration(false)
            ->toArray();

        $assigneeLabels = [];
        $assigneeValues = [];
        foreach ($assigneeRows as $row) {
            $assigneeLabels[] = (string)($row['label'] ?? 'Sin nombre');
            $assigneeValues[] = (int)($row['total'] ?? 0);
        }

        if (empty($assigneeLabels)) {
            $assigneeLabels = ['Sin datos'];
            $assigneeValues = [0];
        }

        $sectorRows = $this->groupedCountByField($schools, 'sector', $schoolFilter, 8);
        $sectorLabels = [];
        $sectorValues = [];
        foreach ($sectorRows as $row) {
            $sectorLabels[] = (string)($row['label'] ?? 'No capturado');
            $sectorValues[] = (int)($row['total'] ?? 0);
        }

        if (empty($sectorLabels)) {
            $sectorLabels = ['Sin datos'];
            $sectorValues = [0];
        }

        $salesTrend = $this->buildYearSalesComparisonTrend($schools, $selectedYear, $selectedUserId);

        $materialFilter = [
            'SchoolsMaterials.created >=' => $yearStart . ' 00:00:00',
            'SchoolsMaterials.created <=' => $yearEnd . ' 23:59:59',
        ];
        $materialPrevFilter = [
            'SchoolsMaterials.created >=' => $prevYearStart . ' 00:00:00',
            'SchoolsMaterials.created <=' => $prevYearEnd . ' 23:59:59',
        ];

        $materialsAssigned = $this->buildMaterialsBaseQuery($schoolsMaterials, $selectedUserId)
            ->where($materialFilter)
            ->count();
        $materialsAssignedPrev = $this->buildMaterialsBaseQuery($schoolsMaterials, $selectedUserId)
            ->where($materialPrevFilter)
            ->count();
        $materialAssignmentsYoY = $materialsAssignedPrev > 0
            ? round((($materialsAssigned - $materialsAssignedPrev) / $materialsAssignedPrev) * 100, 1)
            : ($materialsAssigned > 0 ? 100.0 : 0.0);
        $materialsProjection = $this->sumMaterialsField($schoolsMaterials, $selectedUserId, 'proyeccion_venta', $materialFilter);
        $materialsProjectionPrev = $this->sumMaterialsField($schoolsMaterials, $selectedUserId, 'proyeccion_venta', $materialPrevFilter);
        $materialsProjectionYoY = $materialsProjectionPrev > 0
            ? round((($materialsProjection - $materialsProjectionPrev) / $materialsProjectionPrev) * 100, 1)
            : ($materialsProjection > 0 ? 100.0 : 0.0);

        $materialTopQuery = $schoolsMaterials->find();
        $materialTopQuery
            ->select([
                'label' => 'Materials.nombre',
                'total' => $materialTopQuery->func()->count('*'),
            ])
            ->innerJoinWith('Materials')
            ->where($materialFilter)
            ->groupBy(['Materials.id', 'Materials.nombre'])
            ->orderBy(['total' => 'DESC'])
            ->limit(8)
            ->enableHydration(false);

        if ($selectedUserId !== null) {
            $materialTopQuery->innerJoinWith('Schools', function (SelectQuery $q) use ($selectedUserId) {
                return $q->where(['Schools.user_id' => $selectedUserId]);
            });
        }

        $materialRows = $materialTopQuery->toArray();
        $materialLabels = [];
        $materialValues = [];
        foreach ($materialRows as $row) {
            $materialLabels[] = (string)($row['label'] ?? 'Sin material');
            $materialValues[] = (int)($row['total'] ?? 0);
        }
        if (empty($materialLabels)) {
            $materialLabels = ['Sin datos'];
            $materialValues = [0];
        }

        $currentYear = (int)date('Y');
        $yearOptions = [];
        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
            $yearOptions[(string)$y] = (string)$y;
        }
        if (!isset($yearOptions[(string)$selectedYear])) {
            $yearOptions[(string)$selectedYear] = (string)$selectedYear;
            krsort($yearOptions, SORT_NUMERIC);
        }

        $this->set(compact(
            'user',
            'selectedUserId',
            'selectedUserName',
            'selectedYear',
            'previousYear',
            'userOptions',
            'yearOptions',
            'totalSchools',
            'assignedSchools',
            'unassignedSchools',
            'assignmentRate',
            'montenegroSales',
            'withoutMontenegroSales',
            'salesRate',
            'salesThisYear',
            'salesPreviousYear',
            'salesYoYDelta',
            'verifiedSchools',
            'unverifiedSchools',
            'statusLabels',
            'statusValues',
            'assigneeLabels',
            'assigneeValues',
            'sectorLabels',
            'sectorValues',
            'salesTrend',
            'materialsAssigned',
            'materialsAssignedPrev',
            'materialAssignmentsYoY',
            'materialsProjection',
            'materialsProjectionPrev',
            'materialsProjectionYoY',
            'materialLabels',
            'materialValues'
        ));
    }

    /**
     * View method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dashboard = $this->Dashboard->get($id, contain: []);
        $this->set(compact('dashboard'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dashboard = $this->Dashboard->newEmptyEntity();
        if ($this->request->is('post')) {
            $dashboard = $this->Dashboard->patchEntity($dashboard, $this->request->getData());
            if ($this->Dashboard->save($dashboard)) {
                $this->Flash->success(__('The dashboard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dashboard could not be saved. Please, try again.'));
        }
        $this->set(compact('dashboard'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dashboard = $this->Dashboard->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dashboard = $this->Dashboard->patchEntity($dashboard, $this->request->getData());
            if ($this->Dashboard->save($dashboard)) {
                $this->Flash->success(__('The dashboard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dashboard could not be saved. Please, try again.'));
        }
        $this->set(compact('dashboard'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dashboard = $this->Dashboard->get($id);
        if ($this->Dashboard->delete($dashboard)) {
            $this->Flash->success(__('The dashboard has been deleted.'));
        } else {
            $this->Flash->error(__('The dashboard could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function formatEstatusLabel(string $status): string
    {
        $map = [
            'noAtendida' => 'No atendida',
            'escuelaPromocion' => 'Escuela en promocion',
            'ventaConfirmada' => 'Venta confirmada',
            'prohibicion' => 'Prohibicion',
            'ventaMarcas' => 'Venta otras marcas',
        ];

        return $map[$status] ?? ($status !== '' ? $status : 'Sin estatus');
    }

    private function groupedCountByField(Table $schools, string $field, array $where = [], int $limit = 0): array
    {
        $query = $schools->find();
        $rows = $query
            ->select([
                'label' => $field,
                'total' => $query->func()->count('*'),
            ])
            ->where($where)
            ->groupBy([$field])
            ->orderBy(['total' => 'DESC'])
            ->enableHydration(false);

        if ($limit > 0) {
            $rows->limit($limit);
        }

        $result = [];
        foreach ($rows->toArray() as $row) {
            $rawLabel = trim((string)($row['label'] ?? ''));
            $result[] = [
                'label' => $rawLabel !== '' ? $rawLabel : 'No capturado',
                'total' => (int)($row['total'] ?? 0),
            ];
        }

        return $result;
    }

    private function buildYearSalesComparisonTrend(Table $schools, int $year, ?int $userId = null): array
    {
        $monthMapCurrent = [];
        $monthMapPrevious = [];
        $labels = [];
        $currentYear = $year;
        $previousYear = $year - 1;

        for ($month = 1; $month <= 12; $month++) {
            $monthKey = str_pad((string)$month, 2, '0', STR_PAD_LEFT);
            $monthMapCurrent[$monthKey] = 0;
            $monthMapPrevious[$monthKey] = 0;
            $labels[] = $this->formatMonthLabel(FrozenDate::create($year, $month, 1));
        }

        [$yearStart, $yearEnd] = $this->buildYearRange($currentYear);
        [$prevStart, $prevEnd] = $this->buildYearRange($previousYear);
        $baseFilter = $this->buildSchoolFilter($userId);

        $currentRows = $schools->find()
            ->select(['fecha_decision'])
            ->where($baseFilter)
            ->where([
                'venta_montenegro' => true,
                'fecha_decision IS NOT' => null,
                'fecha_decision >=' => $yearStart,
                'fecha_decision <=' => $yearEnd,
            ])
            ->enableHydration(false)
            ->toArray();

        $previousRows = $schools->find()
            ->select(['fecha_decision'])
            ->where($baseFilter)
            ->where([
                'venta_montenegro' => true,
                'fecha_decision IS NOT' => null,
                'fecha_decision >=' => $prevStart,
                'fecha_decision <=' => $prevEnd,
            ])
            ->enableHydration(false)
            ->toArray();

        foreach ($currentRows as $row) {
            $rawDate = (string)($row['fecha_decision'] ?? '');
            $monthKey = substr($rawDate, 5, 2);
            if ($monthKey !== '' && array_key_exists($monthKey, $monthMapCurrent)) {
                $monthMapCurrent[$monthKey]++;
            }
        }

        foreach ($previousRows as $row) {
            $rawDate = (string)($row['fecha_decision'] ?? '');
            $monthKey = substr($rawDate, 5, 2);
            if ($monthKey !== '' && array_key_exists($monthKey, $monthMapPrevious)) {
                $monthMapPrevious[$monthKey]++;
            }
        }

        return [
            'labels' => $labels,
            'current' => array_values($monthMapCurrent),
            'previous' => array_values($monthMapPrevious),
        ];
    }

    private function formatMonthLabel(FrozenDate $date): string
    {
        $months = [
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic',
        ];

        $monthNumber = (int)$date->format('n');
        $monthLabel = $months[$monthNumber] ?? $date->format('M');

        return $monthLabel;
    }

    private function buildSchoolFilter(?int $selectedUserId): array
    {
        if ($selectedUserId === null) {
            return [];
        }

        return ['user_id' => $selectedUserId];
    }

    private function getSelectedUserId(): ?int
    {
        $raw = $this->request->getQuery('user_id');
        if ($raw === null || $raw === '') {
            return null;
        }

        $value = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return $value === false ? null : (int)$value;
    }

    private function getSelectedYear(): int
    {
        $raw = $this->request->getQuery('year');
        $current = (int)date('Y');
        if ($raw === null || $raw === '') {
            return $current;
        }

        $value = filter_var($raw, FILTER_VALIDATE_INT);
        if ($value === false) {
            return $current;
        }

        $year = (int)$value;
        if ($year < 2020 || $year > $current + 1) {
            return $current;
        }

        return $year;
    }

    private function buildYearRange(int $year): array
    {
        return [
            sprintf('%d-01-01', $year),
            sprintf('%d-12-31', $year),
        ];
    }

    private function buildMaterialsBaseQuery(Table $schoolsMaterials, ?int $selectedUserId): SelectQuery
    {
        $query = $schoolsMaterials->find();
        if ($selectedUserId !== null) {
            $query->innerJoinWith('Schools', function (SelectQuery $q) use ($selectedUserId) {
                return $q->where(['Schools.user_id' => $selectedUserId]);
            });
        }

        return $query;
    }

    private function sumMaterialsField(
        Table $schoolsMaterials,
        ?int $selectedUserId,
        string $field,
        array $where = []
    ): float {
        $query = $this->buildMaterialsBaseQuery($schoolsMaterials, $selectedUserId);
        $row = $query
            ->select(['total' => $query->func()->sum("SchoolsMaterials.{$field}")])
            ->where($where)
            ->enableHydration(false)
            ->first();

        return round((float)($row['total'] ?? 0), 2);
    }
}
