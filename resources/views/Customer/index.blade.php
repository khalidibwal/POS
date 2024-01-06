<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Responsive Food Menu</title>
</head>

<body>
    <div class="header">
        @if (isset($form_data_id))
            <p>Welcome, {{ $formData->name }} || Table Number : {{ $formData->table_num }}</p>
        @else
            <p>No Data ID available.</p>
        @endif
    </div>

    <div class="menu-container">
        @foreach ($products as $product)
            <div class="menu-item">
                <img class="card-img-top gambar" src="{{ $product->image }}" alt="Card image cap">
                <form
                    action="{{ route('add.to.cart', ['customer_id' => $form_data_id, 'product_id' => $product->id]) }}"
                    method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm cart-btn">Order here</button>
                </form>
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                @if ($product->qty < 1)
                    <span class="out-of-stock">Out Of stock</span>
                @else
                    <span class="price">IDR {{ number_format($product->price, 2, ',', '.') }}</span>
                @endif
            </div>
        @endforeach
    </div>

    <!-- View Cart Button -->
    <div class="view-cart-container">
        <a href="{{ url("/kalahaMenu/cart/{$form_data_id}") }}" class="view-cart-btn">
            <img src="{{ asset('img/cart.png') }}" alt="Cart Icon" class="cart-icon">
            @if($totalQuantity > 0)
            <span class="total-quantity">{{ $totalQuantity }}</span>
        @else
            <!-- If $totalQuantity is 0 or less, don't display anything -->
        @endif
        </a>
    </div>
</body>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        color: #fff;
    }

    .menu-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 20px;
    }

    .menu-item {
        width: 300px;
        margin: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        text-align: center;
        border-radius: 15px;
    }
    .btn {
        background: linear-gradient(to bottom, #3498db 0%, #2980b9 100%);
        /* Gradient background */
        border: none; /* Remove borders */
        color: white; /* White text */
        padding: 10px 20px; /* Some padding */
        font-size: 16px; /* Set a font size */
        cursor: pointer; /* Mouse pointer on hover */
        border-radius: 5px;
    }

    .btn:hover {
        background: linear-gradient(to bottom, #2980b9 0%, #3498db 100%);
        /* Darker gradient background on hover */
    }

    .menu-item img {
        max-width: 100%;
        height: 150px; 
    }

    .menu-item h2 {
        margin-top: 10px;
        font-size: 1.5em;
    }

    .menu-item p {
        color: #000000;
    }

    .out-of-stock {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #e44d26;
    }

    .price {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #333; /* Adjust color as needed */
    }

    .view-cart-container {
        position: fixed;
        top: 20px;
        right: 20px;
        text-align: center;
    }

    .view-cart-btn {
        display: inline-block;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #3498db;
        color: #000000;
        text-decoration: none;
        line-height: 50px;
    }

    .cart-icon {
        width: 30px;
        height: 30px;
        vertical-align: middle;
    }
    .total-quantity {
        position: absolute;
        top: 5px;
        right: 5px;
        color: #fa0a0a;
        border-radius: 50%;
        padding: 5px;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .menu-item {
            width: 100%;
        }
    }
</style>

</html>
