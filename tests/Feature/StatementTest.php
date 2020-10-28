<?php

namespace Tests\Feature;

use App\testFolder\StatementRender;
use App\testFolder\StatementBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatementTest extends TestCase
{

    private $plays;
    private $statement;
    private $expected;
    private $htmlExpected;

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
        $this->expected = "StatementRender for bingo
Hamlet: 65000 (55 seats)
as you like it: 49000 (35 seats)
Othello: 50000 (40 seats)
Amount owed is 164000
You earned 47 credits
";
        $this->htmlExpected = "<h1>StatementRender for bingo</h1>
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

        $statement = new StatementRender(new StatementBuilder($this->invoice, $this->plays));
        $res = $statement->statement();
        $this->assertEquals($this->expected, $res);
    }

    /** @test */
    public function html_equals_expected()
    {

        $statement = new StatementRender(new StatementBuilder($this->invoice, $this->plays));
        $res = $statement->htmlstatement();
        $this->assertEquals($this->htmlExpected, $res);
    }

    /** @test */
    public function builder_emit_correct_dara()
    {

        $builder = new StatementBuilder($this->invoice, $this->plays);
        $expected = [
            'customer' =>
                "bingo",
            'performances' =>
                [
                    [
                        'playID' =>
                            "hamlet",
                        'audience' =>
                            55,
                        'play' =>
                            [
                                'name' =>
                                    "Hamlet",
                                'type' =>
                                    "tragedy"
                            ],
                        'amount' =>
                            65000,
                        'volumeCredits' =>
                            25
                    ],
                    [
                        'playID' =>
                            "asLike",
                        'audience' =>
                            35,
                        'play' =>
                            [
                                'name' =>
                                    "as you like it",
                                'type' =>
                                    "comedy",
                            ],
                        'amount' =>
                            49000,
                        'volumeCredits' =>
                            12
                    ],
                    [
                        'playID' =>
                            "othe",
                        'audience' =>
                            40,
                        'play' =>
                            [
                                'name' =>
                                    "Othello",
                                'type' =>
                                    "tragedy"
                            ],
                        'amount' =>
                            50000,
                        'volumeCredits' =>
                            10
                    ]
                ],
            'totalAmount' =>
                164000,
            'totalVolumeCredits' =>
                47
        ];

        $this->assertEquals($expected,$builder->createStatementData());


    }
}
