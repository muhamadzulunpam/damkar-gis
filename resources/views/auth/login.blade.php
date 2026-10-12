

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="author" content="Damkar GIS">
      <meta name="description" content="Damkar GIS - Sistem Informasi Pemetaan Kerawanan Kebakaran & Penyelamatan Kota Tangerang Selatan">
      <title>Login - Damkar GIS</title>
      <link rel="icon" type="image/png" href="{{ asset('img/logo_damkar.png') }}">
      <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
      <!-- Bootstrap 5.3 -->
      <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
      <!-- AOS Animate on Scroll -->
      <link href="{{ asset('css/aos.css') }}" rel="stylesheet"/>
      <!-- Swiper -->
      <link href="{{ asset('css/swiper-bundle.min.css') }}" rel="stylesheet"/>
      <!-- all min css -->
      <link rel="stylesheet" href="{{ asset('css/all.min.css') }}"/>
      <!-- magnific CSS -->
      <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}"/>
      <!-- Style CSS -->
      <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
      <style>
         
         .resico {
         width: 54px;
         height: 54px;
         border-radius: 13px;
         background: rgba(232, 40, 26, 0.1);
         display: flex;
         align-items: center;
         justify-content: center;
         color: var(--primary);
         font-size: 1.25rem;
         flex-shrink: 0;
         }
         .flbl {
         font-size: 0.83rem;
         font-weight: 600;
         color: var(--dark);
         margin-bottom: 5px;
         display: block;
         }
         .fctrl {
         width: 100%;
         border: 2px solid #eee;
         border-radius: 9px;
         padding: 11px 15px;
         font-family: "Poppins", sans-serif;
         font-size: 0.88rem;
         outline: none;
         transition: 0.3s;
         color: var(--dark);
         background: #fff;
         appearance: auto;
         }
         .fctrl:focus {
         border-color: var(--primary);
         }
         .fcarde {
         position: relative; 
         background: #fff;
         border-radius: 18px;
         padding: 32px;
         box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
         }

         /* Pengaturan gaya dan posisi tombol silang X */
         .close-card-btn {
         position: absolute;
         top: 15px;
         right: 20px;
         font-size: 1.25rem;
         color: #aaa;
         transition: 0.2s;
         text-decoration: none;
         }

         .close-card-btn:hover {
         color: var(--primary); 
         transform: scale(1.1);
         }

         .sucmsg {
         display: none;
         text-align: center;
         padding: 16px;
         background: rgba(45, 106, 79, 0.07);
         border-radius: 11px;
         margin-top: 14px;
         }
         .sucmsg i {
         color: var(--green);
         font-size: 1.7rem;
         display: block;
         margin-bottom: 7px;
         }
         .sucmsg p {
         color: var(--green);
         font-weight: 600;
         margin: 0;
         font-size: 0.88rem;
         }
         .hcircle {
            border-radius: 20% !important;
         }
         .hcircle img {
            border-radius: 20% !important;
         }
         @media (max-width: 767px) {
            .hcircle {
               display: none;
            }
         }
      </style>
   </head>
   <body>
      <!-- ============================================================
         TOP BAR 
         ============================================================ -->
      <div class="mqsec">
         <div class="mqtrack">
            <div class="mqitem"><i class="fas fa-fire-extinguisher"></i>Pemadaman Kebakaran</div>
            <div class="mqitem"><i class="fas fa-ambulance"></i>Penyelamatan &amp; Evakuasi</div>
            <div class="mqitem"><i class="fas fa-shield-alt"></i>Pencegahan Dini Kebakaran</div>
            <div class="mqitem"><i class="fas fa-ambulance"></i>Evakuasi Hewan Liar (Rescue)</div>
            <div class="mqitem"><i class="fas fa-hard-hat"></i>Edukasi &amp; Sosialisasi</div>
            <div class="mqitem"><i class="fas fa-clock"></i>Layanan Darurat 24 Jam</div>
            <div class="mqitem"><i class="fas fa-truck-moving"></i>Armada Cepat Tanggap</div>
            <div class="mqitem"><i class="fas fa-fire-extinguisher"></i>Pemadaman Kebakaran</div>
            <div class="mqitem"><i class="fas fa-ambulance"></i>Penyelamatan &amp; Evakuasi</div>
            <div class="mqitem"><i class="fas fa-shield-alt"></i>Pencegahan Dini Kebakaran</div>
            <div class="mqitem"><i class="fas fa-ambulance"></i>Evakuasi Hewan Liar (Rescue)</div>
            <div class="mqitem"><i class="fas fa-hard-hat"></i>Edukasi &amp; Sosialisasi</div>
            <div class="mqitem"><i class="fas fa-clock"></i>Layanan Darurat 24 Jam</div>
            <div class="mqitem"><i class="fas fa-truck-moving"></i>Armada Cepat Tanggap</div>
         </div>      
      </div>

      <section id="hero">
         <div class="container">
            
            <div class="row align-items-center g-5" style="min-height:60vh;">
               <div class="col-lg-6">
                  @if ($errors->any())
                     <div class="alert alert-danger mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 0.85rem; border-radius: 9px;">
                        <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                        <div>
                           {{ $errors->first() }}
                        </div>
                     </div>
                  @endif
                  <div class="fcarde">
                     <div class="text-center mb-5" data-aos="fade-up">
                        <span class="slbl">Akses Terbatas</span>
                        <h2 class="stitle">Login <span>Sistem Informasi</span></h2>
                        <div class="sline"></div>
                     </div>
                     <a href="/" class="close-card-btn" title="Kembali ke Beranda">
                        <i class="fas fa-times"></i>
                     </a>
                     <form action="{{ route('login.process') }}" method="POST">
                     @csrf 
                        <div class="row g-3">
                           <div class="col-sm-12">
                              <label class="flbl">Email <span class="text-danger">*</span></label>
                              <input type="email" name="email" class="fctrl" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus/>
                           </div>
                           <div class="col-sm-12">
                              <label class="flbl">Password <span class="text-danger">*</span></label>
                              <input type="password" name="password" class="fctrl" placeholder="••••••••" required/>
                           </div>
                           <div class="col-12 mt-4">
                              <button type="submit" class="btn-red w-100 justify-content-center" id="loginBtn">
                                 Masuk Sistem
                              </button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div style="position:relative;text-align:center;">
                     <div class="hcircle">
                        <img src="img/damkar.jpg" alt="damkar"/>
                     </div>
                     <div class="fcard fc1">
                        <div class="fcoi r"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <span class="fcnum">Call Center</span>
                            <span class="fcsm">0811900074</span>
                        </div>
                    </div>
                    <div class="fcard fc2">
                        <div class="fcoi y"><i class="fas fa-clock"></i></div>
                        <div>
                            <span class="fcnum">24 Jam</span>
                            <span class="fcsm">Siaga Darurat</span>
                        </div>
                    </div>
                    <div class="fcard fc3">
                        <div class="fcoi g"><i class="fas fa-map"></i></div>
                        <div>
                            <span class="fcnum">Web GIS</span>
                            <span class="fcsm">Pemetaan Interaktif</span>
                        </div>
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </section>

	  
	<!-- jQuery -->
      <script src="js/jquery-3.7.1.min.js"></script>
      <!-- Bootstrap 5 -->
      <script src="js/bootstrap.bundle.min.js"></script>
      <!-- AOS -->
      <script src="js/aos.js"></script>
      <!-- Swiper -->
      <script src="js/swiper-bundle.min.js"></script>
      <!-- CounterUp -->
      <script src="js/jquery.magnific-popup.min.js"></script>
      <!-- Main js -->
      <script src="js/main.js"></script>
   </body>
</html>
