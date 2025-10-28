<?php

namespace App\Helpers;

class PerformanceProfiler
{
    private static $startTime;
    private static $checkpoints = [];

    /**
     * Iniciar el profiling
     */
    public static function start()
    {
        self::$startTime = microtime(true);
        self::$checkpoints = [];
    }

    /**
     * Guardar un checkpoint con nombre
     */
    public static function checkpoint($name)
    {
        $currentTime = microtime(true);
        self::$checkpoints[] = [
            'name' => $name,
            'time' => $currentTime - self::$startTime,
            'elapsed' => isset(self::$checkpoints[count(self::$checkpoints) - 1])
                ? $currentTime - self::$checkpoints[count(self::$checkpoints) - 1]['time']
                : $currentTime - self::$startTime
        ];
    }

    /**
     * Obtener reporte de tiempos
     */
    public static function getReport()
    {
        $report = "=== PERFORMANCE REPORT ===\n\n";
        $report .= "TOTAL TIME: " . number_format((microtime(true) - self::$startTime) * 1000, 2) . " ms\n\n";

        $report .= "CHECKPOINTS:\n";
        $report .= str_repeat("-", 70) . "\n";
        $report .= sprintf("%-40s | %12s | %12s\n", "CHECKPOINT", "TOTAL (ms)", "ELAPSED (ms)");
        $report .= str_repeat("-", 70) . "\n";

        foreach (self::$checkpoints as $checkpoint) {
            $report .= sprintf(
                "%-40s | %12s | %12s\n",
                $checkpoint['name'],
                number_format($checkpoint['time'] * 1000, 2),
                number_format($checkpoint['elapsed'] * 1000, 2)
            );
        }

        $report .= str_repeat("-", 70) . "\n";

        // Top 5 más lentos
        usort(self::$checkpoints, function ($a, $b) {
            return $b['elapsed'] <=> $a['elapsed'];
        });

        $report .= "\nTOP 5 SLOWEST OPERATIONS:\n";
        $report .= str_repeat("-", 70) . "\n";
        foreach (array_slice(self::$checkpoints, 0, 5) as $i => $checkpoint) {
            $report .= ($i + 1) . ". " . $checkpoint['name'] . " - " . number_format($checkpoint['elapsed'] * 1000, 2) . " ms\n";
        }

        return $report;
    }

    /**
     * Logear el reporte automáticamente
     */
    public static function logReport()
    {
        \Log::info(self::getReport());
    }
}
