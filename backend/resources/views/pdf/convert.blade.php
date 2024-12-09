<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $files->register . '_' . $files->mr . '_' . $files->nama_dok }}</title>
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    <style>
        body {
            padding: 2rem 1rem
        }

        .container {
            padding: 1rem
        }

        .cover {
            padding: 15px;
        }

        .img-cover {
            text-align: center;
            width: 100%;
            height: 100vh;
            margin-bottom: 2rem;
        }

        .img-cover img {
            width: 100%;
        }
    </style>
</head>

<body style="font-size:13px;">

    <div class="container">

        <div class="row relative" style="text-align: center">

            <img src="{{ asset('gs-logo.png') }}" width="80px" class="d-inline-block relative" style="max-width: 15%">

            <div class="d-inline-block max-width" style="width: 700px;margin: 0 auto; max-width: 70%">

                <ol class="inner text-center">

                    <li>

                        <h2 style="font-weight: bold; font-size: 1.5em">RUMAH SAKIT</h2>

                    </li>

                    <li>

                        <h1 style="color: red; font-weight: bold; font-size: 2em">"GRAHA SEHAT"</h1>

                    </li>

                    <li class="mb-1"><small class="font-weight-1">Jl. Raya Panglima Sudirman No 2 Kraksaan -

                            Probolinggo</small></li>

                    <li><small class="font-weight-1">Telp. (0335) 846500, 846354, 844200 Fax (0335) 846500</small>

                    </li>

                    {{-- <li><small class="font-weight">UNIT LABORATORIUM</small></li> --}}

                </ol>

            </div>

            <img src="{{ asset('paripurna-no-bg.png') }}" width="96px" class="d-inline-block relative"
                style="margin-right: auto;">

        </div>

        <hr style="line-height: 3px; background-color: black">

        <h2 class="prt-title" style="font-weight: bold; margin-bottom: 1rem">DOKUMEN {{ $files->nama_dok }}</h2>

    </div>

    <div style="text-align: center">
        <div class="cover">
            @foreach ($files->filepond as $file)
                {{-- <img src="{{ asset('public/storage/' . $file->filepath . '/' . $file->filename) }}"> --}}
                <div class="img-cover">
                    <img src="{!! Illuminate\Support\Facades\Storage::disk($file->disk)->url($file->filepath . '/' . $file->filename) !!}">
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
