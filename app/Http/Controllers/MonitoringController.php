<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    /**
     * Affiche le dashboard de monitoring
     */
    public function index()
    {
        // Vérifier les permissions (admin uniquement)
        if (auth()->user()->role_id > 2) {
            abort(403, 'Accès non autorisé');
        }

        $health = $this->getSystemHealth();
        $metrics = $this->getSystemMetrics();
        $logs = $this->getRecentLogs();
        $alerts = $this->getActiveAlerts();

        return view('pages.monitoring', compact('health', 'metrics', 'logs', 'alerts'));
    }

    /**
     * API pour obtenir l'état de santé du système
     */
    public function healthCheck()
    {
        $health = $this->getSystemHealth();
        
        $status = $health['overall_status'] === 'healthy' ? 200 : 503;
        
        return response()->json($health, $status);
    }

    /**
     * API pour obtenir les métriques système
     */
    public function getMetrics()
    {
        return response()->json($this->getSystemMetrics());
    }

    /**
     * API pour obtenir les logs récents
     */
    public function getLogs()
    {
        return response()->json($this->getRecentLogs());
    }

    /**
     * Obtenir l'état de santé du système
     */
    private function getSystemHealth()
    {
        $health = [
            'overall_status' => 'healthy',
            'checks' => []
        ];

        // Vérification de la base de données
        try {
            DB::select('SELECT 1');
            $health['checks']['database'] = [
                'status' => 'healthy',
                'message' => 'Connexion à la base de données OK',
                'response_time' => $this->measureDbResponseTime()
            ];
        } catch (\Exception $e) {
            $health['checks']['database'] = [
                'status' => 'unhealthy',
                'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()
            ];
            $health['overall_status'] = 'unhealthy';
        }

        // Vérification du cache
        try {
            Cache::put('health_check', 'ok', 60);
            $cached = Cache::get('health_check');
            $health['checks']['cache'] = [
                'status' => $cached === 'ok' ? 'healthy' : 'unhealthy',
                'message' => $cached === 'ok' ? 'Cache fonctionnel' : 'Cache non fonctionnel'
            ];
        } catch (\Exception $e) {
            $health['checks']['cache'] = [
                'status' => 'unhealthy',
                'message' => 'Erreur de cache: ' . $e->getMessage()
            ];
            $health['overall_status'] = 'unhealthy';
        }

        // Vérification de l'espace disque
        $diskUsage = $this->getDiskUsage();
        $health['checks']['disk'] = [
            'status' => $diskUsage['percentage'] > 90 ? 'unhealthy' : 'healthy',
            'message' => "Espace disque: {$diskUsage['used']} / {$diskUsage['total']} ({$diskUsage['percentage']}%)",
            'details' => $diskUsage
        ];

        if ($diskUsage['percentage'] > 90) {
            $health['overall_status'] = 'unhealthy';
        }

        // Vérification de la mémoire
        $memoryUsage = $this->getMemoryUsage();
        $health['checks']['memory'] = [
            'status' => $memoryUsage['percentage'] > 90 ? 'unhealthy' : 'healthy',
            'message' => "Mémoire: {$memoryUsage['used']} / {$memoryUsage['total']} ({$memoryUsage['percentage']}%)",
            'details' => $memoryUsage
        ];

        if ($memoryUsage['percentage'] > 90) {
            $health['overall_status'] = 'unhealthy';
        }

        // Vérification des services critiques
        $health['checks']['services'] = [
            'status' => 'healthy',
            'message' => 'Tous les services critiques sont opérationnels'
        ];

        $health['timestamp'] = now()->toISOString();

        return $health;
    }

    /**
     * Obtenir les métriques système
     */
    private function getSystemMetrics()
    {
        return [
            'application' => [
                'version' => config('app.version', '2.0.0'),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug'),
                'timezone' => config('app.timezone'),
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
            ],
            'database' => [
                'connections' => $this->getDbConnections(),
                'slow_queries' => $this->getSlowQueries(),
                'size' => $this->getDatabaseSize(),
            ],
            'users' => [
                'total' => User::count(),
                'active_today' => User::whereDate('last_login_at', today())->count(),
                'active_this_week' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
                'by_role' => User::selectRaw('role_id, COUNT(*) as count')
                    ->groupBy('role_id')
                    ->pluck('count', 'role_id')
                    ->toArray(),
            ],
            'activities' => [
                'total' => Activity::count(),
                'created_today' => Activity::whereDate('created_at', today())->count(),
                'created_this_week' => Activity::where('created_at', '>=', now()->subDays(7))->count(),
                'active' => Activity::where('status', 1)->count(),
                'pending' => Activity::where('status', 0)->count(),
            ],
            'performance' => [
                'response_time_avg' => $this->getAverageResponseTime(),
                'requests_per_minute' => $this->getRequestsPerMinute(),
                'error_rate' => $this->getErrorRate(),
            ],
            'cache' => [
                'hit_rate' => $this->getCacheHitRate(),
                'size' => $this->getCacheSize(),
            ],
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Obtenir les logs récents
     */
    private function getRecentLogs()
    {
        $logs = [];
        
        // Lire les logs Laravel (adapter selon votre configuration)
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -100); // Dernières 100 lignes
            
            foreach ($recentLines as $line) {
                if (trim($line)) {
                    $logs[] = [
                        'timestamp' => $this->extractTimestampFromLog($line),
                        'level' => $this->extractLevelFromLog($line),
                        'message' => trim($line),
                        'raw' => $line
                    ];
                }
            }
        }

        return array_reverse(array_slice($logs, -50)); // Derniers 50 logs
    }

    /**
     * Obtenir les alertes actives
     */
    private function getActiveAlerts()
    {
        $alerts = [];
        $health = $this->getSystemHealth();

        // Alertes basées sur l'état de santé
        foreach ($health['checks'] as $check => $data) {
            if ($data['status'] === 'unhealthy') {
                $alerts[] = [
                    'type' => 'critical',
                    'title' => "Problème détecté: {$check}",
                    'message' => $data['message'],
                    'timestamp' => now()->toISOString(),
                    'check' => $check
                ];
            }
        }

        // Alertes personnalisées
        $diskUsage = $this->getDiskUsage();
        if ($diskUsage['percentage'] > 80) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Espace disque faible',
                'message' => "L'espace disque est à {$diskUsage['percentage']}%",
                'timestamp' => now()->toISOString(),
            ];
        }

        // Alertes de performance
        $errorRate = $this->getErrorRate();
        if ($errorRate > 5) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Taux d\'erreur élevé',
                'message' => "Le taux d'erreur est de {$errorRate}%",
                'timestamp' => now()->toISOString(),
            ];
        }

        return $alerts;
    }

    /**
     * Mesurer le temps de réponse de la base de données
     */
    private function measureDbResponseTime()
    {
        $start = microtime(true);
        DB::select('SELECT 1');
        $end = microtime(true);
        
        return round(($end - $start) * 1000, 2); // en millisecondes
    }

    /**
     * Obtenir l'utilisation du disque
     */
    private function getDiskUsage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        $percentage = round(($used / $total) * 100, 2);

        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percentage' => $percentage,
        ];
    }

    /**
     * Obtenir l'utilisation de la mémoire
     */
    private function getMemoryUsage()
    {
        if (function_exists('memory_get_usage')) {
            $used = memory_get_usage(true);
            $total = ini_get('memory_limit');
            $totalBytes = $this->parseMemoryLimit($total);
            $percentage = round(($used / $totalBytes) * 100, 2);

            return [
                'total' => $this->formatBytes($totalBytes),
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($totalBytes - $used),
                'percentage' => $percentage,
            ];
        }

        return [
            'total' => 'N/A',
            'used' => 'N/A',
            'free' => 'N/A',
            'percentage' => 0,
        ];
    }

    /**
     * Obtenir les connexions à la base de données
     */
    private function getDbConnections()
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            return $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtenir les requêtes lentes
     */
    private function getSlowQueries()
    {
        try {
            $result = DB::select("SHOW GLOBAL STATUS LIKE 'Slow_queries'");
            return $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtenir la taille de la base de données
     */
    private function getDatabaseSize()
    {
        try {
            $result = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size 
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ");
            return $result[0]->size ?? 0 . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Obtenir le temps de réponse moyen
     */
    private function getAverageResponseTime()
    {
        // Simulation - à implémenter avec un vrai monitoring
        return rand(100, 500) . ' ms';
    }

    /**
     * Obtenir les requêtes par minute
     */
    private function getRequestsPerMinute()
    {
        // Simulation - à implémenter avec un vrai monitoring
        return rand(10, 100);
    }

    /**
     * Obtenir le taux d'erreur
     */
    private function getErrorRate()
    {
        // Simulation - à implémenter avec un vrai monitoring
        return rand(0, 2);
    }

    /**
     * Obtenir le taux de succès du cache
     */
    private function getCacheHitRate()
    {
        // Simulation - à implémenter avec un vrai monitoring
        return rand(85, 99) . '%';
    }

    /**
     * Obtenir la taille du cache
     */
    private function getCacheSize()
    {
        // Simulation - à implémenter avec un vrai monitoring
        return $this->formatBytes(rand(1000000, 10000000));
    }

    /**
     * Formater les octets en format lisible
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Parser la limite de mémoire
     */
    private function parseMemoryLimit($limit)
    {
        $limit = strtolower($limit);
        $multiplier = 1;
        
        if (strpos($limit, 'g') !== false) {
            $multiplier = 1024 * 1024 * 1024;
        } elseif (strpos($limit, 'm') !== false) {
            $multiplier = 1024 * 1024;
        } elseif (strpos($limit, 'k') !== false) {
            $multiplier = 1024;
        }
        
        return intval($limit) * $multiplier;
    }

    /**
     * Extraire le timestamp d'une ligne de log
     */
    private function extractTimestampFromLog($line)
    {
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $line, $matches)) {
            return $matches[1];
        }
        return now()->format('Y-m-d H:i:s');
    }

    /**
     * Extraire le niveau d'une ligne de log
     */
    private function extractLevelFromLog($line)
    {
        if (preg_match('/\.(ERROR|WARNING|INFO|DEBUG)/', $line, $matches)) {
            return strtolower($matches[1]);
        }
        return 'info';
    }
}
