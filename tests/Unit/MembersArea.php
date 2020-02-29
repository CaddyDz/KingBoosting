<?php

namespace Tests\Unit;

use Tests\TestCase;

class MembersArea extends TestCase
{
    /**
     * Assert we can load dark theme
     *
     * @return void
     */
    public function testDarkTheme()
    {
        $response = $this->get('/nova-api/styles/nova-dark-theme');
        $response->assertOk();
    }
}
