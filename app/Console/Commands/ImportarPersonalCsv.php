<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Log;

class ImportarPersonalCsv extends Command
{
    protected $signature = 'importar:personal {archivo}';
    protected $description = 'Importa personal desde un archivo CSV con log de duplicados';

    public function handle()
    {
        $ruta = $this->argument('archivo');

        if (!file_exists($ruta)) {
            $this->error("El archivo no existe en la ruta: $ruta");
            return;
        }

        $this->info("Iniciando importación...");

        // Precargar cédulas para validación en RAM
        $cedulasMap = array_flip(DB::table('personal_migracion')->pluck('cedula_identidad')->all());

        $importados = 0;
        $saltados = [];

        $totalLineas = count(file($ruta)) - 1;
        $bar = $this->output->createProgressBar($totalLineas);


        try {
            DB::beginTransaction();

            LazyCollection::make(function () use ($ruta) {
                $handle = fopen($ruta, 'r');
                $lineaCabecera = fgetcsv($handle, 1000, ";");

                // CORRECCIÓN 2: Limpiar posibles caracteres invisibles (BOM) en la primera cabecera
                if ($lineaCabecera) {
                    $lineaCabecera[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $lineaCabecera[0]);
                }

                $headers = $lineaCabecera;

                while (($line = fgetcsv($handle, 1000, ";")) !== false) {
                    // Verificamos que la línea tenga el mismo número de elementos que la cabecera
                    if (count($headers) === count($line)) {
                        yield array_combine($headers, $line);
                    }
                }
                fclose($handle);
            })
            ->chunk(1000)
            ->each(function ($chunk) use (&$cedulasMap, &$importados, &$saltados, $bar) {
                $lote = [];

                foreach ($chunk as $key => $fila) {
                    $cedula = trim($fila['cedula_identidad']);

                    if (isset($cedulasMap[$cedula])) {
                        $saltados[] = $cedula; // Guardamos la cédula saltada
                        $bar->advance();
                        continue;
                    }

                    $lote[] = [
                        'nombres'           => $fila['nombres'],
                        'cedula_identidad'  => $cedula,
                        'cargo_opsu'        => $fila['cargo_opsu'] ?? null,
                        'cod_nucleo'        => $fila['cod_nucleo'] ?? null,
                        'correo'            => $fila['correo'] ?? null,
                        'tipo_personal'     => $fila['tipo_personal'] ?? null,
                        'sexo'              => $fila['sexo'] ?? null,
                        'telefono'          => $fila['telefono'] ?? null,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];

                    $cedulasMap[$cedula] = true;
                    $importados++;
                    $bar->advance();
                }

                DB::table('personal_migracion')->insert($lote);
            });

            DB::commit();
            $bar->finish();
            $this->newLine(2);

            // Generar archivo de log si hubo saltados
            if (count($saltados) > 0) {
                $nombreLog = 'importacion_duplicados_' . date('Ymd_His') . '.log';
                file_put_contents(storage_path('logs/' . $nombreLog), implode(PHP_EOL, $saltados));
                $this->warn("Se generó un log con " . count($saltados) . " cédulas duplicadas en: storage/logs/$nombreLog");
            }

            $this->info("¡Proceso completado!");
            $this->table(['Resultado', 'Cantidad'], [
                ['Importados correctamente', $importados],
                ['Saltados por duplicidad', count($saltados)]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine();
            $this->error("Error crítico: " . $e->getMessage());
        }
    }
}
