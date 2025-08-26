@extends('layouts.app')

@section('content')
<style>
    .address-form-page {
        padding: 2rem 0;
    }
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 2rem;
        text-align: center;
    }
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    .form-container h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .form-floating {
        margin-bottom: 1rem;
    }
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
    }
    .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .btn-primary {
        background: #212529;
        border: 2px solid #212529;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: #000;
        border-color: #000;
        transform: translateY(-1px);
    }
    .btn-secondary {
        background: #6c757d;
        border: 2px solid #6c757d;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-right: 1rem;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
        color: white;
        transform: translateY(-1px);
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <section class="address-form-page container">
        <h2 class="page-title">Add New Address</h2>
        
        <div class="form-container">
            <h3>Shipping Address Information</h3>
            
            <form method="POST" action="{{ route('user.address.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" id="name" value="{{ old('name') }}" required>
                            <label for="name">Full Name *</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" id="phone" value="{{ old('phone') }}" required>
                            <label for="phone">Phone Number *</label>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('zip') is-invalid @enderror" 
                                   name="zip" id="zip" value="{{ old('zip') }}" required>
                            <label for="zip">Pincode *</label>
                            @error('zip')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>                        
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                   name="state" id="state" value="{{ old('state') }}" required>
                            <label for="state">State *</label>
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>                            
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                   name="city" id="city" value="{{ old('city') }}" required>
                            <label for="city">Town / City *</label>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   name="address" id="address" value="{{ old('address') }}" required>
                            <label for="address">House no, Building Name *</label>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('locality') is-invalid @enderror" 
                                   name="locality" id="locality" value="{{ old('locality') }}" required>
                            <label for="locality">Road Name, Area, Colony *</label>
                            @error('locality')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>    
                    </div>    
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('landmark') is-invalid @enderror" 
                                   name="landmark" id="landmark" value="{{ old('landmark') }}" required>
                            <label for="landmark">Landmark *</label>
                            @error('landmark')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>                                         
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('user.addresses') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
