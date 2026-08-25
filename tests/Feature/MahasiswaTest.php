<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\MahasiswaNarasi;
use App\Models\MahasiswaBukti;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'koordinatorprodi']);
        $this->mahasiswa = Mahasiswa::create(['user_id' => $this->user->id, 'tahun_akreditasi' => date('Y')]);
    }

    public function test_mahasiswa_index_accessible_and_renders_correctly(): void
    {
        $response = $this->actingAs($this->user)->get(route('mahasiswa.index'));

        $response->assertStatus(200);
        $response->assertSee('Kriteria 4 — Mahasiswa');
        $response->assertSee('4.1');
        $response->assertSee('4.2');
        $response->assertSee('4.3');
        $response->assertSee('4.4');
    }

    public function test_mahasiswa_narasi_and_status_simulation_is_dynamic(): void
    {
        // Initial load creates standard records
        $this->actingAs($this->user)->get(route('mahasiswa.index'));

        // Fill all 4.1 EUs with status Lengkap
        $eus = MahasiswaNarasi::where('mahasiswa_id', $this->mahasiswa->id)
            ->where('kriteria_kode', 'LIKE', '4.1_EU%')
            ->get();

        foreach ($eus as $eu) {
            $eu->update(['status' => 'Lengkap', 'narasi_persen' => 100]);
        }

        // Request index to trigger auto-recalculate
        $response = $this->actingAs($this->user)->get(route('mahasiswa.index'));
        $response->assertStatus(200);

        // Parent 4.1 must be updated to 100% and Memenuhi
        $parent = MahasiswaNarasi::where('mahasiswa_id', $this->mahasiswa->id)
            ->where('kriteria_kode', '4.1')
            ->first();

        $this->assertEquals(100, $parent->narasi_persen);
        $this->assertEquals('Memenuhi', $parent->status);
    }
}
