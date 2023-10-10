@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('tahunajar.update', ['tahunajar' => $tahunajar->id]) }}" method="POST"
            enctype="multipart/form-data" id="my-form">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <h5 class="card-header">edit tahun ajar</h5>
                        <div class="card-body">

                            @if (session()->has('pesan'))
                                {!! session('pesan') !!}
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">tahun</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="tahun"
                                        value="{{ $tahunajar->tahun }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">kuota</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kuota"
                                        value="{{ $tahunajar->kuota }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">keterangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="keterangan"
                                        value="{{ $tahunajar->keterangan }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">mulai</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control tanggal" name="mulai"
                                        value="{{ $tahunajar->mulai }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">akhir</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control tanggal" name="akhir"
                                        value="{{ $tahunajar->akhir }}">
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('tahunajar.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator->selector('#my-form') !!}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script>
        $('.tanggal').flatpickr({
            minDate: "today",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
@endsection
