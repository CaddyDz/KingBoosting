<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    /**
     * test basic page.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * test admin page.
     *
     * @return void
     */
    public function testAdminPage()
    {
        $response = $this->get(config('nova.path'));

        $response->assertStatus(302);
    }
}
