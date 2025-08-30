@extends('layouts.app')
@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="my-account container">
    <h2 class="page-title">Account Details</h2>
    <div class="row">
      <div class="col-lg-3">
        @include('user.account-nav')
      </div>
      <div class="col-lg-9">
        <div class="page-content">
          @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row g-4">
            <div class="col-md-6">
              <div class="card p-3">
                <h5 class="mb-3">Profile</h5>
                <form method="POST" action="{{ route('user.account.update') }}">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" class="form-control" name="mobile" value="{{ old('mobile', $user->mobile) }}">
                  </div>
                  <button class="btn btn-dark" type="submit">Save Changes</button>
                </form>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card p-3">
                <h5 class="mb-3">Change Password</h5>
                <form method="POST" action="{{ route('user.account.password') }}">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" name="current_password" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="password" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                  </div>
                  <button class="btn btn-dark" type="submit">Update Password</button>
                </form>
              </div>
            </div>
          </div>

          <div class="card p-3 mt-4">
            <h5 class="mb-3">Saved Addresses</h5>
            <div class="mb-3">
              <a class="btn btn-outline-dark" href="{{ route('user.address.create') }}" onclick="event.preventDefault(); window.location='{{ route('user.address.create') }}'">Add New Address</a>
              <a class="btn btn-link" href="{{ route('user.addresses') }}">Manage All</a>
            </div>
            <div class="row">
              @forelse($addresses as $address)
              <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                  @if($address->is_default)
                    <span class="badge bg-primary mb-2">Default</span>
                  @endif
                  <div>{{ $address->name }}</div>
                  <div>{{ $address->address_line1 }}</div>
                  @if($address->address_line2)
                    <div>{{ $address->address_line2 }}</div>
                  @endif
                  <div>{{ $address->city }}, {{ $address->state }} {{ $address->zip }}</div>
                  <div>{{ $address->country }}</div>
                  <div>Phone: {{ $address->phone }}</div>
                </div>
              </div>
              @empty
              <div class="col-12"><p class="text-muted">No saved addresses yet.</p></div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection


