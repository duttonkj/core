<?php

use Belt\Core\Http\Requests\StoreTeam;

class StoreTeamTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Belt\Core\Http\Requests\StoreTeam::rules
     */
    public function test()
    {

        $request = new StoreTeam();

        $this->assertNotEmpty($request->rules());
    }

}