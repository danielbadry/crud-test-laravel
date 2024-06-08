<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    /**
     * Test creating a new customer.
     *
     * @return void
     */
    public function testCreateCustomer()
    {
        // Create a new customer
        $customer = Customer::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'email' => 'john@example.com',
            'bank_account_number' => '12345678901234'
        ]);

        // Assert that the customer was created successfully
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('John', $customer->firstname);
        $this->assertEquals('Doe', $customer->lastname);
        $this->assertEquals('1990-01-01', $customer->date_of_birth);
        $this->assertEquals('1234567890', $customer->phone_number);
        $this->assertEquals('john@example.com', $customer->email);
        $this->assertEquals('12345678901234', $customer->bank_account_number);
    }

    /**
     * Test uniqueness of customer email.
     *
     * @return void
     */
    public function testUniqueEmail()
    {
        // Attempt to create a customer with an existing email address
        $customer1 = Customer::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'email' => 'john@example.com',
            'bank_account_number' => '12345678901234'
        ]);

        // Attempt to create another customer with the same email address
        $customer2 = Customer::create([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'date_of_birth' => '1995-01-01',
            'phone_number' => '9876543210',
            'email' => 'john@example.com', // Same email as customer1
            'bank_account_number' => '54321098765432'
        ]);

        // Assert that the second customer was not created due to email uniqueness constraint
        $this->assertNull($customer2);
    }
}