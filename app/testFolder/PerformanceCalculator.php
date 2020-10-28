<?php


namespace App\testFolder;


class PerformanceCalculator
{
    protected $performance;
    public $play;

    /**
     * PerformanceCalculator constructor.
     * @param $performance
     */
    public function __construct($performance, $play)
    {
        $this->performance = $performance;
        $this->play = $play;

    }

    /**
     * @param $perf
     * @return false|float|mixed
     */
    public function volumeCredits()
    {
        return max($this->performance["audience"] - 30, 0);


    }

    public function amount()
    {


        throw new Exception("look to SubClass");


    }


}
