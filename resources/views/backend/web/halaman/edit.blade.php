@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('halaman.update', ['halaman' => $halaman->id]) }}" method="POST" enctype="multipart/form-data" id="my-form">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <h5 class="card-header">edit halaman</h5>
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
                                <label class="col-sm-2 col-form-label">judul</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="judul" value="{{ $halaman->judul }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">deskripsi</label>
                                <div class="col-sm-10">
                                    <textarea name="deskripsi" id="deskripsi" class="form-control quilljs-textarea" cols="30" rows="10">{!! $halaman->deskripsi !!}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="slug" value="{{ $halaman->slug }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pengaturan</h5>

                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="email">Gambar utama</label>
                                <div class="col-sm-12">
                                    <div class="button-wrapper">
                                        <button type="button" class="account-file-input btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                            <span class="d-none d-sm-block">Ganti foto</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                        </button>
                                        <input type="hidden" name="foto" id="foto"
                                            value="{{ $halaman->gambar ? $halaman->gambar : '' }}">
                                        <div><small class="text-muted mb-0">JPG, GIF, PNG. Maksimal ukuran 2000 Kb</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div id="box-foto">
                                        @if ($halaman->gambar)
                                            <img src="{{ url('/storage/' . $halaman->gambar) }}" class="rounded img-fluid">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('halaman.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal LOGO-->
    <div class="modal fade" id="modalUploadFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadFotoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadFotoLabel">Unggah gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif"></span>
                    <form action="{{ route('ganti-foto') }}" class="dropzone" id="upload-image" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="path" value="halaman">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-simpan"
                        onclick="simpanFoto()">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset('vendor/quilljs/quill-textarea.js') }}"></script>

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator->selector('#my-form') !!}

    <script>
        $(document).ready(function() {
            const judul = $('input[name="judul"]');
            const slug = $('input[name="slug"]');

            $(judul).keyup(function() {
                let val = $(this).val();
                let format = val.replace(/\s+/g, '-').toLowerCase();

                $(slug).val(format.trim());
            });
        });

        // ------------- Logo
        Dropzone.options.uploadImage = {
            maxFilesize: 2000,
            acceptedFiles: ".jpeg,.jpg,.png",
            method: 'post',
            createImageThumbnails: true,
            init: function() {
                this.on("addedfile", file => {
                    $('.btn-simpan').attr('disabled', 'disabled').text('Loading...');
                });
            },
            success: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const foto = response.file;
                $('.modal-body #notif').html(`<div class="alert alert-success">Foto berhasil diunggah</div>`);
                $('#foto').val(foto);
            },
            error: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };

        function simpanFoto($tipe = '') {
            let title = '';
            let foto = '';
            let boxImage = '';
            let notif = '';

            if ($tipe == '') {
                title = 'Foto';
                foto = $('#foto').val();
                boxImage = $('#box-foto');
                notif = $('#notif');
            }

            if (foto === '' || foto === null) {
                $(notif).html(`<div class="alert alert-danger">Tidak dapat menambahkan ${title}</div>`);
            } else {
                $('#modalUploadFoto, #modalUploadFavicon').modal('hide');
                $(boxImage).html(`<img src="{{ url('/storage/${foto}') }}" class="rounded img-fluid">`);
            }
        }
    </script>
@endsection
