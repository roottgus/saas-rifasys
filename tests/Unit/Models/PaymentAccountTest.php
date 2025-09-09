<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PaymentAccount;

class PaymentAccountTest extends TestCase
{
    public function test_can_process_bs_with_valid_rate(): void
    {
        $account = new PaymentAccount([
            'bs_enabled' => true,
            'tasa_bs'    => 36.50,
        ]);

        $this->assertTrue($account->canProcessBs());
    }

    public function test_cannot_process_bs_without_rate(): void
    {
        $account = new PaymentAccount([
            'bs_enabled' => true,
            'tasa_bs'    => null,
        ]);

        $this->assertFalse($account->canProcessBs());
    }

    public function test_converts_usd_to_bs_correctly(): void
    {
        $account = new PaymentAccount([
            'bs_enabled' => true,
            'tasa_bs'    => 36.50,
        ]);

        $result = $account->convertUsdToBs(100);

        $this->assertEquals(3650.00, $result);
    }
}
