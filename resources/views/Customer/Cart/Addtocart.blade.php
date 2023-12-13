<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
        }

        .cart-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .cart-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            max-width: 100px;
            height: auto;
            margin-right: 15px;
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .cart-item-details h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
        }

        .cart-item-details p {
            margin: 0;
            color: #555;
        }

        .cart-item-price {
            font-size: 1.2em;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .cart-item img {
                margin-bottom: 10px;
            }

            .cart-item-details {
                text-align: center;
            }
        }

        table {
            width: 100%;
        }

        table th,
        table td {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .checkbox-label {
            display: block;
        }

        .checkbox-input {
            margin-right: 5px;
        }
    </style>
    <title>Responsive Cart List</title>
</head>
<body>

<div class="cart-container">
    <div class="cart-item mb-3">
        <a href="{{ route('kalaha', ['form_data_id' => $customerId]) }}">Go back to Menu</a>
        <table class="table table-sm">
            <thead>
            <tr>
                <th width="10%">No</th>
                <th width="30%">Nama Product</th>
                <th width="30%">Qty</th>
                <th width="30%" class="text-right">Sub Total</th>
            </tr>
            </thead>
            <tbody>
            @php
                $no=1
            @endphp
            @forelse($cart_data as $index=>$item)
                <tr>
                    <td>
                        <form action="{{url('/transcation/removeproduct',$item['rowId'])}}"
                              method="POST">
                            @csrf
                            {{$no++}} <br><a onclick="this.closest('form').submit();return false;"><i
                                    class="fas fa-trash" style="color: rgb(134, 134, 134)"></i></a>
                        </form>
                    </td>
                    <td>{{Str::words($item['name'],3)}} <br>Rp.
                        {{ number_format($item['pricesingle'],2,',','.') }}
                    </td>
                    <td class="font-weight-bold">
                        <form action="{{ route('decreasecart', ['customer_id' => $customerId, 'rowId' => $item['rowId']]) }}"
                              method="POST" style='display:inline;'>
                            @csrf
                            <button class="btn btn-sm btn-info"
                                    style="display: inline;padding:0.4rem 0.6rem!important"><i
                                    class="fas fa-minus"></i></button>
                        </form>
                        <a style="display: inline">{{$item['qty']}}</a>
                        <form action="{{ route('increasecart', ['customer_id' => $customerId, 'rowId' => $item['rowId']]) }}"
                              method="POST" style='display:inline;'>
                            @csrf
                            <button class="btn btn-sm btn-primary"
                                    style="display: inline;padding:0.4rem 0.6rem!important"><i
                                    class="fas fa-plus"></i></button>
                        </form>
                    </td>
                    <td class="text-right">Rp. {{ number_format($item['price'],2,',','.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Empty Cart</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <table class="table table-sm table-borderless">
        <tr>
            <th width="60%">Sub Total</th>
            <th width="40%" class="text-right">Rp.
                {{ number_format($data_total['sub_total'],2,',','.') }} </th>
        </tr>
        <tr>
            <th>
                <form action="{{ url('/transcation') }}" method="get">
                    PB1 10%
                    {{-- <input type="checkbox" {{ $data_total['tax'] > 0 ? "checked" : ""}} name="tax" class="checkbox-input"
                           value="true" onclick="this.form.submit()"> --}}
                </form>
            </th>
            <th class="text-right">Rp.
                {{ number_format($data_total['tax'],2,',','.') }}</th>
        </tr>
        <tr>
            <th>Total</th>
            <th class="text-right font-weight-bold">Rp.
                {{ number_format($data_total['total'],2,',','.') }}</th>
        </tr>
    </table>
</div>

</body>
</html>
