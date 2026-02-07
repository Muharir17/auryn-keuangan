@extends('adminlte::master')

@section('title', '403 Forbidden')

@section('classes_body', 'login-page')

@section('body')
<div class="error-page" style="margin-top: 50px;">
    <h2 class="headline text-warning"> 403</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Akses Ditolak.</h3>
        <p>
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
            Silakan hubungi Administrator jika Anda merasa ini adalah kesalahan.
            <br>
            Sementara itu, Anda dapat <a href="{{ url('/home') }}">kembali ke dashboard</a>.
        </p>

        <form class="search-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Siswa/Data...">
                <div class="input-group-append">
                    <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('adminlte_css')
<style>
    .error-page {
        width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .headline {
        float: left;
        font-size: 100px;
        font-weight: 300;
        margin-right: 30px;
        line-height: 100px;
    }

    .error-content {
        margin-left: 190px;
        display: block;
    }

    .error-content h3 {
        font-size: 25px;
        margin-top: 0;
    }

    @media (max-width: 767px) {
        .error-page {
            width: 100%;
            padding: 20px;
        }

        .headline {
            float: none;
            text-align: center;
            margin-right: 0;
        }

        .error-content {
            margin-left: 0;
            text-align: center;
        }
    }
</style>
@stop