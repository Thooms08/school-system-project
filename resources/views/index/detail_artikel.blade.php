@extends('layouts.app')

@section('title', $artikel->judul)

@section('content')
<section class="py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Beranda</a></li>
                        <li class="breadcrumb-item active">Artikel</li>
                    </ol>
                </nav>

                <h1 class="fw-bold mb-4">{{ $artikel->judul }}</h1>
                
                @if($artikel->fotos->count() > 0)
                    <div class="mb-4">
                        <img src="{{ asset($artikel->fotos->first()->foto_artikel) }}" class="img-fluid rounded-4 shadow w-100" alt="Foto" style="width: 80%; height:80% ; object-fit: cover">
                        <p class="mt-2 text-center small text-muted">Sumber gambar: {{ $artikel->fotos->first()->sumber_foto }}</p>
                    </div>
                @endif

                <div class="article-body fs-5 lh-lg">
                    {!! nl2br(e($artikel->deskripsi)) !!}
                </div>

                <div class="mt-5 border-top pt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-success rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection