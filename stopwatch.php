<?php


class stopwatch
{
    private $start_time, $end_time, $laps = [];

    public function __construct()
    {
        $this->start_time = 0;
    }

    function start()
    {
        $this->start_time = microtime(true);
    }

    function end()
    {
        $this->end_time = microtime(true);
    }

    function lap($name)
    {
        $lap = microtime(true);
        $this->laps[$name] = $lap - $this->start_time;
    }

    function total($precision = 5)
    {
        $this->laps['total'] = $this->end_time - $this->start_time;
        $laps = $this->laps;
        array_walk($laps, function ($value, $key) use (&$laps, $precision) {
            $value = round($value, $precision);
            $laps[$key] = "\t{$key}: {$value}s";
        });
        return implode("\n", $laps);
    }

}