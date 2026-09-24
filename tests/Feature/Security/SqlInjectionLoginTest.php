<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SqlInjectionLoginTest extends TestCase
{
    public function test_intento_inyeccion_sql_en_login_es_neutralizado()
    {
        $payloads = [
            "' OR '1'='1",
            "admin' --",
            "' UNION SELECT null, null, null--",
            "admin' #",
            "' OR 1=1#",
            "'; DROP TABLE usuarios; --",
        ];

        foreach ($payloads as $payload) {
            $response = $this->post('/login', [
                'username' => $payload,
                'password' => 'password_falsa_123',
            ]);

            // Debe rechazar con error de validación, nunca con error 500 (SQL Crash) ni login exitoso (302 a dashboard)
            $response->assertSessionHasErrors('username');
            $this->assertGuest();
        }
    }
}