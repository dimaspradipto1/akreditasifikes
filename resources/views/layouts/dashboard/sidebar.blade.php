    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="index.html">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse"
                    href="#" style="font-size: 14px">
                    <i class="bi bi-menu-button-wide"></i><span>8 KRITERIA — S1 Kesehatan</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('vmts.index') }}">
                            <i class="bi bi-circle"></i><span>1. Visi, Misi, Tujuan & Strategi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kurikulum.index') }}">
                            <i class="bi bi-circle"></i><span>2. Kurikulum</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('penilaian.index') }}">
                            <i class="bi bi-circle"></i><span>3. Penilaian</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mahasiswa.index') }}">
                            <i class="bi bi-circle"></i><span>4. Mahasiswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-buttons.html">
                            <i class="bi bi-circle"></i><span>5. Dosen, Tendik, Penelitian & PkM</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-cards.html">
                            <i class="bi bi-circle"></i><span>6. Sarana, Prasarana & Keuangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-carousel.html">
                            <i class="bi bi-circle"></i><span>7. Penjaminan Mutu</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-list-group.html">
                            <i class="bi bi-circle"></i><span>8. Tata Kelola & Administrasi</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('vmts.index') }}">
                    <i class="bi bi-file-earmark"></i>
                    <span>Matriks 26 Sub-Kriteria</span>
                </a>
            </li>

              <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('user.index') }}">
                    <i class="bi bi-file-earmark"></i>
                    <span>Dokumen Bersama</span>
                </a>
            </li>
              <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('user.index') }}">
                    <i class="bi bi-file-earmark"></i>
                    <span>Tracker Bukti</span>
                </a>
            </li>


            <li class="nav-heading">Pages</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('user.index') }}">
                    <i class="bi bi-file-earmark"></i>
                    <span>Manajemen User</span>
                </a>
            </li>

        </ul>

    </aside><!-- End Sidebar-->