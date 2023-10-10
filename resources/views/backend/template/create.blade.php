@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('template.store') }}" method="POST" enctype="multipart/form-data" id="my-form">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <h5 class="card-header">Tambah Template</h5>
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
                                <label class="col-sm-3 col-form-label">template</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="template">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">keterangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="keterangan">
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('template.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <h5 class="card-header">Form</h5>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th width="80%">FORM</th>
                                        <th width="10%">TAMPIL <br> <input type="checkbox" id="tampilAll"></th>
                                        <th width="10%">WAJIB <br> <input type="checkbox" id="wajibAll"></th>
                                    </tr>
                                </thead>
                            </table>
                            <section style="height: 300px; overflow-y:scroll">
                                <table class="table table-sm table-hover">
                                    <tbody>
                                        @foreach ($grups as $grup)
                                            <tr>
                                                <td width="80%"><b>{{ \Str::upper($grup->grup) }}</b></td>
                                                <td width="10%">
                                                    <input type="checkbox" name="grup[]" class="tampil grup_tampil"
                                                        data-grup_id="{{ $grup->id }}" value="{{ $grup->id }}">
                                                </td>
                                                <td width="10%">

                                                </td>
                                            </tr>
                                            @foreach ($grup->form as $form)
                                                <tr>
                                                    <td>
                                                        <div class="ms-3">{{ \Str::upper($form->label) }}</div>
                                                    </td>
                                                    <td width="10%"><input type="checkbox" name="form[]"
                                                            class="tampil form-tampil-{{ $form->grup_id }}"
                                                            value="{{ $form->id }}"></td>
                                                    <td width="10%"><input type="checkbox" name="form_wajib[]"
                                                            class="wajib"
                                                            value="{{ $form->id }}"></td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </section>
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

    <script>
        $('input[type="checkbox"]').prop('checked', false);

        $("#tampilAll").click(function() {
            $('.tampil').not(this).prop('checked', this.checked);
        });

        $("#wajibAll").click(function() {
            $('.wajib').not(this).prop('checked', this.checked);
        });

        $('.grup_tampil').click(function() {
            const grup_id = $(this).data('grup_id');
            $(`.form-tampil-${grup_id}`).not(this).prop('checked', this.checked);
        })
    </script>
@endsection
