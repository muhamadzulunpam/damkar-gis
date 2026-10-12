@include('errors.layout', [
    'code'         => '403',
    'title'        => 'Akses Ditolak',
    'message'      => 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi administrator jika menurut Anda ini sebuah kesalahan.',
    'icon'         => 'bi-shield-lock',
    'iconColor'    => '#dc3545',
    'iconColorDark'=> '#b02a37',
    'iconBg'       => 'rgba(220,53,69,.1)',
])