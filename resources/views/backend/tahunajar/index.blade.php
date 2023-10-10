@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">Tahun Ajar
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-3 mt-2"><input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-9 mt-2">
                        @permission('tahunajar-create')
                            <a href="{{ route('tahunajar.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endpermission

                    </div>
                </div>

                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif

                <table class="table table-sm table-hover display nowrap mb-4" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>tahun</th>
                            <th>kuota</th>
                            <th>keterangan</th>
                            <th>periode mulai</th>
                            <th>periode akhir</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>

                @permission('tahunajar-delete')
                    <a href="{{ route('tahunajar.delete') }}" class="btn btn-xs btn-outline-primary hapus-kolektif"><i
                            class='bx bx-trash float-start'></i> Hapus</a>
                @endpermission
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet"
        href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.7/css/dataTables.checkboxes.css">
    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.7/js/dataTables.checkboxes.min.js"></script>

    <script>
        var datatables = $('#datatable').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            bDestroy: true,
            ajax: {
                url: "{{ route('tahunajar-ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = $('.btn-check:checked').val();
                    d.cari = $('#cari').val();
                },
            },
            columnDefs: [{
                targets: 0,
                checkboxes: {
                    selectRow: true
                }
            }],
            select: {
                style: 'multi'
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'tahun'
                },
                {
                    data: 'kuota'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'mulai'
                },
                {
                    data: 'akhir'
                },
                {
                    data: 'aksi'
                },
            ],
            fnDrawCallback: function(e){
                let status = $('.btn-check:checked').val();
                if(Number(status) === 1){
                    $('.hapus-kolektif').show();
                    $('.dataTable input[type="checkbox"]').removeAttr('disabled');
                }else{
                    $('.hapus-kolektif').hide();
                    $('.dataTable input[type="checkbox"]').attr('disabled','disabled');
                }
            }
        });

        $('#cari').keyup(function() {
            datatables.ajax.reload();
        });
    </script>
@endsection
