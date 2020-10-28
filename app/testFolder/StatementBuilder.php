<?php


namespace App\testFolder;


class StatementBuilder
{
    public $invoice;
    public $plays;

    /**
     * StatementRender constructor.
     */
    public function __construct($invoice, $plays)
    {
        $this->invoice = $invoice;
        $this->plays = $plays;
    }


    public function createStatementData(){
        $statementData = [];
        $statementData["customer"] = $this->invoice["customer"];

        $statementData["performances"] = array_map(function ($performance) {
            return $this->enrichPerformance($performance);
        }, $this->invoice["performances"]);
        $statementData["totalAmount"] = $this->totalAmount($statementData);
        $statementData["totalVolumeCredits"] = $this->totalVolumeCredits($statementData);


        return $statementData;
    }

    public function enrichPerformance($performance)
    {
        $calcurator = $this->createPerformanceCalculator($performance,$this->playFor($performance));
        $result = $performance;
        $result["play"] = $calcurator->play;
        $result["amount"] = $calcurator->amount();
        $result["volumeCredits"] = $calcurator->volumeCredits();
        return $result;
    }

    public function createPerformanceCalculator($performance,$play){
        switch($play["type"]){
            case "tragedy": return new TragedyCalculator($performance,$play);
            case "comedy": return new ComedyCalculator($performance,$play);
            default:
                throw new \Exception("unknown performance: ${play["performance"]}");
        }
    }


    /**
     * @param $plays
     * @param $perf
     * @return mixed
     */
    public function playFor($perf)
    {

        return $this->plays[$perf["playID"]];
    }



    /**
     * @param $invoice
     * @param $plays
     * @return false|float|int|mixed
     */
    public function totalVolumeCredits($data)
    {
        $volumeCredits = 0;
        foreach ($data["performances"] as $perf) {


            $volumeCredits += $perf["volumeCredits"];

        };
        return $volumeCredits;
    }

    /**
     * @param $invoice
     * @param $plays
     * @return int
     */
    public function totalAmount($data): int
    {

        $result = 0;
        foreach ($data["performances"] as $perf) {

            $result += $perf["amount"];

        };
        return $result;
    }
}
