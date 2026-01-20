<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCsvData extends Command
{
    protected $signature = 'import:csv {--only=} {--truncate}';
    protected $description = 'Import CSV files from the project root into tables';

    public function handle(): int
    {
        $definitions = [
            'department' => [
                'file' => 'department.csv',
                'table' => 'department',
            ],
            'province' => [
                'file' => 'province.csv',
                'table' => 'province',
            ],
            'district' => [
                'file' => 'district.csv',
                'table' => 'district',
            ],
            'languages' => [
                'file' => 'languages.csv',
                'table' => 'languages',
            ],
            'etnias' => [
                'file' => 'etnias.csv',
                'table' => 'etnias',
            ],
            'ipress' => [
                'file' => 'ipress.csv',
                'table' => 'ipress',
                'map' => [
                    'red code' => 'red_code',
                ],
            ],
            'cie10' => [
                'file' => 'cie10.csv',
                'table' => 'cie10',
            ],
        ];

        $only = $this->option('only');
        $onlyList = [];
        if ($only) {
            $onlyList = array_filter(array_map('trim', explode(',', $only)));
        }

        foreach ($definitions as $key => $definition) {
            if ($onlyList && !in_array($key, $onlyList, true)) {
                continue;
            }

            $path = base_path($definition['file']);
            if (!file_exists($path)) {
                $this->warn("Missing file: {$definition['file']}");
                continue;
            }

            $table = $definition['table'];
            if ($this->option('truncate')) {
                DB::table($table)->truncate();
            }

            $this->info("Importing {$definition['file']} into {$table}...");
            $count = $this->importFile($path, $table, $definition['map'] ?? []);
            $this->info("Imported {$count} rows into {$table}.");
        }

        return self::SUCCESS;
    }

    private function importFile(string $path, string $table, array $headerMap): int
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->error("Unable to open file: {$path}");
            return 0;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            $this->error("Empty CSV file: {$path}");
            return 0;
        }

        $columns = [];
        foreach ($header as $column) {
            $normalized = $this->normalizeHeader($column);
            $columns[] = $headerMap[$normalized] ?? $normalized;
        }

        $batch = [];
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if ($row === [null] || $row === false) {
                continue;
            }

            $item = [];
            foreach ($columns as $index => $column) {
                if ($column === '') {
                    continue;
                }
                $value = $row[$index] ?? null;
                if ($value !== null) {
                    $value = trim($value);
                }
                if ($value === '' || $value === '?' || (is_string($value) && strcasecmp($value, 'null') === 0)) {
                    $value = null;
                }
                $item[$column] = $value;
            }

            if ($item) {
                $batch[] = $item;
                $count++;
            }

            if (count($batch) >= 500) {
                DB::table($table)->insert($batch);
                $batch = [];
            }
        }

        if ($batch) {
            DB::table($table)->insert($batch);
        }

        fclose($handle);

        return $count;
    }

    private function normalizeHeader(string $value): string
    {
        $value = trim($value);
        $value = str_replace("\xEF\xBB\xBF", '', $value);

        return strtolower($value);
    }
}
