<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Size;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class SizeController extends Controller
{

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'size_name' => 'required',
            'collar_size' => 'required',
            'chest_size' => 'required',
            'sleeve_length' => 'required',
            'cuff_size' => 'required',
            'shoulder_size' => 'required',
            'waist_size' => 'required',
            'shirt_length' => 'required',
            'legs_length' => 'required',
            'description' => 'required',
            'category' => 'required|in:shirt_pant,kurta,blazer,kameez_shalwar',
        ], [
            'customer_id.exists' => 'The selected customer does not exist.',
        ]);
    
        $size = new Size([
            'customer_id' => $request->input('customer_id'),
            'size_name' => $request->input('size_name'),
            'collar_size' => $request->input('collar_size'),
            'chest_size' => $request->input('chest_size'),
            'sleeve_length' => $request->input('sleeve_length'),
            'cuff_size' => $request->input('cuff_size'),
            'shoulder_size' => $request->input('shoulder_size'),
            'waist_size' => $request->input('waist_size'),
            'shirt_length' => $request->input('shirt_length'),
            'legs_length' => $request->input('legs_length'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
        ]);

        $size->user_id = $user->id;
        $size->save();
    
        return response()->json(['message' => 'Size record created successfully', 'data' => $size], 201);
    }
    

    public function index()
    {

        $sizes = Size::where('user_id', Auth::user()->id)->paginate(10);
        return response()->json(['sizes' => $sizes]);
    }


    public function show($id)
    {
        $size = Size::find($id);

        if (Auth::user()->id !== $size->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        if (!$size) {
            return response()->json(['message' => 'Size not found'], 404);
        }

        return response()->json(['data' => $size]);
    }
    

    public function update(Request $request, $id)
    {

        $user = Auth::user();

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'size_name' => 'required',
            'collar_size' => 'required',
            'chest_size' => 'required',
            'sleeve_length' => 'required',
            'cuff_size' => 'required',
            'shoulder_size' => 'required',
            'waist_size' => 'required',
            'shirt_length' => 'required',
            'legs_length' => 'required',
            'description' => 'required',
            'category' => 'required',
        ], [
            'customer_id.exists' => 'The selected customer does not exist.',
        ]);
    
        $size = Size::find($id);
    
        if (!$size) {
            return response()->json(['message' => 'Size not found'], 404);
        }
        
        
        
        $size->fill($request->all());
    
        $size->user_id = $user->id;
        
        $size->save();

        return response()->json(['message' => 'Size updated successfully']);
    }
    

    public function delete($id)
    {
        $user = Auth::user();
        
        if (Auth::user()->id !== $size->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $size = Size::find($id);
    
        if (!$size) {
            return response()->json(['message' => 'Size not found'], 404);
        }

        $size->delete();

        return response()->json(['message' => 'Size deleted successfully']);
    }
    
    public function getByCustomer($customer_id)
    {
        $user = Auth::user();

        $customer = Customer::find($customer_id);
    
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        if ($user->id !== $customer->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $sizes = Size::where('customer_id', $customer_id)->get();
    
        return response()->json(['data' => $sizes]);
    }
    
}
