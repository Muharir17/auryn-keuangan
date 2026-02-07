@extends('adminlte::master')

@section('title', '500 Server Error')

@section('classes_body', 'login-page')

@section('body')
<div class="error-page" style="margin-top: 50px;">
    <h2 class="headline text-danger"> 500</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Terjadi Kesalahan Server.</h3>
        <p>
            Maaf, sistem mengalami masalah saat memproses permintaan Anda.
            Tim Admin kami telah diberitahu tentang masalah ini.
            <br>
            Silakan coba lagi nanti atau <a href="{{ url('/home') }}">kembali ke dashboard</a>.
        </p>
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

    .headline.text-danger {
        color: #dc3545 !important;
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