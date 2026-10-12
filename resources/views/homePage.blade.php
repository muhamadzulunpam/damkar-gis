<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="author" content="Damkar GIS">
   <meta name="description" content="Damkar GIS - Sistem Informasi Pemetaan Kerawanan Kebakaran & Penyelamatan Kota Tangerang Selatan">
   <link rel="icon" type="image/png" href="{{ asset('img/logo_damkar.png') }}">
   <title>Damkar GIS - Pemetaan Kerawanan Kebakaran & Penyelamatan Kota Tangerang Selatan</title>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
   <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
   <link href="{{ asset('css/aos.css') }}" rel="stylesheet"/>
   <link href="{{ asset('css/swiper-bundle.min.css') }}" rel="stylesheet"/>
   <link rel="stylesheet" href="{{ asset('css/all.min.css') }}"/>
   <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}"/>
   <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
   <style>
      /* Kartu filter & ringkasan peta (terpisah dari .fcard milik hero) */
      .mapcard{background:#fff;border-radius:16px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,.08)}
      .leaflet-container{font-family:inherit}
      .wa-float{
      position:fixed;
      right:24px;
      bottom:28px;
      width:47px;
      height:47px;
      border-radius:50%;
      background:#25d366;
      color:#fff;
      font-size:1.9rem;
      display:flex;
      align-items:center;
      justify-content:center;
      text-decoration:none;
      box-shadow:0 4px 14px rgba(0,0,0,.25);
      z-index:1000;
      transition:transform .2s, box-shadow .2s;
   }
   .wa-float:hover{
      color:#fff;
      transform:scale(1.08);
      box-shadow:0 6px 18px rgba(0,0,0,.3);
   }
   .wa-float::after{
      content:'';
      position:absolute;
      inset:0;
      border-radius:50%;
      border:2px solid #25d366;
      animation:waPulse 2s infinite;
   }
   @keyframes waPulse{
      0%{transform:scale(1);opacity:.7}
      100%{transform:scale(1.6);opacity:0}
   }
   </style>
</head>
<body>

   {{-- TOP BAR --}}
   <div id="topbar">
      <div class="container">
         <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="top-contact d-flex flex-wrap">
               <span><i class="fas fa-phone-alt"></i>+62 (21) 1234-5678</span>
               <span><i class="fas fa-envelope"></i>damkar@tangerangselatan.go.id</span>
               <span><i class="fas fa-map-marker-alt"></i>Kota Tangerang Selatan</span>
            </div>
            <div class="d-flex align-items-center gap-3">
               <span class="ttag"><i class="fas fa-fire me-1"></i>Siaga 24 Jam!</span>
               <div class="tsoc">
                  <a href="https://www.facebook.com/DamkarTangsel" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                  <a href="https://www.instagram.com/damkartangselofficial/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                  <a href="https://www.tiktok.com/@damkartangselofficial?_r=1&_t=ZS-9A33ymZzIWu" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                  <a href="https://youtube.com/@damkartangselofficial?si=7Z25TFC8n7cWxp_G" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
               </div>
            </div>
         </div>
      </div>
   </div>

   {{-- NAVBAR --}}
   <nav class="navbar navbar-expand-lg" id="nav">
      <div class="container">
         <a class="navbar-brand" href="#hero">
            <div class="blogo">
               <img class="logo-img" src="{{ asset('img/logo_damkar.png') }}" alt="Logo Damkar GIS">
               <div>
                  <div class="bname">Damkar <span>GIS</span></div>
                  <div class="bsub">Kota Tangerang Selatan</div>
               </div>
            </div>
         </a>
         <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <i class="fas fa-bars" style="color:var(--primary);font-size:1.35rem;"></i>
         </button>
         <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav mx-auto">
               <li class="nav-item"><a class="nav-link active" href="#hero">Beranda</a></li>
               <li class="nav-item"><a class="nav-link" href="#maps">Peta Kerawanan</a></li>
               <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
               <li class="nav-item"><a class="nav-link" href="#informasi">Informasi</a></li>
               <li class="nav-item"><a class="nav-link" href="#contact-section">Kontak</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
               @auth
                  <a href="{{ route('dashboard') }}" class="nav-link nav-cta">
                     <i class="fas fa-user me-1"></i>Dashboard
                  </a>
               @else
                  <a href="{{ route('login') }}" class="btn-outline-red" style="padding:8px 18px;border:2px solid var(--primary);color:var(--primary);border-radius:50px;text-decoration:none;font-weight:600;font-size:0.85rem;transition:0.3s;">
                     <i class="fas fa-sign-in-alt me-1"></i>Login
                  </a>
               @endauth
            </div>
         </div>
      </div>
   </nav>

   {{-- HERO --}}
   <section id="hero">
      <div class="hs hs1"></div>
      <div class="hs hs2"></div>
      <div class="hbgtxt">DAMKAR</div>
      <div class="container">
         <div class="row align-items-center g-5" style="min-height:88vh;">
            <div class="col-lg-6">
               <div class="hbadge">
                  <div class="hbi"><i class="fas fa-fire-extinguisher"></i></div>
                  <span>Sistem Informasi Pemetaan Kerawanan</span>
               </div>
               <h1 class="htitle">Pantang Pulang <span class="hl">Sebelum Padam</span><br/>Siap Sedia Setiap Saat</h1>
               <p class="hdesc">Layanan penyelamatan dan penanggulangan kebakaran cepat tanggap demi keselamatan warga. Kami hadir 24 jam dengan personel profesional yang terlatih dan responsif.</p>
               <div class="d-flex flex-wrap gap-3 mb-2">
                  <a href="#maps" class="btn-red"><i class="fas fa-map-marked-alt"></i>Lihat Peta</a>
                  <a href="https://www.tiktok.com/@damkartangselofficial?_r=1&_t=ZS-9A33ymZzIWu" target="_blank" class="magnific_popup btn-play popup-youtube">
                     <div class="pico"><i class="fas fa-play"></i></div>
                     <span>Tonton Kisah Kami</span>
                  </a>
               </div>
               <div class="hstats d-flex gap-3 flex-wrap mt-4">
                  <div class="hstat">
                     <span class="snum" data-count="150">100<em>+</em></span>
                     <small>Kejadian Ditangani</small>
                  </div>
                  <div class="sdiv"></div>
                  <div class="hstat"><span class="snum">54</span><small>Kelurahan</small></div>
                  <div class="sdiv"></div>
                  <div class="hstat"><span class="snum">7</span><small>Kecamatan</small></div>
                  <div class="sdiv"></div>
                  <div class="hstat"><span class="snum">24<em>h</em></span><small>Siaga Darurat</small></div>
               </div>
            </div>
            <div class="col-lg-6">
               <div style="position:relative;text-align:center;">
                  <div class="hcircle">
                     <img src="{{ asset('img/damkar.jpg') }}" alt="Petugas Damkar Tangsel"/>
                  </div>
                  <div class="fcard fc1">
                     <div class="fcoi r"><i class="fas fa-phone-alt"></i></div>
                     <div><span class="fcnum">Call Center</span><span class="fcsm">0811 9000 74</span></div>
                  </div>
                  <div class="fcard fc2">
                     <div class="fcoi y"><i class="fas fa-clock"></i></div>
                     <div><span class="fcnum">24 Jam</span><span class="fcsm">Siaga Darurat</span></div>
                  </div>
                  <div class="fcard fc3">
                     <div class="fcoi g"><i class="fas fa-map"></i></div>
                     <div><span class="fcnum">Web GIS</span><span class="fcsm">Pemetaan Interaktif</span></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   {{-- MARQUEE --}}
   <div class="mqsec">
      <div class="mqtrack">
         @for($i = 0; $i < 2; $i++)
            <div class="mqitem"><i class="fas fa-fire-extinguisher"></i>Pemadaman Kebakaran</div>
            <div class="mqitem"><i class="fas fa-ambulance"></i>Penyelamatan &amp; Evakuasi</div>
            <div class="mqitem"><i class="fas fa-shield-alt"></i>Pencegahan Dini Kebakaran</div>
            <div class="mqitem"><i class="fas fa-paw"></i>Evakuasi Hewan Liar (Rescue)</div>
            <div class="mqitem"><i class="fas fa-hard-hat"></i>Edukasi &amp; Sosialisasi</div>
            <div class="mqitem"><i class="fas fa-clock"></i>Layanan Darurat 24 Jam</div>
            <div class="mqitem"><i class="fas fa-truck-moving"></i>Armada Cepat Tanggap</div>
         @endfor
      </div>
   </div>

   {{-- PETA KERAWANAN --}}
   <section id="maps">
      <div class="container">
         <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Peta Kerawanan</span>
            <h2 class="stitle">Pantau Wilayah <span>Berdasarkan Risiko</span></h2>
            <div class="sline"></div>
            <p class="sdesc mx-auto" style="max-width:480px;">
               Lihat tingkat kerawanan kebakaran dan penyelamatan di setiap kelurahan
               di Kota Tangerang Selatan.
            </p>
         </div>

         <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-right">
               <div style="position:relative;">
                  <div id="map" style="height:520px;width:100%;border-radius:16px;overflow:hidden;z-index:1;"></div>
                  <div id="mapLoading" style="position:absolute;inset:0;z-index:500;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.7);border-radius:16px;">
                     <span class="text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Memuat peta...</span>
                  </div>
               </div>
            </div>

            <div class="col-lg-4" data-aos="fade-left">
               <div class="mapcard mb-3">
                  <div class="row g-3">
                     <div class="col-12">
                        <label class="flbl" for="filterJenis">Jenis Kejadian</label>
                        <select id="filterJenis" class="fctrl">
                           @forelse($jenisKejadianList as $jenis)
                              <option value="{{ $jenis }}"
                                 @selected($jenis === $jenisDefault)>
                                 {{ ucfirst($jenis) }}
                              </option>
                           @empty
                              <option value="">Belum ada data</option>
                           @endforelse
                        </select>
                     </div>
                     <div class="col-12">
                        <label class="flbl" for="filterPeriode">Periode</label>
                        <select id="filterPeriode" class="fctrl">
                           {{-- diisi otomatis oleh JavaScript --}}
                        </select>
                     </div>
                  </div>
               </div>

               <div class="mapcard">
                  <div class="flbl mb-2">Ringkasan Wilayah</div>
                  <div class="d-flex align-items-center mb-2">
                     <span style="width:14px;height:14px;background:#22c55e;border-radius:50%;" class="me-2"></span>
                     Rendah <strong class="ms-auto"><span id="stRendah">0</span> kelurahan</strong>
                  </div>
                  <div class="d-flex align-items-center mb-2">
                     <span style="width:14px;height:14px;background:#eab308;border-radius:50%;" class="me-2"></span>
                     Sedang <strong class="ms-auto"><span id="stSedang">0</span> kelurahan</strong>
                  </div>
                  <div class="d-flex align-items-center">
                     <span style="width:14px;height:14px;background:#ef4444;border-radius:50%;" class="me-2"></span>
                     Tinggi <strong class="ms-auto"><span id="stTinggi">0</span> kelurahan</strong>
                  </div>
                  <div id="mapStatus" class="small text-muted mt-3"></div>
               </div>
            </div>
         </div>
      </div>
   </section>

   {{-- ABOUT --}}
   <section id="about">
      <div class="container">
         <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
               <div class="astack">
                  <div class="aexp"><span class="anum">7</span><small>Posko <br> Strategis</small></div>
                  <div class="amain"><img src="{{ asset('img/damkar1.jpg') }}" alt="Dinas Damkar Tangsel"/></div>
                  <div class="asm"><img src="{{ asset('img/damkar2.jpg') }}" alt="Petugas Rescue Tangsel"/></div>
               </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
               <span class="slbl">Tentang Kami</span>
               <h2 class="stitle text-start">Yudha Brama Jaya<br/>Dinas Damkar &amp; <span>Penyelamatan Tangsel</span></h2>
               <div class="sline lft"></div>
               <p class="sdesc mb-4">Dinas Pemadam Kebakaran dan Penyelamatan Kota Tangerang Selatan berkomitmen penuh dalam melindungi warga dari bahaya kebakaran dan situasi darurat. Melalui pilar pencegahan, pemadaman, dan penyelamatan non-kebakaran (rescue), kami siap sedia melayani 7 kecamatan di wilayah Tangsel selama 24 jam penuh.</p>
               <div class="mb-4">
                  <div class="fti">
                     <div class="ftico r"><i class="fas fa-bolt"></i></div>
                     <div>
                        <h6>Respon Cepat Tanggap 24/7</h6>
                        <p>Kesiapsiagaan penuh dari seluruh personel di setiap posko darurat demi mencapai response time yang optimal bagi keselamatan warga.</p>
                     </div>
                  </div>
                  <div class="fti">
                     <div class="ftico y"><i class="fas fa-building"></i></div>
                     <div>
                        <h6>Armada Khusus Vertikal (Bronto Skylift)</h6>
                        <p>Dilengkapi unit Bronto Skylift untuk mengantisipasi dan menangani insiden darurat pada bangunan vertikal dan gedung tinggi di wilayah perkotaan Tangsel.</p>
                     </div>
                  </div>
                  <div class="fti">
                     <div class="ftico g"><i class="fas fa-users"></i></div>
                     <div>
                        <h6>Pemberdayaan Relawan Kebakaran (Redkar)</h6>
                        <p>Membentuk dan membina jaringan Relawan Pemadam Kebakaran (Redkar) di tingkat kelurahan untuk mempercepat penanganan dini kebakaran.</p>
                     </div>
                  </div>
               </div>
               <a href="https://wa.me/62811900074?text=Halo%20Damkar%20Tangsel%2C%20saya%20ingin%20melaporkan%20kejadian%20darurat"
                  target="_blank" rel="noopener" class="btn-red">
                  <i class="fas fa-phone-alt"></i> Hubungi Call Center
               </a>
            </div>
         </div>
      </div>
   </section>

   {{-- INFORMASI & KESELAMATAN --}}
   <section id="informasi">
      <div class="container">
         <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Edukasi</span>
            <h2 class="stitle">Informasi &amp; <span>Keselamatan</span></h2>
            <p class="sdesc mx-auto" style="max-width:480px;">Panduan lengkap menghadapi keadaan darurat kebakaran, mulai dari pencegahan, penanganan, hingga evakuasi.</p>
            <div class="sline"></div>
         </div>

         <div class="text-center mb-4" data-aos="fade-up" id="edukasiTabs">
            <button type="button" class="filtbtn active" data-f="all"><i class="fas fa-th-large me-1"></i> Semua</button>
            <button type="button" class="filtbtn" data-f="api"><i class="fas fa-fire me-1"></i> Api</button>
            <button type="button" class="filtbtn" data-f="apar"><i class="fas fa-fire-extinguisher me-1"></i> APAR</button>
            <button type="button" class="filtbtn" data-f="evakuasi"><i class="fas fa-people-arrows me-1"></i> Evakuasi</button>
            <button type="button" class="filtbtn" data-f="hewan"><i class="fas fa-paw me-1"></i> Hewan</button>
         </div>

         <div class="row g-4 justify-content-center" id="mgrid">
            @foreach($edukasi as $e)
               <div class="col-sm-6 col-lg-4 mwrap" data-c="{{ $e['kategori'] }}">
                  <div class="mcard">
                     <div class="mimg">
                        <img src="{{ asset($e['gambar']) }}" alt="{{ $e['judul'] }}"
                           onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect fill=%22%23dc3545%22 width=%22300%22 height=%22200%22/%3E%3C/svg%3E'">
                        @if($e['penting'])
                           <div class="mbdg hot"><i class="fas fa-star"></i> Penting</div>
                        @endif
                     </div>
                     <div class="mbody">
                        <div class="mcat"><i class="fas {{ $e['ikon'] }} me-1"></i> {{ $e['label'] }}</div>
                        <div class="mtit">{{ $e['judul'] }}</div>
                        <div class="mdesc">{{ $e['deskripsi'] }}</div>
                        <div class="mfoot">
                           <div>
                              <div class="mprice">
                                 <i class="fas fa-clock me-1" style="font-size:0.7rem;color:#6c757d;"></i>
                                 <span style="font-size:0.8rem;color:#6c757d;">{{ $e['menit'] }} menit baca</span>
                              </div>
                              <div class="mstars">
                                 <i class="fas fa-check-circle" style="color:#28a745;"></i>
                                 <span style="color:#999;font-size:.7rem;">Panduan Resmi</span>
                              </div>
                           </div>
                           <button type="button" class="madd" title="Lihat Detail"
                                 data-bs-toggle="modal" data-bs-target="#infoModal{{ $e['id'] }}">
                              <i class="fas fa-eye"></i>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </section>

   {{-- MODAL DETAIL (satu per item) --}}
   @foreach($edukasi as $e)
      <div class="modal fade" id="infoModal{{ $e['id'] }}" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:16px;">
               <div class="modal-header border-0">
                  <div>
                     <div class="small fw-semibold" style="color:var(--primary)">
                        <i class="fas {{ $e['ikon'] }} me-1"></i>{{ $e['label'] }}
                     </div>
                     <h5 class="modal-title fw-bold">{{ $e['judul'] }}</h5>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
               </div>
               <div class="modal-body">
                  <p class="text-muted">{{ $e['deskripsi'] }}</p>
                  <ol class="mb-0">
                     @foreach($e['langkah'] as $langkah)
                        <li class="mb-2">{{ $langkah }}</li>
                     @endforeach
                  </ol>
               </div>
               <div class="modal-footer border-0">
                  <a href="https://wa.me/62811900074" target="_blank" rel="noopener" class="btn-red">
                     <i class="fas fa-phone-alt"></i> Hubungi Damkar
                  </a>
               </div>
            </div>
         </div>
      </div>
   @endforeach

   {{-- MODAL DETAIL INFORMASI --}}
   <div class="modal fade" id="infoModal1" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0">
               <h5 class="modal-title fw-bold">Cara Memadamkan Api</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
               <p>Kenali jenis kebakaran dan cara penanganan yang aman serta tepat.</p>
               <ol>
                  <li>Tetap tenang dan segera beri tahu orang di sekitar.</li>
                  <li>Matikan sumber listrik atau gas bila aman dilakukan.</li>
                  <li>Gunakan APAR dengan prinsip PASS: Pull, Aim, Squeeze, Sweep.</li>
                  <li>Jika api membesar, segera evakuasi dan hubungi Damkar 0811 9000 74 atau 112.</li>
               </ol>
            </div>
         </div>
      </div>
   </div>

   {{-- FOOTER / KONTAK --}}
   <footer id="contact-section">
      <div class="container">
         <div class="row g-5">
            <div class="col-lg-5">
               <div class="bname">DAMKAR <span>GIS</span></div>
               <p class="fdesc">Dinas Pemadam Kebakaran dan Penyelamatan Kota Tangerang Selatan selalu siap sedia 24 jam melindungi masyarakat melalui aksi cepat tanggap pemadaman dan penyelamatan darurat.</p>
               <div class="fsoc">
                  <a href="https://www.facebook.com/DamkarTangsel" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                  <a href="https://www.instagram.com/damkartangselofficial/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                  <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                  <a href="https://www.youtube.com/@damkartangselofficial" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                  <a href="https://www.tiktok.com/@damkartangselofficial" target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
               </div>
            </div>

            <div class="col-sm-6 col-lg-3">
               <div class="ftit">Tautan Cepat</div>
               <ul class="flinks ps-0">
                  <li><a href="#hero"><i class="fas fa-chevron-right"></i>Beranda</a></li>
                  <li><a href="#maps"><i class="fas fa-chevron-right"></i>Peta Kerawanan</a></li>
                  <li><a href="#about"><i class="fas fa-chevron-right"></i>Tentang Kami</a></li>
                  <li><a href="#informasi"><i class="fas fa-chevron-right"></i>Informasi &amp; Edukasi</a></li>
               </ul>
            </div>

            <div class="col-sm-6 col-lg-4">
               <div class="ftit">Hubungi Kami</div>
               <div class="fci">
                  <div class="fciico"><i class="fas fa-map-marker-alt"></i></div>
                  <div class="fciinfo"><strong>Alamat Mako</strong>Jl. Raya Serpong No.1, Cilenggang, Kec. Serpong, Kota Tangerang Selatan, Banten 15310</div>
               </div>
               <div class="fci">
                  <div class="fciico"><i class="fas fa-phone-alt"></i></div>
                  <div class="fciinfo"><strong>Hotline Respon</strong>0811 900 074 / Call Center 112</div>
               </div>
               <div class="fci">
                  <div class="fciico"><i class="fas fa-envelope"></i></div>
                  <div class="fciinfo"><strong>Email Resmi</strong>damkar@tangerangselatankota.go.id</div>
               </div>
               <div class="fci">
                  <div class="fciico"><i class="fas fa-clock"></i></div>
                  <div class="fciinfo"><strong>Waktu Operasional</strong>24 Jam Siaga Darurat (Senin - Minggu)</div>
               </div>
            </div>
         </div>
      </div>

      <div class="fbot">
         <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
               <p>&copy; {{ date('Y') }} <span>Dinas Damkar dan Penyelamatan Kota Tangerang Selatan</span>.<br> All Rights Reserved.</p>
               <div><a href="#">Kebijakan Privasi</a><a href="#">Syarat &amp; Ketentuan</a></div>
            </div>
         </div>
      </div>
   </footer>

   {{-- Back to top --}}
   <button id="btt" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fas fa-chevron-up"></i></button>

   {{-- WhatsApp Floating Button --}}
   <a href="https://wa.me/62811900074?text={{ rawurlencode('Halo Damkar Tangsel, saya ingin melaporkan kejadian darurat / bertanya tentang layanan.') }}"
      class="wa-float"
      target="_blank"
      rel="noopener"
      aria-label="Hubungi via WhatsApp"
      title="Hubungi via WhatsApp">
      <i class="fab fa-whatsapp"></i>
   </a>
   {{-- SCRIPTS (urutan penting: Leaflet sebelum script peta) --}}
   <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
   <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('js/aos.js') }}"></script>
   <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
   <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
   <script src="{{ asset('js/main.js') }}"></script>
   <script>
   (function () {
      const tabs  = document.querySelectorAll('#edukasiTabs .filtbtn');
      const cards = document.querySelectorAll('#mgrid .mwrap');

      tabs.forEach(btn => {
         btn.addEventListener('click', function () {
            const f = this.dataset.f;

            tabs.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            cards.forEach(card => {
               const tampil = f === 'all' || card.dataset.c === f;
               card.classList.toggle('d-none', !tampil);
            });

            if (window.AOS) AOS.refresh();
         });
      });
   })();
   </script>
   <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

   <script>
   (function () {
      const URL_DATA     = @json(route('peta.data'));
      const URL_BOUNDARY = @json(route('peta.boundary'));

      // Data dari Laravel
      const PERIODE_PER_JENIS = @json($periodePerJenis);
      const JENIS_DEFAULT     = @json($jenisDefault);
      const PERIODE_DEFAULT   = @json($periodeDefault);

      const map = L.map('map', { scrollWheelZoom: false }).setView([-6.295, 106.704], 12);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
         maxZoom: 19,
         attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      // Scroll-zoom baru aktif setelah peta diklik agar tidak mengganggu scroll halaman
      map.on('click', () => map.scrollWheelZoom.enable());
      map.on('mouseout', () => map.scrollWheelZoom.disable());

      const norm = s => String(s || '').trim().toLowerCase();
      const esc  = s => String(s ?? '').replace(/[&<>"']/g, c => ({
         '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
      }[c]));

      const $loading = document.getElementById('mapLoading');
      const $status  = document.getElementById('mapStatus');
      const $jenis   = document.getElementById('filterJenis');
      const $periode = document.getElementById('filterPeriode');

      let boundary = [];
      const layerGroup = L.featureGroup().addTo(map);
      let fitted = false;

      /*
      |--------------------------------------------------------------------------
      | ISI DROPDOWN PERIODE BERDASARKAN JENIS
      |--------------------------------------------------------------------------
      */

      function isiPeriode(jenis, pilihPeriode = null) {
         const list = PERIODE_PER_JENIS[jenis] || [];

         $periode.innerHTML = '';

         if (!list.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Belum ada data';
            $periode.appendChild(opt);
            $periode.disabled = true;
            return;
         }

         $periode.disabled = false;

         list.forEach(function (p) {
            const opt = document.createElement('option');
            opt.value = p.value;
            opt.textContent = p.label;
            if (pilihPeriode !== null && String(pilihPeriode) === String(p.value)) {
               opt.selected = true;
            }
            $periode.appendChild(opt);
         });
      }

      /*
      |--------------------------------------------------------------------------
      | RENDER PETA
      |--------------------------------------------------------------------------
      */

      function renderPeta(payload) {
         layerGroup.clearLayers();
         const hasil = payload.hasil || {};

         boundary.forEach(w => {
            if (!w.path || !w.path.length) return;

            const h = hasil[norm(w.nama) + '|' + norm(w.kecamatan)] || null;
            const baseOpacity = h ? 0.65 : 0.20;

            const poly = L.polygon(w.path, {
               color: '#ffffff',
               weight: 1,
               fillColor: h ? h.warna : '#adb5bd',
               fillOpacity: baseOpacity
            });

            let popup = `<div style="min-width:200px">
               <h6 class="fw-bold mb-1">${esc(w.nama)}</h6>
               <div class="small text-muted mb-2">Kecamatan ${esc(w.kecamatan)}</div>`;

            if (h) {
               popup += `
               <span style="display:inline-block;padding:4px 10px;border-radius:5px;color:#fff;background:${esc(h.warna)}">
                  Kerawanan ${esc(h.tingkat_kerawanan)}
               </span>
               <div class="small mt-2">Jumlah kejadian: <strong>${esc(h.jumlah_kejadian)}</strong></div>`;
            } else {
               popup += `<div class="small text-muted">Belum ada data untuk periode ini.</div>`;
            }
            popup += `</div>`;

            poly.bindPopup(popup);
            poly.on('mouseover', function () { this.setStyle({ weight: 2, color: '#333', fillOpacity: h ? 0.8 : 0.3 }); });
            poly.on('mouseout',  function () { this.setStyle({ weight: 1, color: '#fff', fillOpacity: baseOpacity }); });

            layerGroup.addLayer(poly);
         });

         if (!fitted && layerGroup.getLayers().length) {
            map.fitBounds(layerGroup.getBounds(), { padding: [20, 20] });
            fitted = true;
         }
      }

      function updateRingkasan(payload) {
         const s = payload.statistik || {};
         document.getElementById('stRendah').textContent = s.rendah ?? 0;
         document.getElementById('stSedang').textContent = s.sedang ?? 0;
         document.getElementById('stTinggi').textContent = s.tinggi ?? 0;

         if (payload.ada) {
            $status.innerHTML = 'Data diperbarui: <strong class="text-danger">' + esc(payload.diperbarui) + '</strong>';
         } else {
            $status.textContent = 'Data kerawanan untuk pilihan ini belum tersedia.';
         }
      }

      async function muatData() {
         if (!$periode.value) {
            $status.textContent = 'Belum ada data untuk jenis ini.';
            return;
         }

         $loading.style.display = 'flex';

         const params = new URLSearchParams({
            periode: $periode.value,
            jenis_kejadian: $jenis.value
         });

         try {
            const res = await fetch(URL_DATA + '?' + params, { headers: { 'Accept': 'application/json' } });
            const payload = await res.json();
            renderPeta(payload);
            updateRingkasan(payload);
         } catch (e) {
            $status.textContent = 'Gagal memuat data peta. Silakan coba lagi.';
         } finally {
            $loading.style.display = 'none';
         }
      }

      /*
      |--------------------------------------------------------------------------
      | LEGEND
      |--------------------------------------------------------------------------
      */

      const legend = L.control({ position: 'bottomright' });
      legend.onAdd = function () {
         const div = L.DomUtil.create('div', 'legend');
         div.style.cssText = 'background:#fff;padding:10px 12px;border-radius:6px;box-shadow:0 1px 5px rgba(0,0,0,.25);font-size:12px;line-height:20px';
         div.innerHTML = '<strong>Tingkat Kerawanan</strong>' +
            [['#22c55e','Rendah'],['#eab308','Sedang'],['#ef4444','Tinggi'],['#adb5bd','Belum ada data']]
               .map(([c,t]) => `<div><span style="display:inline-block;width:14px;height:14px;background:${c};margin-right:6px;vertical-align:middle;border:1px solid #999"></span>${t}</div>`)
               .join('');
         return div;
      };
      legend.addTo(map);

      /*
      |--------------------------------------------------------------------------
      | INIT
      |--------------------------------------------------------------------------
      */

      (async function init() {
         isiPeriode(JENIS_DEFAULT, PERIODE_DEFAULT);

         try {
            const res = await fetch(URL_BOUNDARY, { headers: { 'Accept': 'application/json' } });
            boundary = await res.json();
            if (!boundary.length) {
               $status.textContent = 'Batas wilayah belum dapat dimuat. Silakan muat ulang halaman.';
               $loading.style.display = 'none';
               return;
            }
         } catch (e) {
            $status.textContent = 'Batas wilayah gagal dimuat.';
            $loading.style.display = 'none';
            return;
         }
         muatData();
      })();

      /*
      |--------------------------------------------------------------------------
      | EVENT: GANTI JENIS → ISI ULANG PERIODE → MUAT DATA
      |--------------------------------------------------------------------------
      */

      $jenis.addEventListener('change', function () {
         isiPeriode(this.value);
         fitted = false;
         muatData();
      });

      $periode.addEventListener('change', function () {
         fitted = false;
         muatData();
      });

      // Peta di dalam section AOS kadang salah ukuran saat pertama render
      setTimeout(() => map.invalidateSize(), 600);
   })();
   </script>
</body>
</html>