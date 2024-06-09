@extends('layouts.app')

@section('content')
    <h1>Add Customer</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="firstname">Firstname:</label>
            <input type="text" name="firstname" id="firstname" class="form-control" value="{{ old('firstname') }}">
        </div>
        <!-- Repeat similar fields for lastname, date_of_birth, phone_number, email, bank_account_number -->

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
