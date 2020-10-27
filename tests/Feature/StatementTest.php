<?php

namespace Tests\Feature;

use App\testFolder\Statement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatementTest extends TestCase
{

    private $plays;
    private $statement;
    private $expected;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoice =
            [
                "customer" => "bingo",
                "performances" => [
                    [
                        "playID" => "hamlet",
                        "audience" => 55
                    ],
                    [
                        "playID" => "asLike",
                        "audience" => 35
                    ],
                    [
                        "playID" => "othe",
                        "audience" => 40
                    ]

                ]

            ];
        $this->plays =
            ["hamlet" => [
                "name" => "Hamlet",
                "type" => "tragedy"
            ],
                "asLike" => [
                    "name" => "as you like it",
                    "type" => "comedy"
                ],
                "othe" => [
                    "name" => "Othello",
                    "type" => "tragedy"
                ]
            ];
        $this->expected = "Statement for bingo
Hamlet: 65000 (55 seats)
as you like it: 49000 (35 seats)
Othello: 50000 (40 seats)
Amount owed is 164000
You earned 47 credits
";

    }

    /** @test */
    public function it_equals_expected()
    {

        $statement = new Statement($this->invoice, $this->plays);
        $res = $statement->statement();
        $this->assertEquals($this->expected,$res);
    }
}
