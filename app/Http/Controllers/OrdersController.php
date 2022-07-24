<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()['id'];
        return Orders::where('uid', $userId)->get();
        // return Orders::all()->where('uid', $userId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $formField = $request->validate([
            'pid' => 'required',
        ]);

        $totalAmount = 0;

        // get the price of products of pid using whereIn
        $products = Product::whereIn('id', $formField['pid'])->get();
        foreach ($products as $product) {
            $totalAmount += $product->price;
        }


        $order = Orders::create([
            'pid' => $formField['pid'],
            'uid' => Auth::user()['id'],
            'totalAmount' => $totalAmount,
        ]);

        return response([
            'message' => 'order placed',
            'order' => $order
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $Orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $Orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $Orders
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = Auth::user()['id'];
        $conditions = array('uid' => $userId, 'id' => $id);
        return Orders::whereArray($conditions)->delete();
    }
}
