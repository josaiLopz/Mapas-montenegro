<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Datasource\FactoryLocator;

class KbSeedCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $Articles = FactoryLocator::get('Table')->get('KbArticles');
        $Chunks   = FactoryLocator::get('Table')->get('KbChunks');

        $io->out("== KB Seed: creando artículos base ==");

        // Artículo 1: tu pantalla Schools/filtros (basado en el código que compartiste)
        $body1 = $this->schoolsFiltrosDoc();
        $a1 = $Articles->newEntity([
            'title' => 'Pantalla: Schools / filtros (Mapa y filtros)',
            'module' => 'Schools',
            'tags' => 'schools,filtros,mapa,visitas,pin',
            'body' => $body1,
            'is_active' => 1,
        ]);
        $Articles->saveOrFail($a1);
        $this->chunkify($Chunks, (int)$a1->id, $body1);

        // Artículo 2: estatus (colores / textos)
        $body2 = $this->estatusDoc();
        $a2 = $Articles->newEntity([
            'title' => 'Estatus de escuela y colores en el mapa',
            'module' => 'Schools',
            'tags' => 'estatus,mapa,colores',
            'body' => $body2,
            'is_active' => 1,
        ]);
        $Articles->saveOrFail($a2);
        $this->chunkify($Chunks, (int)$a2->id, $body2);

        // Artículo 3: rutas (extracto) para que “entienda” endpoints
        $routesPath = ROOT . DS . 'config' . DS . 'routes.php';
        if (is_file($routesPath)) {
            $routesTxt = (string)file_get_contents($routesPath);
            $body3 = "Rutas del sistema (extracto de config/routes.php):\n\n" . $this->trimHuge($routesTxt);
            $a3 = $Articles->newEntity([
                'title' => 'Rutas del sistema (config/routes.php)',
                'module' => 'Sistema',
                'tags' => 'rutas,router,controllers',
                'body' => $body3,
                'is_active' => 1,
            ]);
            $Articles->saveOrFail($a3);
            $this->chunkify($Chunks, (int)$a3->id, $body3);
        }

        $io->success("KB Seed terminado. (kb_articles + kb_chunks)");
        return self::CODE_SUCCESS;
    }

    private function chunkify($Chunks, int $articleId, string $body): void
    {
        $paras = preg_split("/\R\R+/", $body) ?: [];
        $chunks = [];
        $buf = '';

        foreach ($paras as $p) {
            $p = trim($p);
            if ($p === '') continue;

            if (mb_strlen($buf . "\n\n" . $p) > 1100) {
                if (trim($buf) !== '') $chunks[] = $buf;
                $buf = $p;
            } else {
                $buf = trim($buf) === '' ? $p : ($buf . "\n\n" . $p);
            }
        }
        if (trim($buf) !== '') $chunks[] = $buf;

        foreach ($chunks as $c) {
            $e = $Chunks->newEntity([
                'article_id' => $articleId,
                'chunk_text' => $c,
                'meta' => null,
                'created' => date('Y-m-d H:i:s'),
            ]);
            $Chunks->saveOrFail($e);
        }
    }

    private function trimHuge(string $txt): string
    {
        $txt = trim($txt);
        if (mb_strlen($txt) > 12000) {
            return mb_substr($txt, 0, 12000) . "\n\n[...recortado...]";
        }
        return $txt;
    }

    private function schoolsFiltrosDoc(): string
    {
        return
"Esta pantalla permite filtrar escuelas y verlas en un mapa.\n\n".
"UI principal:\n".
"- Pestañas: Ubicación, Escuelas, Comercial.\n".
"- Botones: Limpiar, Buscar, Nueva Escuela.\n".
"- Panel resultados: lista de escuelas (clic para seleccionar).\n".
"- Mapa: puntos por escuela; clic abre popup (InfoWindow).\n\n".
"Acciones en el popup de una escuela:\n".
"1) Agendar visita: abre modal para fecha/hora y guarda.\n".
"2) Mover pin: crea marcador arrastrable y permite ajustar coordenadas.\n".
"3) Guardar ubicación: POST a Schools/guardarCoordenadas con id, lat, lng.\n".
"4) Cancelar: revierte coordenadas originales.\n".
"5) Editar escuela: abre modal con iframe a Schools/editModal/{id}?layout=ajax.\n".
"6) Gestor de materiales: abre /schools/{id}/materials-manager.\n\n".
"Agenda de visitas (panel derecho):\n".
"- Alcance: Mis / Global.\n".
"- Estado: Pendientes / Completadas.\n".
"- Iniciar ruta: usa geolocalización o selecciona punto en mapa como origen.\n".
"- Abrir en Google Maps: abre ruta externa.\n".
"- Completar: modal de notas y evidencia (max 10MB).\n";
    }

    private function estatusDoc(): string
    {
        return
"Colores por estatus (mapa):\n".
"- noAtendida: gris\n".
"- escuelaPromocion: cyan\n".
"- ventaConfirmada: verde\n".
"- prohibicion: rojo\n".
"- ventaMarcas: naranja\n\n".
"Texto por estatus:\n".
"- No atendida\n".
"- Escuela en promoción\n".
"- Venta confirmada\n".
"- Prohibición\n".
"- Venta otras marcas\n";
    }
}