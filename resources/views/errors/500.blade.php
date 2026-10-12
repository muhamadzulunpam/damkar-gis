@include('layouts.errors.layout', [
    'code'         => '500',
    'title'        => 'Kesalahan Server',
    'message'      => 'Terjadi kesalahan internal pada server. Silakan coba beberapa saat lagi.',
    'icon'         => 'bi-exclamation-triangle',
    'iconColor'    => '#dc3545',
    'iconColorDark'=> '#b02a37',
    'iconBg'       => 'rgba(220,53,69,.1)',
])