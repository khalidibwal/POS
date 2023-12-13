<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\FormData;
use Darryldecode\Cart\CartCondition;
use Auth;

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

public function index(Request $request)
{
    $form_data_id = $request->query('form_data_id');
    
    // Rest of your code...

    $products = Product::when(request('search'), function ($query) {
        return $query->where('name', 'like', '%' . request('search') . '%');
    })
        ->orderBy('created_at', 'desc')
        ->paginate(8);

    // Pass the form data to the view
    return view('Customer.index', compact('products', 'form_data_id'));
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

}
