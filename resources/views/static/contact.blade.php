@extends('layouts.app')
@section('content')
<main class="container py-5">
  <h1 class="mb-3">Contact Us</h1>
  <p class="text-secondary">Have a question? Reach out and we'll get back to you.</p>
  <div class="row g-3">
    <div class="col-md-6">
      <form method="post" action="#">
        @csrf
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <textarea class="form-control" rows="5" required></textarea>
        </div>
        <button class="btn btn-dark" type="submit">Send</button>
      </form>
    </div>
    <div class="col-md-6">
      <div class="p-3 border rounded h-100">
        <h6 class="mb-2">Support</h6>
        <p class="mb-1">Email: support@example.com</p>
        <p class="mb-0">Phone: +1 (000) 000-0000</p>
      </div>
    </div>
  </div>
</main>
@endsection


