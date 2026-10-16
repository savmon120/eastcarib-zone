<?php

namespace Drupal\ecz_vatsim\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;

class VatsimStatsController extends ControllerBase {

    protected $client;

    public function __construct() {
        $this->client = new Client([
            'timeout' => 10,
        ]);
    }

    public function getStats($cid) {
        try {
            $statsUrl = "https://api.vatsim.net/v2/members/$cid/stats";
            $response = $this->client->get($statsUrl);
            $stats = json_decode($response->getBody(), true);

            return new JsonResponse([
                'cid' => $cid,
                'atc_hours_total' => $stats['atc'] ?? 0,
                'pilot_hours_total' => $stats['pilot'] ?? 0,
                'ratings' => [
                    's1' => $stats['s1'] ?? 0,
                    's2' => $stats['s2'] ?? 0,
                    's3' => $stats['s3'] ?? 0,
                    'c1' => $stats['c1'] ?? 0,
                    'c2' => $stats['c2'] ?? 0,
                    'c3' => $stats['c3'] ?? 0,
                ],
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getAtc($cid) {
    try {
        $url = "https://api.vatsim.net/v2/members/$cid/atc";
        $response = $this->client->get($url);
        $sessions = json_decode($response->getBody(), true);

        // VATSIM wraps ATC sessions inside "items"
        $sessionItems = $sessions['items'] ?? [];

        $now = new \DateTime();
        $thirtyDaysAgo = (clone $now)->modify('-30 days');
        $weekAgo = (clone $now)->modify('-7 days');

        $totalMinutes30d = 0;
        $sessionsThisWeek = 0;
        $positionCounts = [];

        foreach ($sessionItems as $session) {

            if (!is_array($session) || empty($session['connection_id'])) {
                continue;
            }

            $conn = $session['connection_id'];

            if (empty($conn['start']) || empty($conn['end'])) {
                continue;
            }

            $start = new \DateTime($conn['start']);
            $end = new \DateTime($conn['end']);
            $duration = $end->getTimestamp() - $start->getTimestamp();
            $minutes = $duration / 60;

            if ($start >= $thirtyDaysAgo) {
                $totalMinutes30d += $minutes;
            }

            if ($start >= $weekAgo) {
                $sessionsThisWeek++;
            }

            $pos = $conn['callsign'] ?? null;
            if ($pos) {
                $positionCounts[$pos] = ($positionCounts[$pos] ?? 0) + 1;
            }
        }

        arsort($positionCounts);
        $topPosition = array_key_first($positionCounts);

        return new JsonResponse([
            'hours_30d' => round($totalMinutes30d / 60, 2),
            'sessions_week' => $sessionsThisWeek,
            'top_position' => $topPosition,
            'raw_sessions' => $sessionItems,
        ]);

    } catch (\Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 500);
    }
}

}
