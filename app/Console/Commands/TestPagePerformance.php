<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestPagePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:performance {page=inicio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test page performance with detailed timing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = $this->argument('page');

        DB::enableQueryLog();

        $this->info("Testing performance of page: {$page}");
        $this->info("=====================================\n");

        $startTime = microtime(true);

        // Simulate the page load
        if ($page == 'inicio') {
            $this->testInicioPage();
        } elseif ($page == 'planillas') {
            $this->testPlanillasPage();
        }

        $totalTime = (microtime(true) - $startTime) * 1000;

        $this->info("\n=== TOTAL TIME: " . number_format($totalTime, 2) . " ms ===");

        // Get query log
        $queries = DB::getQueryLog();

        $this->info("\n=== QUERY PERFORMANCE ===\n");
        $this->displayQueryStats($queries);

        return 0;
    }

    private function testInicioPage()
    {
        $checkpoints = [];

        // Checkpoint 1
        $start = microtime(true);
        $empresas = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('empresa as descripcion')
            ->distinct()
            ->orderBy('empresa')
            ->get()
            ->map(function ($item) {
                return (object) ['descripcion' => $item->descripcion];
            });
        $checkpoints[] = ['name' => 'Query empresas (lomar_prod)', 'time' => (microtime(true) - $start) * 1000];

        // Checkpoint 2
        $start = microtime(true);
        $procesos = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('proceso as nombre')
            ->distinct()
            ->orderBy('proceso')
            ->get()
            ->map(function ($item) {
                return (object) ['nombre' => strtoupper($item->nombre)];
            });
        $checkpoints[] = ['name' => 'Query procesos (lomar_prod)', 'time' => (microtime(true) - $start) * 1000];

        // Checkpoint 3
        $start = microtime(true);
        $proveedores = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('proveedor as descripcion')
            ->distinct()
            ->orderBy('proveedor')
            ->get()
            ->map(function ($item) {
                return (object) ['descripcion' => $item->descripcion];
            });
        $checkpoints[] = ['name' => 'Query proveedores (lomar_prod)', 'time' => (microtime(true) - $start) * 1000];

        // Checkpoint 4
        $start = microtime(true);
        $especies = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('especie as descripcion')
            ->distinct()
            ->orderBy('especie')
            ->get()
            ->map(function ($item) {
                return (object) ['descripcion' => $item->descripcion];
            });
        $checkpoints[] = ['name' => 'Query especies (lomar_prod)', 'time' => (microtime(true) - $start) * 1000];

        // Checkpoint 5-9
        $start = microtime(true);
        $turnos = DB::select('SELECT id,nombre FROM [administracion].[dbo].[tipos_turno] WHERE activo=1 ORDER BY id ASC;');
        $checkpoints[] = ['name' => 'Query turnos', 'time' => (microtime(true) - $start) * 1000];

        $start = microtime(true);
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $checkpoints[] = ['name' => 'Query supervisores', 'time' => (microtime(true) - $start) * 1000];

        $start = microtime(true);
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');
        $checkpoints[] = ['name' => 'Query planilleros', 'time' => (microtime(true) - $start) * 1000];

        $start = microtime(true);
        $jefes_turno = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=4 AND activo = 1 ORDER BY nombre ASC;');
        $checkpoints[] = ['name' => 'Query jefes_turno', 'time' => (microtime(true) - $start) * 1000];

        $start = microtime(true);
        $tipos_planilla = DB::select('SELECT cod_tipo_planilla, nombre FROM pst.dbo.tipo_planilla WHERE activo = 1 ORDER BY nombre ASC;');
        $checkpoints[] = ['name' => 'Query tipos_planilla', 'time' => (microtime(true) - $start) * 1000];

        // Checkpoints 10-12
        $start = microtime(true);
        $fechaHoy = \Carbon\Carbon::now()->format('Y-m-d');
        $fechaHace7Dias = \Carbon\Carbon::now()->subDays(7)->format('Y-m-d');

        $planillas7dias = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1)
            ->whereBetween('fec_turno', [$fechaHace7Dias, $fechaHoy])
            ->orderByDesc('fec_turno')
            ->get();
        $checkpoints[] = ['name' => 'Query planillas7dias', 'time' => (microtime(true) - $start) * 1000];

        $start = microtime(true);
        $planillasHoy = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1)
            ->whereDate('fec_turno', $fechaHoy)
            ->orderByDesc('fec_turno')
            ->get();
        $checkpoints[] = ['name' => 'Query planillasHoy', 'time' => (microtime(true) - $start) * 1000];

        $start = microtime(true);
        $noGuardado = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 0)
            ->where('cod_usuario_crea', 1) // Dummy user
            ->orderByDesc('fec_turno')
            ->get();
        $checkpoints[] = ['name' => 'Query noGuardado', 'time' => (microtime(true) - $start) * 1000];

        $this->displayCheckpoints($checkpoints);
    }

    private function testPlanillasPage()
    {
        $this->info("Planillas page not yet implemented");
    }

    private function displayCheckpoints($checkpoints)
    {
        $this->info("=== CHECKPOINT TIMES ===\n");

        usort($checkpoints, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        foreach ($checkpoints as $i => $checkpoint) {
            $this->line(sprintf(
                "%2d. %-45s | %8.2f ms",
                $i + 1,
                $checkpoint['name'],
                $checkpoint['time']
            ));
        }
    }

    private function displayQueryStats($queries)
    {
        $queryTimes = [];
        foreach ($queries as $query) {
            $queryTimes[] = [
                'time' => $query['time'],
                'query' => substr($query['query'], 0, 100)
            ];
        }

        usort($queryTimes, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        foreach ($queryTimes as $i => $q) {
            if ($i < 10) {
                $this->line(sprintf(
                    "%2d. %s... | %8.2f ms",
                    $i + 1,
                    $q['query'],
                    $q['time']
                ));
            }
        }
    }
}
