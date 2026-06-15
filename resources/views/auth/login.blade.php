@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center py-5">
    <div class="col-md-5">
        <div class="card card-custom p-4 shadow-lg">
            <div class="text-center mb-4">
                <i class="fa-solid fa-shield-halved text-warning fs-1 mb-2"></i>
                <h3 class="fw-bold" style="color: var(--bpbd-navy)">Masuk ke SiGAPBanjir</h3>
                <p class="text-muted">Gunakan akun Anda untuk masuk ke sistem.</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="contoh: masyarakat@gmail.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan kata sandi Anda" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 form-check d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-bpbd w-100 py-2.5 mb-3">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
                </button>
            </form>

            <div class="text-center">
                <span class="text-muted">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="text-warning fw-bold text-decoration-none ms-1">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
