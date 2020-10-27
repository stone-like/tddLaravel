<?php


namespace App\testFolder;


class Statement
{

    public static function statement($invoice,$plays){
        $totalAmount = 0;
        $volumeCredits = 0;
        $result = "Statement for ${invoice["customer"]}\n";

        foreach($invoice["performances"] as $perf){
            $play = $plays[$perf["playID"]];

            $thisAmount = self::amoutFor($play, $perf);

            $volumeCredits += max($perf["audience"] - 30,0);

            if("comedy" === $play["type"]) $volumeCredits += floor($perf["audience"] /5);


            $result .= "${play["name"]}: ${thisAmount} (${perf["audience"]} seats)\n";
            $totalAmount += $thisAmount;

        };

        $result .= "Amount owed is ${totalAmount}\n";
        $result .= "You earned ${volumeCredits} credits\n";
        return $result;
    }

    /**
     * @param $play
     * @param $perf
     * @return float|int
     */
    public static function amoutFor($play, $perf)
    {
        switch ($play["type"]) {
            case "tragedy":
                $result = 40000;
                if ($perf > 30) {
                    $result += 1000 * ($perf["audience"] - 30);
                }
                break;
            case "comedy":
                $result = 30000;
                if ($perf["audience"] > 20) {
                    $result += 1000 + 500 * ($perf["audience"] - 20);
                }
                $result += 300 * $perf["audience"];
                break;
            default:
                throw new Exception("unknown type: ${
                     $play}");

        }
        return $result;
    }
}
