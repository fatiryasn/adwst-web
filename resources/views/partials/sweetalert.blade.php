@if (session('swal_success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'success',
            text: "{{ session('swal_success') }}",
            confirmButtonColor: '#4f46e5',
        });
    });
</script>
@endif

@if (session('swal_error'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'warning',
            text: "{{ session('swal_error') }}",
            confirmButtonColor: '#4f46e5',
        });
    });
</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'error',
            html: "{!! implode('<br>', $errors->all()) !!}",
            confirmButtonColor: '#4f46e5',
        });
    });
</script>
@endif