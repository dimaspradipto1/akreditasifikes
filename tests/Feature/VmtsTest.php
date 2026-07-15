<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vmts;
use App\Models\VmtsBukti;
use App\Models\VmtsNarasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VmtsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $vmts;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'koordinatorprodi']);
        $this->vmts = Vmts::create(['user_id' => $this->user->id, 'tahun_akreditasi' => date('Y')]);
        
        // Seed EU-1
        VmtsNarasi::create([
            'vmts_id' => $this->vmts->id,
            'elemen_kode' => 'EU-1',
            'elemen_nama' => 'Mekanisme perumusan VMTS',
        ]);
    }

    public function test_index_page_can_be_accessed()
    {
        $response = $this->actingAs($this->user)->get(route('vmts.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.vmts.index');
    }

    public function test_can_update_narasi()
    {
        $narasi = VmtsNarasi::where('elemen_kode', 'EU-1')->first();
        
        $response = $this->actingAs($this->user)->put(route('vmts.narasi.update', $narasi->id), [
            'status' => 'Memenuhi',
            'narasi_persen' => 80,
            'bukti_persen' => 90,
            'kondisi_saat_ini' => 'Kondisi saat ini sangat baik',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vmts_narasis', [
            'id' => $narasi->id,
            'kondisi_saat_ini' => 'Kondisi saat ini sangat baik',
        ]);
    }

    public function test_can_store_bukti()
    {
        $response = $this->actingAs($this->user)->post(route('vmts.bukti.store'), [
            'vmts_id' => $this->vmts->id,
            'nama_bukti' => 'Dokumen VMTS Baru',
            'level' => 'PRODI',
            'status' => 'Tersedia',
            'link' => 'https://example.com/doc',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vmts_buktis', [
            'nama_bukti' => 'Dokumen VMTS Baru',
            'level' => 'PRODI',
        ]);
    }

    public function test_can_delete_bukti()
    {
        $bukti = VmtsBukti::create([
            'vmts_id' => $this->vmts->id,
            'nama_bukti' => 'To Be Deleted',
            'level' => 'PRODI',
            'status' => 'Tersedia',
        ]);

        $response = $this->actingAs($this->user)->delete(route('vmts.bukti.destroy', $bukti->id));
        $response->assertRedirect();
        
        $this->assertDatabaseMissing('vmts_buktis', [
            'id' => $bukti->id,
        ]);
    }
}
