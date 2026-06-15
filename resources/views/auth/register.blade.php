@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="row justify-content-center align-items-center py-5">
    <div class="col-md-5">
        <div class="card card-custom p-4 shadow-lg">
            <div class="text-center mb-4">
                <i class="fa-solid fa-user-plus text-warning fs-1 mb-2"></i>
                <h3 class="fw-bold" style="color: var(--bpbd-navy)">Daftar Akun SiGAPBanjir</h3>
                <p class="text-muted">Buat akun untuk melaporkan kejadian banjir di sekitar Anda.</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="contoh: Rian Hidayat" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="contoh: rian@gmail.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-bpbd w-100 py-2.5 mb-3">
                    <i class="fa-solid fa-circle-check me-2"></i>Daftar Sekarang
                </button>
            </form>

            <div class="text-center">
                <span class="text-muted">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="text-warning fw-bold text-decoration-none ms-1">Masuk</a>
            </div>
        </div>
    </div>
</div>
@endsection
