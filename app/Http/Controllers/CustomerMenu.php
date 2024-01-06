<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\HistoryProduct;
use App\FormData;
use App\Customer;
use App\CustomerTrans;
use App\ProductTranscation;
use Darryldecode\Cart\CartCondition;
use Auth;
use DB;

//import dulu packagenya biar bs dipake
use Haruncpi\LaravelIdGenerator\IdGenerator;

class CustomerMenu extends Controller
{    
    public function showForm()
    {
        return view('Customer.Auth.FormTable');
    }
    public function processForm(Request $request)
{
    // Validate the form data
    $request->validate([
        'name' => 'required|string|max:255',
        'table_num' => 'required|integer',
    ]);

    $invoiceNumber = 'INV-' . date('Ymd') . strtoupper(str_replace(' ', '', $request->input('name'))) . mt_rand(1000, 9999);
    
    // Save form data to the database
    $formData = FormData::create([
        'name' => $request->input('name'),
        'table_num' => $request->input('table_num'),
        'invoice_number' => $invoiceNumber,
    ]);

    // Save the form data ID in the session
    $request->session()->put('form_data_id', $formData->id);

    // Redirect back with a success message
    return redirect('/kalahaMenu?form_data_id=' . $formData->id)->with('success', 'Form submitted successfully!');
}

public function getTotalQuantity($customerId)
{
    $items = \Cart::session($customerId)->getContent();

    $totalQuantity = 0;

    foreach ($items as $item) {
        $totalQuantity += $item->quantity;
    }

    return $totalQuantity;
}

public function index(Request $request)
{
    $form_data_id = $request->query('form_data_id');
    
    $formData = FormData::find($form_data_id);

    $products = Product::when(request('search'), function ($query) {
        return $query->where('name', 'like', '%' . request('search') . '%');
    })
        ->orderBy('created_at', 'desc')
        ->paginate(8);
        $totalQuantity = $this->getTotalQuantity($form_data_id);
    // Pass the form data to the view
    return view('Customer.index', compact('products', 'form_data_id','totalQuantity','formData'));
}


public function CartView($customerId){
    
        // ... rest of your code ...

        $products = Product::when(request('search'), function($query){
            return $query->where('name','like','%'.request('search').'%');
        })
        ->orderBy('created_at','desc')
        ->paginate(12);


    //cart item
    $tax = "+10%";


    $condition = new \Darryldecode\Cart\CartCondition(array(
    'name' => 'pajak',
    'type' => 'tax', //tipenya apa
    'target' => 'total', //target kondisi ini apply ke mana (total, subtotal)
    'value' => $tax, //contoh -12% or -10 or +10 etc
    'order' => 1
    ));                

    \Cart::session($customerId)->condition($condition);          

    $items = \Cart::session($customerId)->getContent();
    if(\Cart::isEmpty()){
    $cart_data = [];            
    }
    else{
    foreach($items as $row) {
    $cart[] = [
        'rowId' => $row->id,
        'name' => $row->name,
        'qty' => $row->quantity,
        'pricesingle' => $row->price,
        'price' => $row->getPriceSum(),
        'created_at' => $row->attributes['created_at'],
    ];         
    }

    $cart_data = collect($cart)->sortBy('created_at');

    }

    //total
    $sub_total = \Cart::session($customerId)->getSubTotal();
    $total = \Cart::session($customerId)->getTotal();

    $new_condition = \Cart::session($customerId)->getCondition('pajak');
    $pajak = $new_condition->getCalculatedValue($sub_total); 

    $data_total = [
    'sub_total' => $sub_total,
    'total' => $total,
    'tax' => $pajak
    ];

    return view('Customer.Cart.Addtocart', compact('cart_data','data_total', 'customerId'));
}

public function addProductCart($customerId, $id){
    $product = Product::find($id);
            
    $cart = \Cart::session($customerId)->getContent();        
    $cek_itemId = $cart->whereIn('id', $id);  
    
    if($cek_itemId->isNotEmpty()){
        if($product->qty == $cek_itemId[$id]->quantity){
            return redirect()->back()->with('error','jumlah item kurang');
        }else{
            \Cart::session($customerId)->update($id, array(
                'quantity' => 1
            ));
        }            
    }else{
        \Cart::session($customerId)->add(array(
            'id' => $id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1, 
            'attributes' => array(
                'created_at' => date('Y-m-d H:i:s')
            )          
        ));
    }       

    return redirect()->back();
}
public function increasecart($customerId, $id){
    $product = Product::find($id);     
    
    $cart = \Cart::session($customerId)->getContent();        
    $cek_itemId = $cart->whereIn('id', $id); 

    if($product->qty == $cek_itemId[$id]->quantity){
        return redirect()->back()->with('error','jumlah item kurang');
    }else{
        \Cart::session($customerId)->update($id, array(
        'quantity' => array(
            'relative' => true,
            'value' => 1
        )));

        return redirect()->back();
    }        
}
public function decreasecart($customerId, $id){
    $product = Product::find($id);      
            
    $cart = \Cart::session($customerId)->getContent();        
    $cek_itemId = $cart->whereIn('id', $id); 

    if($cek_itemId[$id]->quantity == 1){
        \Cart::session($customerId)->remove($id);  
    }else{
        \Cart::session($customerId)->update($id, array(
        'quantity' => array(
            'relative' => true,
            'value' => -1
        )));            
    }
    return redirect()->back();

}
public function bayar($customerId)
{
    $cart_total = \Cart::session($customerId)->getTotal();
    $bayar = request()->bayar;
    // Validate if cart total is zero
    if ($cart_total == 0.0) {
        return redirect()->back()->with('errorTransaksi', 'Cart cannot be empty');
    }

    DB::beginTransaction();

    try {
        $all_cart = \Cart::session($customerId)->getContent();

        $filterCart = $all_cart->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity
            ];
        });

        foreach ($filterCart as $cart) {
            $product = Product::find($cart['id']);

            if ($product->qty == 0) {
                return redirect()->back()->with('errorTransaksi', 'Jumlah pembayaran tidak valid');
            }

            HistoryProduct::create([
                'product_id' => $cart['id'],
                'customer_id' => $customerId,
                'qty' => $product->qty,
                'qtyChange' => -$cart['quantity'],
                'tipe' => 'decrease from transaction'
            ]);

            $product->decrement('qty', $cart['quantity']);
        }

        $id = IdGenerator::generate(['table' => 'customerTransaction', 'length' => 10, 'prefix' => 'INV-', 'field' => 'invoices_number']);

        foreach ($filterCart as $cart){
            CustomerTrans::create([
                'invoices_number' => $id,
                'customer_id' => $customerId,
                'product_id' => $cart['id'],
                'pay' => $bayar,
                'total' => $cart_total
            ]);
        }


        foreach ($filterCart as $cart) {
            ProductTranscation::create([
                'product_id' => $cart['id'],
                'invoices_number' => $id,
                'qty' => $cart['quantity'],
            ]);
        }

        \Cart::session($customerId)->clear();

        DB::commit();
        return redirect()->back()->with('success', 'Transaksi Berhasil dilakukan Tahu Coding | Klik History untuk print');
    } catch (\Exception $e) {
        DB::rollback();
        dd('Error: ' . $e->getMessage());
    }
}


