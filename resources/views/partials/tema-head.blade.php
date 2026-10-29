<script>
    try {
        document.documentElement.setAttribute(
            'data-tema',
            localStorage.getItem('scac-modo') === 'oscuro' ? 'oscuro' : 'claro'
        );
    } catch (error) {
        document.documentElement.setAttribute('data-tema', 'claro');
    }
</script>
<link rel="stylesheet" href="{{ asset('css/tema.css') }}?v={{ filemtime(public_path('css/tema.css')) }}">