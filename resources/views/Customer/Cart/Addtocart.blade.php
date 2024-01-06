<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha384-ez2tjlC0r8P1a5F6s5IYCIuZcFA6cPP3LT5O5SCdRVJTRXjUq/BRF7B84g5GCZJj" crossorigin="anonymous">

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
            padding: 20px;
            /* Added padding for container */
        }

        .cart-item {
            display: flex;
            flex-direction: column;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            /* Added margin between items */
        }

        .cart-item img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .cart-item-details {
            text-align: center;
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

        .btn {
            background: linear-gradient(to bottom, #3498db 0%, #2980b9 100%);
            /* Gradient background */
            border: none;
            /* Remove borders */
            color: white;
            /* White text */
            padding: 10px 20px;
            /* Some padding */
            font-size: 16px;
            /* Set a font size */
            cursor: pointer;
            /* Mouse pointer on hover */
            border-radius: 5px;
            width: 150px;
            margin-top: 5px
        }
        .btnqty{
            background: linear-gradient(to bottom, #3498db 0%, #2980b9 100%);
            /* Gradient background */
            border: none;
            /* Remove borders */
            color: white;
            /* White text */
            padding: 10px 20px;
            /* Some padding */
            font-size: 16px;
            /* Set a font size */
            cursor: pointer;
            /* Mouse pointer on hover */
            border-radius: 5px;
            width: '100%';
            margin-top: 5px
        }

        .btn:hover {
            background: linear-gradient(to bottom, #2980b9 0%, #3498db 100%);
            /* Darker gradient background on hover */
        }
        .back-to-menu:hover {
            background: linear-gradient(to bottom, #2980b9 0%, #3498db 100%);
            /* Darker gradient background on hover */
        }

        .back-to-menu {
            text-decoration: none;
            background-color: #bababa;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            margin-right: 10px;
        }

        @media (min-width: 769px) {
            .cart-item {
                flex-direction: row;
                align-items: center;
                text-align: left;
            }

            .cart-item img {
                margin-bottom: 0;
                margin-right: 15px;
            }

            .cart-item-details {
                text-align: left;
            }
        }

        table {
            width: 100%;
        }

        table th,
        table td {
            text-align: left;
            padding: 10px;
            /* Added padding for table cells */
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

        @media (max-width: 768px) {
            .cart-item {
                padding: 10px;
            }

            .cart-item img {
                margin-bottom: 5px;
            }

            .cart-item-details h3 {
                font-size: 1em;
            }

            .cart-item-details p {
                font-size: 0.8em;
            }

            table th,
            table td {
                padding: 8px;
            }
        }
    </style>
    <title>Responsive Cart List</title>
</head>

<body>

    <div class="cart-container">
        <div class="cart-item mb-3">
            <a href="{{ route('kalaha', ['form_data_id' => $customerId]) }}" class="back-to-menu">BACK</a>
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
                        $no = 1;
                    @endphp
                    @forelse($cart_data as $index=>$item)
                        <tr>
                            <td>
                                <form action="{{ url('/transcation/removeproduct', $item['rowId']) }}" method="POST">
                                    @csrf
                                    {{ $no++ }} <br><a onclick="this.closest('form').submit();return false;"><i
                                            class="fas fa-trash" style="color: rgb(134, 134, 134)"></i></a>
                                </form>
                            </td>
                            <td>{{ Str::words($item['name'], 3) }} <br>Rp.
                                {{ number_format($item['pricesingle'], 2, ',', '.') }}
                            </td>
                            <td class="font-weight-bold">
                                <form
                                    action="{{ route('decreasecart', ['customer_id' => $customerId, 'rowId' => $item['rowId']]) }}"
                                    method="POST" style='display:inline;'>
                                    @csrf
                                    <button class="btnqty btn-sm btn-info"
                                        style="display: inline;padding:0.4rem 0.6rem!important"><i
                                            class="fas fa-minus"></i>-</button>
                                </form>
                                <a style="display: inline">{{ $item['qty'] }}</a>
                                <form
                                    action="{{ route('increasecart', ['customer_id' => $customerId, 'rowId' => $item['rowId']]) }}"
                                    method="POST" style='display:inline;'>
                                    @csrf
                                    <button class="btnqty btn-sm btn-primary"
                                        style="display: inline;padding:0.4rem 0.6rem!important"><i
                                            class="fas fa-plus"></i>+</button>
                                </form>
                            </td>
                            <td class="text-right">Rp. {{ number_format($item['price'], 2, ',', '.') }}</td>
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
                    {{ number_format($data_total['sub_total'], 2, ',', '.') }} </th>
            </tr>
            <tr>
                <th>
                    <form action="{{ url('/transcation') }}" method="get">
                        PPN 10%
                        {{-- <input type="checkbox" {{ $data_total['tax'] > 0 ? "checked" : ""}} name="tax" class="checkbox-input"
                            value="true" onclick="this.form.submit()"> --}}
                    </form>
                </th>
                <th class="text-right">Rp.
                    {{ number_format($data_total['tax'], 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th>Total</th>
                <th class="text-right font-weight-bold">Rp.
                    {{ number_format($data_total['total'], 2, ',', '.') }}</th>
            </tr>
        </table>
        <div class="row">
            <div class="col-sm-4">
                <form action="{{ url('/transcation/clear') }}" method="POST">
                    @csrf
                    <button class="btn btn-info btn-lg btn-block" style="padding:1rem!important"
                        onclick="return confirm('Apakah anda yakin ingin meng-clear cart ?');"
                        type="submit">Clear</button>
                </form>
            </div>
            <div class="col-sm-4">
                <form action="{{ route('payment', ['customer_id' => $customerId]) }}" method="POST" id="paymentForm">
                    @csrf
                    <button class="btn btn-success btn-lg btn-block" style="padding: 1rem!important" type="submit"
                        onclick="confirmFinishOrder({{ $customerId }})">Pay</button>
                </form>
            </div>
        </div>
    </div>
    @if (session('errorTransaksi'))
        <script>
            alert("{{ session('errorTransaksi') }}");
        </script>
    @endif


</body>
<script>
    function confirmFinishOrder(customerId) {
        var confirmation = confirm("Are you sure you want to finish your order?");
        if (confirmation) {
            window.location.href = "/finish-order/" + customerId;
        }
    }
</script>

</html>
