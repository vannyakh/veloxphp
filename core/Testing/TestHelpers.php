<?php

namespace Core\Testing;

trait TestHelpers
{
    protected function assertDatabaseHas(string $table, array $data): void
    {
        $found = $this->app->db->table($table)
            ->where($data)
            ->exists();

        PHPUnit\Framework\Assert::assertTrue($found, sprintf(
            "Failed asserting that table [%s] contains %s",
            $table,
            json_encode($data)
        ));
    }

    protected function assertDatabaseMissing(string $table, array $data): void
    {
        $found = $this->app->db->table($table)
            ->where($data)
            ->exists();

        PHPUnit\Framework\Assert::assertFalse($found, sprintf(
            "Failed asserting that table [%s] does not contain %s",
            $table,
            json_encode($data)
        ));
    }
} 