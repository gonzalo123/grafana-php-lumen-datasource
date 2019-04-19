<?php

namespace App\Http\Handlers;

use Illuminate\Http\Request;

class AnnotationHandler
{
    public function __invoke(Request $request)
    {
        $json       = $request->json();
        $annotation = $json->get('annotation');
        $range      = $json->get('range');

        return $this->getAnnotations($annotation, $range);
    }

    private function getAnnotations($annotation, $range)
    {
        return $this->getValues($range, 'PT' . $annotation['query'] . 'H');
    }


    private function getValues($range, $int)
    {
        $tz   = new \DateTimeZone('Europe/Madrid');
        $from = \DateTimeImmutable::createFromFormat("Y-m-d\TH:i:s.uP", $range['from'], $tz);
        $to   = \DateTimeImmutable::createFromFormat("Y-m-d\TH:i:s.uP", $range['to'], $tz);

        $annotation = [
            'name'       => $int,
            'enabled'    => true,
            'datasource' => "gonzalo datasource",
            'showLine'   => true,
        ];

        $interval = new \DateInterval($int);
        $period   = new \DatePeriod($from, $interval, $to->add($interval));

        $annotations = [];
        foreach ($period as $date) {
            $annotations[] = ['annotation' => $annotation, "title" => "H " . $date->format('H'), "time" => strtotime($date->format('Y-m-d H:i:sP')) * 1000, 'text' => "teeext"];
        }

        return $annotations;
    }
}
