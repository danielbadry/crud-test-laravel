<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Customers",
 *     description="API Endpoints for Customers"
 * )
 *
 * @OA\Schema(
 *     schema="Customer",
 *     title="Customer",
 *     required={"firstname", "lastname", "date_of_birth", "phone_number", "email", "bank_account_number"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="The unique identifier for the customer"
 *     ),
 *     @OA\Property(
 *         property="firstname",
 *         type="string",
 *         example="John",
 *         description="The firstname of the customer"
 *     ),
 *     @OA\Property(
 *         property="lastname",
 *         type="string",
 *         example="Doe",
 *         description="The lastname of the customer"
 *     ),
 *     @OA\Property(
 *         property="date_of_birth",
 *         type="string",
 *         format="date",
 *         example="1990-01-01",
 *         description="The date of birth of the customer"
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         example="1234567890",
 *         description="The phone number of the customer"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="john.doe@example.com",
 *         description="The email address of the customer"
 *     ),
 *     @OA\Property(
 *         property="bank_account_number",
 *         type="string",
 *         example="123456789",
 *         description="The bank account number of the customer"
 *     )
 * )

 */
class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/customers",
     *     summary="Get all customers",
     *     description="Retrieve a list of all customers.",
     *     tags={"Customers"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
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
        return view('customers.create');
    }

    /**
     * @OA\Post(
     *     path="/customers",
     *     summary="Create a new customer",
     *     description="Create a new customer record.",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "firstname": {"The firstname field is required."},
     *                     "email": {"The email field is required.", "The email must be a valid email address."}
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Customer::$rules);

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
     * @OA\Put(
     *     path="/customers/{id}",
     *     summary="Update a customer",
     *     description="Update an existing customer record.",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the customer to update",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Customer not found"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|unique:customers,phone_number,' . $customer->id,
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'bank_account_number' => 'required|string|unique:customers,bank_account_number,' . $customer->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Validate phone number using libphonenumber
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($request->phone_number, "US");
            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                return response()->json(['error' => 'Invalid phone number'], 400);
            }
        } catch (\libphonenumber\NumberParseException $e) {
            return response()->json(['error' => 'Invalid phone number format'], 400);
        }

        $customer->update($request->all());
        return response()->json($customer, 200);
    }

    /**
     * @OA\Delete(
     *     path="/customers/{id}",
     *     summary="Delete a customer",
     *     description="Delete an existing customer record.",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the customer to delete",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Customer not found"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json(null, 204);
    }
}
