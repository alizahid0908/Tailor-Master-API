<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
       $search = $request->input('search', '');
       $userId = Auth::user()->id;
    
       $customers = Customer::query();
    
       if (!empty($search)) {
           $customers->where(function ($query) use ($search) {
               $query->where('id', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
           });
       }
    
       $customers->where('user_id', $userId);   
    
       $customers = $customers->paginate(10);
    
       return response()->json([
        'customers' => $customers,
        'total_pages' => $customers->lastPage(),
        ]);
    }    

    public function show($id)
    {


        $customer = Customer::find($id);
    
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        
        if (Auth::user()->id !== $customer->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($customer);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:11|min:11',
        ]);
        dd($data);
        $customer = Customer::create($data);
        $customer->user_id = $user->id;
        $customer->save();

        return response()->json(['message' => 'Customer registered successfully', 'data' => $customer], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:11|min:11,'
        ]);

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->user_id = $user->id;
        $customer->update($validatedData);
        $customer->save();

        return response()->json(['message' => 'Customer updated successfully']);
    }

    public function delete(Request $request, $id)
    {


        $customer = Customer::find($id);

        if (Auth::user()->id !== $customer->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }

}
