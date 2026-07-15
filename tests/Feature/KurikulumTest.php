<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kurikulum;
use App\Models\KurikulumBukti;
use App\Models\KurikulumNarasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KurikulumTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kurikulum;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'koordinatorprodi']);
        $this->kurikulum = Kurikulum::create(['user_id' => $this->user->id, 'tahun_akreditasi' => date('Y')]);
        
        // Seed 2.1_EU-1
        KurikulumNarasi::create([
            'kurikulum_id' => $this->kurikulum->id,
            'kriteria_kode' => '2.1_EU-1',
            'kriteria_nama' => 'Penyusunan CPL',
        ]);
    }

    public function test_index_page_can_be_accessed()
    {
        $response = $this->actingAs($this->user)->get(route('kurikulum.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.kurikulum.index');
    }

    public function test_can_update_narasi()
    {
        $narasi = KurikulumNarasi::where('kriteria_kode', '2.1_EU-1')->first();
        
        $response = $this->actingAs($this->user)->put(route('kurikulum.narasi.update', $narasi->id), [
            'status' => 'Memenuhi',
            'narasi_persen' => 80,
            'bukti_persen' => 90,
            'kondisi_saat_ini' => 'Kondisi CPL sudah disusun',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kurikulum_narasis', [
            'id' => $narasi->id,
            'kondisi_saat_ini' => 'Kondisi CPL sudah disusun',
        ]);
    }

    public function test_can_store_bukti()
    {
        $response = $this->actingAs($this->user)->post(route('kurikulum.bukti.store'), [
            'kurikulum_id' => $this->kurikulum->id,
            'nama_bukti' => 'Dokumen RPS',
            'level' => 'PRODI',
            'status' => 'Tersedia',
            'link' => 'https://example.com/rps',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kurikulum_buktis', [
            'nama_bukti' => 'Dokumen RPS',
            'level' => 'PRODI',
        ]);
    }

    public function test_can_delete_bukti()
    {
        $bukti = KurikulumBukti::create([
            'kurikulum_id' => $this->kurikulum->id,
            'nama_bukti' => 'To Be Deleted',
            'level' => 'PRODI',
            'status' => 'Tersedia',
        ]);

        $response = $this->actingAs($this->user)->delete(route('kurikulum.bukti.destroy', $bukti->id));
        $response->assertRedirect();
        
        $this->assertDatabaseMissing('kurikulum_buktis', [
            'id' => $bukti->id,
        ]);
    }
}
