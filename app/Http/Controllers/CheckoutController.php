<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use Symfony\Component\Intl\Countries;

class CheckoutController extends Controller
{
    //
    public function create()
    {
        $countries = Countries::getNames('en');
        return view('shop.checkout', [
            'countries' => $countries,
        ]);
    }
    // public function store(Request $request)
    // {

    //     $validated = $request->validate([

    //         'customer_first_name' => 'required',
    //         'customer_last_name' => 'required',
    //         'customer_email' => 'required',
    //         'customer_phone' => 'nullable',
    //         'customer_address' => 'required',
    //         'customer_city' => 'required',
    //         'customer_postal_code' => 'nullable',
    //         'customer_province' => 'nullable',
    //         'customer_country_code' => 'required|string|size:2',
    //     ]);
    //     $validated['user_id'] = Auth::id();
    //     $validated['status'] = 'pending';
    //     $validated['payment_status'] = 'pending';
    //     $validated['currency'] = 'EUR';

    //     $cookie_id = $request->cookie('cart_id');
    //     $cart = Cart::with('product')->where('cookie_id', '=', $cookie_id)->get(); //collection

    //     $total = $cart->sum(function ($item) {
    //         return $item->product->price * $item->quantity;
    //     });

    //     $validated['total'] = $total;

    //     DB::beginTransaction();


    //     // create order
    //     $order = Order::create($validated);
    //     // Insert order lines
    //     foreach ($cart as $item) {
    //         $data = new OrderLine();
    //         $data->order_id =  $order->id;
    //         $data->price =  $item->product->price;
    //         $data->product_id =  $order->id;
    //         $data->quantity =  $item->quantity;
    //         $data->product_name =  $item->product->name;
    //         $idSaved = $data->save();
    //         if ($idSaved) {
    //             return redirect()->route('checkout.success');
    //         }
    //     }
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_email' => 'required',
            'customer_phone' => 'nullable',
            'customer_address' => 'required',
            'customer_city' => 'required',
            'customer_postal_code' => 'nullable',
            'customer_province' => 'nullable',
            'customer_country_code' => 'required|string|size:2',
        ]);

        $validated['user-id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'pending';
        $validated['currency'] = 'ILS';

        $cookie_id = $request->cookie('cart_id');
        $cart = Cart::with('product')->where('cookie_id', '=', $cookie_id)->get();

        $total = $cart->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $validated['total'] = $total;

        DB::beginTransaction();

        try {
            // create order
            $order = Order::create($validated);

            // insert order line
            foreach ($cart as $item) {
                OrderLine::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'product_name' => $item->product->name,
                ]);
            }

            // delete cart items
            // Cart::where('cookie_id', '=', $cookie_id)->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()
            ->withInput()
            ->with('error', $e->getMessage());
        }
        // send notification to admin!
        $user= User::where('type','=','super-admin')->first();
        $user->notify(new NewOrderNotification  ($order));

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('shop.success');
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return back()
            ->with('success', "Cart Has Been Deleted Successfully");
    }
}
