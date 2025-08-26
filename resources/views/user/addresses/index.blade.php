@extends('layouts.app')

@section('content')
<style>
    .addresses-page {
        padding: 2rem 0;
    }
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 2rem;
        text-align: center;
    }
    .address-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .address-card.default {
        border-color: #28a745;
        background: #f8fff9;
    }
    .address-card.default::before {
        content: 'Default Address';
        background: #28a745;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    .address-card h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1rem;
    }
    .address-details p {
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 1rem;
    }
    .address-actions {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f3f4;
    }
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background: #212529;
        border-color: #212529;
        color: white;
    }
    .btn-primary:hover {
        background: #000;
        border-color: #000;
        color: white;
    }
    .btn-warning {
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    .btn-warning:hover {
        background: #e0a800;
        border-color: #e0a800;
        color: #212529;
    }
    .btn-danger {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background: #c82333;
        border-color: #c82333;
        color: white;
    }
    .btn-success {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }
    .btn-success:hover {
        background: #218838;
        border-color: #218838;
        color: white;
    }
    .empty-addresses {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    .empty-addresses i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
    .empty-addresses h5 {
        color: #212529;
        margin-bottom: 1rem;
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <section class="addresses-page container">
        <h2 class="page-title">My Addresses</h2>
        
        <div class="text-end mb-4">
            <a href="{{ route('user.address.create') }}" class="btn btn-primary">Add New Address</a>
        </div>
        
        @if($addresses->count() > 0)
            @foreach($addresses as $address)
                <div class="address-card {{ $address->is_default ? 'default' : '' }}" style="position: relative;">
                    <h4>{{ $address->name }}</h4>
                    <div class="address-details">
                        <p><strong>Phone:</strong> {{ $address->phone }}</p>
                        <p><strong>Address:</strong> {{ $address->address }}</p>
                        <p><strong>Locality:</strong> {{ $address->locality }}</p>
                        <p><strong>City:</strong> {{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                        <p><strong>Landmark:</strong> {{ $address->landmark }}</p>
                        <p><strong>Country:</strong> {{ $address->country }}</p>
                    </div>
                    
                    <div class="address-actions">
                        @if(!$address->is_default)
                            <form method="POST" action="{{ route('user.address.set-default', $address->id) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Set as Default</button>
                            </form>
                        @endif
                        
                        <a href="{{ route('user.address.edit', $address->id) }}" class="btn btn-warning">Edit</a>
                        
                        @if($addresses->count() > 1)
                            <form method="POST" action="{{ route('user.address.destroy', $address->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-addresses">
                <i class="icon-map-pin"></i>
                <h5>No addresses found</h5>
                <p>You haven't added any shipping addresses yet.</p>
                <a href="{{ route('user.address.create') }}" class="btn btn-primary">Add Your First Address</a>
            </div>
        @endif
    </section>
</main>
@endsection
