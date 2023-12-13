<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Responsive Food Menu</title>
</head>

<body>
    @if (isset($form_data_id))
        <p>Form Data ID: {{ $form_data_id }}</p>
    @else
        <p>No Form Data ID available.</p>
    @endif

    <div class="menu-container">
        @foreach ($products as $product)
            @if ($product->qty < 1)
                <div class="menu-item">
                    <img class="card-img-top gambar" src="{{ $product->image }}" alt="Card image cap">
                    <h2>{{ $product->name }}</h2>
                    <p>{{ $product->description }}</p>
                    <span>Out Of stock</span>
                </div>
            @else
                <div class="menu-item">
                    <form
                        action="{{ route('add.to.cart', ['customer_id' => $form_data_id, 'product_id' => $product->id]) }}"
                        method="post">
                        @csrf
                        <img class="card-img-top gambar" src="{{ $product->image }}" alt="Card image cap"
                            style="cursor: pointer" onclick="this.closest('form').submit();return false;">
                        <button type="submit" class="btn btn-primary btn-sm cart-btn"><i
                                class="fas fa-cart-plus"></i></button>
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->description }}</p>
                        <span> IDR {{ number_format($product->price, 2, ',', '.') }}</span>
                    </form>
            @endif
        @endforeach
        <!-- Add more menu items as needed -->
    </div>
    <!-- View Cart Button -->

    <div class="view-cart-container">
        <a href="{{ url("/kalahaMenu/cart/{$form_data_id}") }}" class="view-cart-btn">
            <img src="{{ asset('img/cart.png') }}" alt="Cart Icon" class="cart-icon">
        </a>
    </div>


</body>

</html>


<style>
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
    }

    .menu-item img {
        max-width: 100%;
        height: auto;
    }

    .menu-item h2 {
        margin-top: 10px;
        font-size: 1.5em;
    }

    .menu-item p {
        color: #555;
    }

    .menu-item span {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #e44d26;
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
        /* Adjust the size of the circular button */
        height: 50px;
        /* Adjust the size of the circular button */
        border-radius: 50%;
        /* Create a circular shape */
        background-color: #3498db;
        /* Button background color */
        color: #fff;
        /* Button text color */
        text-decoration: none;
        line-height: 50px;
        /* Center the text vertically */
    }

    .cart-icon {
        width: 30px;
        /* Adjust the size of the cart icon */
        height: 30px;
        /* Adjust the size of the cart icon */
        vertical-align: middle;
        /* Center the icon vertically */
    }

    /* Media query for responsive design */
    @media (max-width: 768px) {
        .menu-item {
            width: 100%;
        }
    }
</style>
