<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Datasource\FactoryLocator;

class IndexKbCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $mode = $args->getArgument('mode') ?? 'aboutus';

        if ($mode === 'aboutus') {
            return $this->indexAboutUs($io);
        }

        $io->err("Modo no reconocido: {$mode}. Usa: aboutus");
        return Command::CODE_ERROR;
    }

    // ==== Indexar AboutUs (todos los registros) ====
    private function indexAboutUs(ConsoleIo $io): int
    {
        $conn = FactoryLocator::get('Table')->get('KbArticles')->getConnection();

        // Trae AboutUs activos (o todos si quieres)
        $rows = $conn->execute("
            SELECT id, title, content, active
            FROM about_us
            ORDER BY id ASC
        ")->fetchAll('assoc');

        if (!$rows) {
            $io->out("No hay registros en about_us");
            return Command::CODE_SUCCESS;
        }

        $insertedArticles = 0;
        $insertedChunks = 0;

        foreach ($rows as $r) {
            $title = trim((string)($r['title'] ?? 'Acerca de'));
            $content = (string)($r['content'] ?? '');
            $isActive = (int)($r['active'] ?? 1);

            // 1) Insertar kb_articles
            $conn->execute("
                INSERT INTO kb_articles (title, module, tags, body, is_active, created, modified)
                VALUES (:title, :module, :tags, :body, :is_active, NOW(), NOW())
            ", [
                'title' => $title,
                'module' => 'AboutUs',
                'tags' => 'about,acerca,empresa,informacion',
                'body' => $content,
                'is_active' => $isActive ? 1 : 0,
            ]);

            $articleId = (int)$conn->getDriver()->lastInsertId();

            // 2) Crear chunks por párrafos
            $chunks = $this->splitIntoChunks($content);

            if (!$chunks) {
                // fallback: 1 chunk con todo
                $chunks = [$content];
            }

            $i = 1;
            foreach ($chunks as $chunkText) {
                $chunkText = trim(strip_tags($chunkText));
                if ($chunkText === '') continue;

                $meta = json_encode([
                    'source_table' => 'about_us',
                    'source_id' => (int)$r['id'],
                    'module' => 'AboutUs',
                    'chunk_no' => $i
                ], JSON_UNESCAPED_UNICODE);

                $conn->execute("
                    INSERT INTO kb_chunks (article_id, chunk_text, meta, created)
                    VALUES (:article_id, :chunk_text, :meta, NOW())
                ", [
                    'article_id' => $articleId,
                    'chunk_text' => $chunkText,
                    'meta' => $meta
                ]);

                $insertedChunks++;
                $i++;
            }

            $insertedArticles++;
        }

        $io->out("Listo ✅ Artículos insertados: {$insertedArticles} | Chunks insertados: {$insertedChunks}");
        $io->out("Ahora tu bot ya puede contestar usando AboutUs.");
        return Command::CODE_SUCCESS;
    }

    // ====== Divide en párrafos y además limita tamaño ======
    private function splitIntoChunks(string $text, int $maxLen = 700): array
    {
        $text = trim($text);
        if ($text === '') return [];

        // separa por párrafos
        $parts = preg_split("/\r\n\r\n|\n\n|\r\r/", $text) ?: [];
        $out = [];

        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') continue;

            // si un párrafo es muy grande, lo parte por oraciones
            if (mb_strlen($p) > $maxLen) {
                $sentences = preg_split('/(?<=[\.\?\!])\s+/', $p) ?: [$p];
                $buf = '';
                foreach ($sentences as $s) {
                    $s = trim($s);
                    if ($s === '') continue;

                    if (mb_strlen($buf . ' ' . $s) > $maxLen) {
                        if (trim($buf) !== '') $out[] = trim($buf);
                        $buf = $s;
                    } else {
                        $buf = trim($buf . ' ' . $s);
                    }
                }
                if (trim($buf) !== '') $out[] = trim($buf);
            } else {
                $out[] = $p;
            }
        }

        return $out;
    }

    public static function defaultName(): string
    {
        return 'index_kb';
    }

    protected function buildOptionParser(\Cake\Console\ConsoleOptionParser $parser): \Cake\Console\ConsoleOptionParser
    {
        $parser->addArgument('mode', [
            'help' => 'Qué indexar: aboutus',
            'required' => false
        ]);
        return $parser;
    }
}