public function history(Request $request)
{
    $query = CustomerTrans::query();

    // Join with the customers table on customer_id
    $query->join('customer', 'customertransaction.customer_id', '=', 'customer.id');
    $query->join('products', 'customertransaction.product_id', '=', 'products.id');

    // Select the columns you need from both tables
    $query->select('customertransaction.*', 'customer.name as customer_name', 'customer.table_num','products.name as product_name');


    // Add your date filters
    if ($request->filled('start_date')) {
        $start_date = $request->input('start_date');
        $query->whereDate('customertransaction.created_at', '>=', $start_date);
    }

    if ($request->filled('end_date')) {
        $end_date = $request->input('end_date');
        $query->whereDate('customertransaction.created_at', '<=', $end_date);
    }

    if (!$request->filled('start_date') && !$request->filled('end_date')) {
        $defaultStartDate = now()->subDays(30)->format('Y-m-d');
        $query->whereDate('customertransaction.created_at', '>=', $defaultStartDate);
    }

    // Order by transaction's created_at column
    $query->orderBy('customertransaction.created_at', 'desc');

    // Paginate the results
    $history = $query->paginate(10);

    return view('pos.customer_history', compact('history'));
}

public function laporan($id){
    $transaksi = CustomerTrans::with('product_transation')->where('invoices_number', $id)->first();
    // dd($transaksi);
    return view('laporan.transaksi',compact('transaksi'));
}

}
