@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="font-weight-bold mb-4" style="color: #FF8C00;">✨ Gamification Manager</h2>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="font-weight-bold text-dark">⚙️ Dynamic Rule Engine</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.gamifikasi.rule.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Aturan</label>
                            <input type="text" name="rule_name" class="form-control" placeholder="Cth: Datang Pagi Banget" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Operator Jam</label>
                                <select name="condition_operator" class="form-control">
                                    <option value="<">Kurang Dari (<)</option>
                                    <option value=">">Lebih Dari (>)</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Waktu Batas (Time)</label>
                                <input type="time" name="condition_value" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Poin Modifier</label>
                            <input type="number" name="point_modifier" class="form-control" placeholder="Cth: 5 atau -3" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block text-white font-weight-bold">Simpan Aturan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="font-weight-bold text-dark">🏆 Leaderboard Bulan Ini</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection