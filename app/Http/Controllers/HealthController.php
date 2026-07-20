<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
        $writable = false;

        try {
            $testFile = storage_path('app/public/health-test.txt');
            file_put_contents($testFile, 'ok');
            unlink($testFile);
            $writable = true;
            $storageStatus = 'ok';
        } catch (\Exception $e) {
            $storageStatus = 'error';
        }

        $data = [
            'status' => ($dbStatus === 'ok' && $storageStatus === 'ok') ? 'ok' : 'degraded',
            'application' => 'SGC Memoria Castrense',
            'timestamp' => now()->toIso8601String(),
            'checks' => [
                'database' => [
                    'status' => $dbStatus,
                    'latency_ms' => $dbLatency,
                ],
                'storage' => [
                    'status' => $storageStatus,
                    'writable' => $writable,
                ],
            ],
        ];

        if (Auth::check() && Auth::user()->isAdmin()) {
            $data['php_version'] = phpversion();
            $data['checks']['memory'] = [
                'usage_mb' => round(memory_get_usage(true) / 1048576, 1),
                'limit' => ini_get('memory_limit'),
            ];
        }

        return response()->json($data);
    }
}
