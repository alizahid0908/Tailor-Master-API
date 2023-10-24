<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Retrieve the number of registered customers
        $registeredCustomersCount = Customer::count();

        // Retrieve the total number of orders
        $totalOrdersCount = Order::count();

        // Retrieve the number of pending orders (you need to define the logic for pending orders)
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // Retrieve the number of delivered orders (you need to define the logic for delivered orders)
        $deliveredOrdersCount = Order::where('status', 'delivered')->count();

        // Pass the data to the view
        return view('home', compact(
            'registeredCustomersCount',
            'totalOrdersCount',
            'pendingOrdersCount',
            'deliveredOrdersCount'
        ));
    }

    
}
