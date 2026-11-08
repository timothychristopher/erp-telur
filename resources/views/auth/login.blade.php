@extends('layouts.app_plain') @section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg border-0" style="width: 400px; border-radius: 20px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="mb-2">
                    <i class="fas fa-egg fa-3x" style="color: #fd7e14;"></i>
                </div>
                <h4 class="fw-bold">ERP OVOEGG</h4>
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm small" style="border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="user@ovoegg.com" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordField" class="form-control border-end-0" placeholder="Masukkan password" required>
                        <span class="input-group-text bg-white border-start-0" id="togglePassword" style="cursor: pointer;">
                            <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn text-white w-100 py-2 mb-3" style="background-color: #fd7e14; border-radius: 10px;">
                    Login Sekarang
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#passwordField');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        // Toggle tipe input
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle ikon mata
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

<style>
    body { background-color: #f8f9fa; }
    .form-control:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.25);
    }
    .input-group-text:hover i {
        color: #fd7e14 !important;
    }
</style>
@endsection