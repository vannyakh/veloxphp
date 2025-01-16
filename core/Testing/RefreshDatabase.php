<?php

namespace Core\Testing;

trait RefreshDatabase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('migrate:fresh');
        
        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }
} 