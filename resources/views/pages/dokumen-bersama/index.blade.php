@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle mb-4">
    <nav>
        <ol class="breadcrumb" style="font-size: 0.85rem; margin-bottom: 0.5rem; background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">S1 Kesehatan Lingkungan</li>
            <li class="breadcrumb-item active">Dokumen Bersama</li>
        </ol>
    </nav>
    <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Dokumen Bersama</h1>
    <small class="text-muted">Diisi satu kali oleh Tim LPM/Rektorat & Dekanat/GPM FIKes — otomatis terbaca oleh halaman Kriteria S1 Kesehatan Lingkungan (dan 2 prodi lain di FIKes)</small>
</div>

<section class="section dashboard">
    <!-- Top Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Dokumen Universitas</div>
                    <div class="fs-4 fw-bold text-danger">{{ $univTersedia }} / 15 Tersedia</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Dokumen FIKes</div>
                    <div class="fs-4 fw-bold text-danger">{{ $fikesTersedia }} / 15 Tersedia</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Total Dokumen Bersama</div>
                    <div class="fs-3 fw-bold">{{ $totalDocs }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Dibaca Otomatis oleh</div>
                    <div class="fs-5 fw-bold text-dark mt-2">Semua Halaman Kriteria</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="alert bg-warning bg-opacity-10 border border-warning d-flex align-items-center mb-4" role="alert" style="border-radius: 10px; font-size: 0.85rem;">
        <div class="text-dark">
            <strong>Penting:</strong> isi kolom Status, Link, PIC, dan Deadline di halaman ini SATU KALI saja. Data ini otomatis terbaca oleh halaman Kriteria S1 Kesehatan Lingkungan (badge 
            <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success" style="font-size: 0.65rem; padding: 0.2rem 0.5rem;">FIKES</span> / 
            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary" style="font-size: 0.65rem; padding: 0.2rem 0.5rem;">UNIV</span>
            ) — jangan diisi ulang di halaman Kriteria.
        </div>
    </div>

    <!-- Table UNIV -->
    <div class="card border shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="mb-1" style="font-size: 1rem; font-weight: 700; color: #1e293b;">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary me-2" style="font-size: 0.7rem;">UNIV</span>
                Dokumen Bersama Universitas XYZ
            </h5>
            <small class="text-muted">Diisi oleh Tim LPM/Rektorat - {{ $univDocs->count() }} dokumen</small>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive table-container" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th scope="col" width="10%">KODE</th>
                            <th scope="col" width="22%">NAMA DOKUMEN</th>
                            <th scope="col" width="10%">JENIS</th>
                            <th scope="col" width="12%">STATUS</th>
                            <th scope="col" width="20%">LINK / LOKASI FILE</th>
                            <th scope="col" width="8%">PIC</th>
                            <th scope="col" width="13%">DEADLINE</th>
                            <th scope="col" width="5%" class="text-center">CATATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($univDocs as $doc)
                        <tr class="doc-row" data-name="{{ $doc->nama_bukti }}">
                            <td class="text-muted" style="font-size: 0.8rem; font-family: monospace;">-</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $doc->nama_bukti }}</div>
                                <div class="text-muted mt-1" style="font-size: 0.7rem; line-height: 1.3;">-</div>
                            </td>
                            <td class="text-muted" style="font-size: 0.8rem;">-</td>
                            <td>
                                <select class="form-select form-select-sm doc-status {{ $doc->status == 'Belum Ada' ? 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25' : 'bg-success bg-opacity-10 text-success border-success border-opacity-25' }}" style="font-size: 0.75rem; font-weight: 600; border-radius: 20px;">
                                    <option value="Belum Ada" {{ $doc->status == 'Belum Ada' ? 'selected' : '' }}>❌ Belum Ada</option>
                                    <option value="Tersedia" {{ $doc->status == 'Tersedia' ? 'selected' : '' }}>✅ Tersedia</option>
                                    <option value="Draft" {{ $doc->status == 'Draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="Revisi" {{ $doc->status == 'Revisi' ? 'selected' : '' }}>⚠️ Revisi</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm doc-link" value="{{ $doc->link }}" placeholder="tautan / lokasi file" style="font-size: 0.8rem; border-radius: 20px;">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm doc-pic text-center" value="{{ $doc->pic }}" placeholder="PIC" style="font-size: 0.8rem; border-radius: 20px;">
                            </td>
                            <td>
                                <input type="date" class="form-control form-control-sm doc-deadline" value="{{ $doc->deadline }}" style="font-size: 0.8rem; border-radius: 20px;">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-light rounded-circle border doc-catatan-btn" data-bs-toggle="tooltip" title="{{ $doc->catatan ?? 'Tambahkan catatan' }}">
                                    <i class="bi bi-chat-text"></i>
                                </button>
                                <input type="hidden" class="doc-catatan" value="{{ $doc->catatan }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Table FIKES -->
    <div class="card border shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="mb-1" style="font-size: 1rem; font-weight: 700; color: #1e293b;">
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success me-2" style="font-size: 0.7rem;">FIKES</span>
                Dokumen Bersama FIKes Universitas XYZ
            </h5>
            <small class="text-muted">Diisi oleh Tim Dekanat/GPM FIKes - {{ $fikesDocs->count() }} dokumen</small>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive table-container" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th scope="col" width="10%">KODE</th>
                            <th scope="col" width="22%">NAMA DOKUMEN</th>
                            <th scope="col" width="10%">JENIS</th>
                            <th scope="col" width="12%">STATUS</th>
                            <th scope="col" width="20%">LINK / LOKASI FILE</th>
                            <th scope="col" width="8%">PIC</th>
                            <th scope="col" width="13%">DEADLINE</th>
                            <th scope="col" width="5%" class="text-center">CATATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fikesDocs as $doc)
                        <tr class="doc-row" data-name="{{ $doc->nama_bukti }}">
                            <td class="text-muted" style="font-size: 0.8rem; font-family: monospace;">-</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $doc->nama_bukti }}</div>
                                <div class="text-muted mt-1" style="font-size: 0.7rem; line-height: 1.3;">-</div>
                            </td>
                            <td class="text-muted" style="font-size: 0.8rem;">-</td>
                            <td>
                                <select class="form-select form-select-sm doc-status {{ $doc->status == 'Belum Ada' ? 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25' : 'bg-success bg-opacity-10 text-success border-success border-opacity-25' }}" style="font-size: 0.75rem; font-weight: 600; border-radius: 20px;">
                                    <option value="Belum Ada" {{ $doc->status == 'Belum Ada' ? 'selected' : '' }}>❌ Belum Ada</option>
                                    <option value="Tersedia" {{ $doc->status == 'Tersedia' ? 'selected' : '' }}>✅ Tersedia</option>
                                    <option value="Draft" {{ $doc->status == 'Draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="Revisi" {{ $doc->status == 'Revisi' ? 'selected' : '' }}>⚠️ Revisi</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm doc-link" value="{{ $doc->link }}" placeholder="tautan / lokasi file" style="font-size: 0.8rem; border-radius: 20px;">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm doc-pic text-center" value="{{ $doc->pic }}" placeholder="PIC" style="font-size: 0.8rem; border-radius: 20px;">
                            </td>
                            <td>
                                <input type="date" class="form-control form-control-sm doc-deadline" value="{{ $doc->deadline }}" style="font-size: 0.8rem; border-radius: 20px;">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-light rounded-circle border doc-catatan-btn" data-bs-toggle="tooltip" title="{{ $doc->catatan ?? 'Tambahkan catatan' }}">
                                    <i class="bi bi-chat-text"></i>
                                </button>
                                <input type="hidden" class="doc-catatan" value="{{ $doc->catatan }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Legend Bottom -->
    <div class="mt-4 border-top d-flex flex-wrap align-items-center gap-3 pt-3" style="font-size: 0.8rem;">
        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary">UNIV</span>
        <span class="text-muted">level Universitas — berlaku untuk semua prodi di Universitas XYZ</span>
        
        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 border border-success ms-md-3">FIKES</span>
        <span class="text-muted">level Fakultas — berlaku untuk semua prodi di FIKes</span>
    </div>
</section>

<!-- Catatan Modal -->
<div class="modal fade" id="catatanModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Catatan Dokumen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="modalCatatanInput" class="form-control" rows="4" placeholder="Tuliskan catatan terkait dokumen ini..."></textarea>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary rounded-pill" id="saveCatatanBtn">Simpan Catatan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        let activeRow = null;
        const catatanModal = new bootstrap.Modal(document.getElementById('catatanModal'));
        const modalCatatanInput = document.getElementById('modalCatatanInput');
        const saveCatatanBtn = document.getElementById('saveCatatanBtn');

        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Event listener for Status change
        $('.doc-status').on('change', function() {
            const val = $(this).val();
            if (val === 'Belum Ada') {
                $(this).removeClass('bg-success text-success border-success').addClass('bg-danger text-danger border-danger');
            } else {
                $(this).removeClass('bg-danger text-danger border-danger').addClass('bg-success text-success border-success');
            }
            saveRowData($(this).closest('.doc-row'));
        });

        // Event listener for input blur (Link, PIC, Deadline)
        $('.doc-link, .doc-pic, .doc-deadline').on('blur', function() {
            saveRowData($(this).closest('.doc-row'));
        });

        // Event listener for Catatan Modal
        $('.doc-catatan-btn').on('click', function() {
            activeRow = $(this).closest('.doc-row');
            const cat = activeRow.find('.doc-catatan').val();
            modalCatatanInput.value = cat;
            catatanModal.show();
        });

        saveCatatanBtn.addEventListener('click', function() {
            if (activeRow) {
                activeRow.find('.doc-catatan').val(modalCatatanInput.value);
                const btn = activeRow.find('.doc-catatan-btn');
                btn.attr('data-bs-original-title', modalCatatanInput.value || 'Tambahkan catatan');
                
                if(modalCatatanInput.value) {
                    btn.removeClass('btn-light').addClass('btn-primary text-white');
                } else {
                    btn.removeClass('btn-primary text-white').addClass('btn-light');
                }
                
                saveRowData(activeRow);
                catatanModal.hide();
            }
        });

        function saveRowData(row) {
            const name = row.data('name');
            const status = row.find('.doc-status').val();
            const link = row.find('.doc-link').val();
            const pic = row.find('.doc-pic').val();
            const deadline = row.find('.doc-deadline').val();
            const catatan = row.find('.doc-catatan').val();

            $.ajax({
                url: `/dokumen-bersama/update`,
                type: 'PUT',
                data: {
                    nama_dokumen: name,
                    status: status,
                    link: link,
                    pic: pic,
                    deadline: deadline,
                    catatan: catatan
                },
                success: function(response) {
                    console.log('Saved', name);
                    // Optional: show small toast notification
                },
                error: function(xhr) {
                    console.error('Error saving', name, xhr);
                }
            });
        }
        
        // Initialize active state for catatans
        $('.doc-catatan').each(function() {
            if($(this).val()) {
                $(this).siblings('.doc-catatan-btn').removeClass('btn-light').addClass('btn-primary text-white');
            }
        });
    });
</script>
<style>
    /* Scrollable table */
    .table-container {
        max-height: 500px;
        overflow-y: auto;
    }
    .table-container thead th {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 10;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .table-container::-webkit-scrollbar { width: 8px; }
    .table-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .table-container::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .table-container::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
</style>
@endpush
