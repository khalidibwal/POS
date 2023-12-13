@extends('layouts.app')
<!-- © 2020 Copyright: Tahu Coding -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card" style="min-height: 85vh">
                <div class="card-header bg-white">
                    <h4 class="font-weight-bold">History Transaction</h4>
                </div>
                <div class="card-body">
                    <!-- Date Search Form -->
                    <form action="{{ url('/transcation/history') }}" method="GET" class="mb-3">
                        <div class="form-row">
                            <div class="col">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="col">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                            <div class="col mt-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>

                    <!-- Transaction History Table -->
                    <table class="table table-sm">
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoices</th>
                            <th>Admin</th>
                            <th>Bayar</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                        @foreach ($history as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->invoices_number}}</td>
                                <td>{{$item->user->name}}</td>
                                <td>@currency($item->pay)</td>
                                <td>@currency($item->total)</td>
                                <td><a href="{{ url('/transcation/laporan', $item->invoices_number ) }}" class="btn btn-primary btn-sm"><i class="fas fa-print"></i></a></td>
                            </tr>
                        @endforeach                        
                    </table>
                    <div>{{ $history->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
