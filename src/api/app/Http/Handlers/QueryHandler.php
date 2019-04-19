<?php

namespace App\Http\Handlers;

use Illuminate\Http\Request;

class QueryHandler
{
    public function __invoke(Request $request)
    {
        $json   = $request->json();
        $range  = $json->get('range');
        $target = $json->get('targets')[0]['target'];

        $tz   = new \DateTimeZone('Europe/Madrid');
        $from = \DateTimeImmutable::createFromFormat("Y-m-d\TH:i:s.uP", $range['from'], $tz);
        $to   = \DateTimeImmutable::createFromFormat("Y-m-d\TH:i:s.uP", $range['to'], $tz);

        return ['target' => $target, 'datapoints' => $this->getDataPoints($from, $to, $target)];
    }

    private function getDataPoints($from, $to, $target)
    {
        $interval = new \DateInterval('PT1H');
        $period   = new \DatePeriod($from, $interval, $to->add($interval));

        $dataPoints = [];
        foreach ($period as $date) {
            $value        = $target > 50 ? rand(0, 100) : $target;
            $dataPoints[] = [$value, strtotime($date->format('Y-m-d H:i:sP')) * 1000];
        }

        return $dataPoints;
    }
}
