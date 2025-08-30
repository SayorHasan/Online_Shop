@extends('layouts.app')
@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="container">
    <h2 class="page-title">My Wishlist</h2>
    <div class="row">
      @forelse($products as $product)
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height:200px;object-fit:cover;" />
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">{{ $product->name }}</h6>
            <p class="text-muted mb-2">{{ $product->category->name ?? '' }}</p>
            <div class="mt-auto d-grid gap-2">
              <a class="btn btn-outline-primary" href="{{ route('user.product.details', $product->id) }}">View</a>
              <button class="btn btn-dark" onclick="buyNow({{ $product->id }})">Buy Now</button>
              <button class="btn btn-outline-danger" onclick="toggleWishlist({{ $product->id }}, this)">Remove</button>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12"><p class="text-muted">No items in wishlist.</p></div>
      @endforelse
    </div>
    {{ $products->links() }}
  </section>
</main>

@push('scripts')
<script>
function toggleWishlist(productId, btn){
  fetch('{{ route('wishlist.toggle') }}',{
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body:JSON.stringify({product_id:productId})
  }).then(r=>r.json()).then(data=>{ if(data.success){ location.reload(); } });
}

function buyNow(productId){
  fetch('{{ route('cart.add') }}',{
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body:JSON.stringify({product_id:productId, quantity:1})
  }).then(r=>r.json()).then(data=>{
    if(data.success){ window.location = '{{ route('user.cart') }}'; }
  });
}
</script>
@endpush
@endsection


