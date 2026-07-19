<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index(): JsonResponse
    {
        $dbLatency = null;
        $dbStatus = 'error';

        try {
            $dbStart = microtime(true);
            DB::select('SELECT 1');
            $dbLatency = round((microtime(true) - $dbStart) * 1000);
            $dbStatus = 'ok';
        } catch (\Exception $e) {
            $dbStatus = 'error';
        }

        $storageStatus = 'error';
        $diskFreeMb = null;
        $writable = false;

        try {
            $diskFreeMb = round(disk_free_space(storage_path()) / 1048576);
            $testFile = storage_path('app/public/health-test.txt');
            file_put_contents($testFile, 'ok');
            unlink($testFile);
            $writable = true;
            $storageStatus = 'ok';
        } catch (\Exception $e) {
            $storageStatus = 'error';
        }

        $memoryUsage = round(memory_get_usage(true) / 1048576, 1);
        $memoryLimit = ini_get('memory_limit');

        return response()->json([
            'status' => ($dbStatus === 'ok' && $storageStatus === 'ok') ? 'ok' : 'degraded',
            'application' => 'SGC Memoria Castrense',
            'version' => '1.0.0',
            'timestamp' => now()->toIso8601String(),
            'php_version' => phpversion(),
            'checks' => [
                'database' => [
                    'status' => $dbStatus,
                    'latency_ms' => $dbLatency,
                ],
                'storage' => [
                    'status' => $storageStatus,
                    'disk_free_mb' => $diskFreeMb,
                    'writable' => $writable,
                ],
                'memory' => [
                    'usage_mb' => $memoryUsage,
                    'limit' => $memoryLimit,
                ],
            ],
        ]);
    }
}
