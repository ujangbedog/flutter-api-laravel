<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()['id'];
        // return Wishlist::where('userId', $userId);
        return Wishlist::all()->where('userId', $userId);
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
            'productId' => 'required|integer',
        ]);

        if (Auth::check()) {

            Wishlist::create([
                'productId' => $formField['productId'],
                'userId' =>   Auth::user()['id'],
            ]);
            return response([
                'message' => 'created'
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Wishlist::find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = Auth::user()['id'];
        $conditions = array('userId' => $userId, 'productId' => $id);
        return Wishlist::whereArray($conditions)->delete();
    }
}
