<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|unique:customers',
            'email' => 'required|email|unique:customers',
            'bank_account_number' => 'required|string|unique:customers',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Validate phone number using libphonenumber
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($request->phone_number, "US"); // Adjust the country code as needed
            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                return response()->json(['error' => 'Invalid phone number'], 400);
            }
        } catch (\libphonenumber\NumberParseException $e) {
            return response()->json(['error' => 'Invalid phone number format'], 400);
        }

        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
