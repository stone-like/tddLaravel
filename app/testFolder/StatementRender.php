<?php


namespace App\testFolder;


class StatementRender
{

    public $builder;

    /**
     * StatementRender constructor.
     */
    public function __construct(StatementBuilder $builder)
    {

        $this->builder = $builder;

    }

    public function statement()
    {

        return $this->renderPlainText($this->builder->createStatementData());
    }



    public function renderPlainText($data)
    {
        $result = "StatementRender for " . $data["customer"] . "\n";

        foreach ($data["performances"] as $perf) {

            $result .= $perf["play"]["name"] . ": " . $perf["amount"] . " (${perf["audience"]} seats)\n";


        };
        $result .= "Amount owed is " . $data["totalAmount"] . "\n";
        $result .= "You earned " . $data["totalVolumeCredits"] . " credits\n";
        return $result;
    }

    public function htmlstatement()
    {

        return $this->renderHtmlText($this->builder->createStatementData());
    }



    public function renderHtmlText($data)
    {
        $result = "<h1>StatementRender for " . $data["customer"] . "</h1>\n";

        foreach ($data["performances"] as $perf) {

            $result .= $perf["play"]["name"] . ": " . $perf["amount"] . " (${perf["audience"]} seats)\n";


        };
        $result .= "Amount owed is " . $data["totalAmount"] . "\n";
        $result .= "You earned " . $data["totalVolumeCredits"] . " credits\n";
        return $result;
    }


}
