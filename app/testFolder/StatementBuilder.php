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
        $result = $performance;
        $result["play"] = $this->playFor($result);
        $result["amount"] = $this->amoutFor($result);
        $result["volumeCredits"] = $this->volumeCreditsFor($result);
        return $result;
    }


    /**
     * @param $play
     * @param $perf
     * @return float|int
     */
    public function amoutFor($performance)
    {

        switch ($performance["play"]["type"]) {
            case "tragedy":
                $result = 40000;
                if ($performance["audience"] > 30) {
                    $result += 1000 * ($performance["audience"] - 30);
                }
                break;
            case "comedy":
                $result = 30000;
                if ($performance["audience"] > 20) {
                    $result += 1000 + 500 * ($performance["audience"] - 20);
                }
                $result += 300 * $performance["audience"];
                break;
            default:
                throw new Exception("unknown type: ${
                     $performance}");

        }
        return $result;
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
     * @param $perf
     * @param $volumeCredits
     * @param $plays
     * @return false|float|mixed
     */
    public function volumeCreditsFor($performance)
    {
        $result = 0;
        $result += max($performance["audience"] - 30, 0);

        if ("comedy" === $performance["play"]["type"]) $result += floor($performance["audience"] / 5);
        return $result;
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
