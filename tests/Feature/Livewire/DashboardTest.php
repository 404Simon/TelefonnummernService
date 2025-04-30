<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Dashboard;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Dashboard::class)
            ->assertStatus(200);
    }

    public function test_phone_number_is_validated()
    {
        Livewire::test(Dashboard::class)
            ->set('phone', '+91 98 765 43210')
            ->assertSet('countryCode', '91')
            ->assertSet('mainNumber', '6543210')
            ->assertSet('areaCode', '987')
            ->assertSet('formattedPhone', '+91 987 65 43 21 0')
            ->assertSet('countryName', 'Indien')
            ->assertSet('countryFlag', '🇮🇳')
            ->assertHasNoErrors('phone');
    }
}
