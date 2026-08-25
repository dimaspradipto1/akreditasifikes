<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_all_get_routes_render_successfully(): void
    {
        $routes = [
            'dashboard',
            'kriteria1.index',
            'kurikulum.index',
            'penilaian.index',
            'mahasiswa.index',
            'doenpkm.index',
            'sarpraskeuangan.index',
            'mutu.index',
            'tatakelola.index',
            'matriks.index',
            'tracker.index',
            'dokumen-bersama.index',
            'user.index',
            'settings.index',
            'laporan.index',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($this->user)->get(route($routeName));
            $this->assertEquals(200, $response->getStatusCode(), "Route {$routeName} failed with status " . $response->getStatusCode());
        }
    }

    public function test_export_pdf_and_excel_work(): void
    {
        $responsePdf = $this->actingAs($this->user)->get(route('laporan.export.pdf'));
        $this->assertEquals(200, $responsePdf->getStatusCode());

        $responseExcel = $this->actingAs($this->user)->get(route('laporan.export.excel'));
        $this->assertEquals(200, $responseExcel->getStatusCode());
    }
}
