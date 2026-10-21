<script>
    try {
        document.documentElement.setAttribute(
            'data-tema',
            localStorage.getItem('scac-modo') === 'claro' ? 'claro' : 'oscuro'
        );
    } catch (error) {
        document.documentElement.setAttribute('data-tema', 'oscuro');
    }
</script>
<link rel="stylesheet" href="{{ asset('css/tema.css') }}?v={{ filemtime(public_path('css/tema.css')) }}">