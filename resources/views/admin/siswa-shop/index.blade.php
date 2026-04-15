@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="font-weight-bold mb-4" style="color: #FF8C00;">🛒 Shop & Inventory Manager</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="font-weight-bold">Tambah Item Reward</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.siswa-shop.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Token/Voucher</label>
                            <input type="text" name="item_name" class="form-control" placeholder="Cth: Bebas Telat 15 Menit" required>
                        </div>
                        <div class="form-group">
                            <label>Harga (Poin)</label>
                            <input type="number" name="point_cost" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-outline-warning btn-block font-weight-bold">Publish Item</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                @foreach($items as $item)
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 15px;">
                        <h6 class="font-weight-bold">{{ $item->item_name }}</h6>
                        <h4 class="text-warning font-weight-bold">{{ $item->point_cost }} <small>Poin</small></h4>
                        <hr>
                        <small class="text-muted">ID Item: #{{ $item->id }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection