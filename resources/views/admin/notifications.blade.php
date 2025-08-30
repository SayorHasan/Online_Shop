@extends('layouts.admin')
@section('content')
<style>
    /* High-contrast black & white for admin notifications */
    .table { color: #111; }
    .table thead th {
        background-color: #000 !important;
        color: #fff !important;
        padding: .85rem 1rem !important;
        border-color: #000 !important;
        font-weight: 700;
        letter-spacing: .2px;
    }
    .table-bordered > :not(caption) > tr > th,
    .table-bordered > :not(caption) > tr > td { border-color: #000 !important; }
    .table tbody td { padding: .85rem 1rem !important; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #fafafa; }
    .table-striped tbody tr:nth-of-type(even) { background-color: #fff; }

    /* Normalize badges to b/w */
    .badge { border-radius: 6px; font-weight: 600; padding: .35rem .6rem; }
    .badge.bg-warning, .badge.bg-success, .badge.bg-danger, .badge.bg-secondary, .badge.bg-info, .badge.bg-primary {
        background-color: #000 !important; color: #fff !important;
    }

    .list-icon-function .item.eye i { color: #000; }
    .breadcrumbs .text-tiny, h3 { color: #000; }
</style>
<div class="main-content-inner">                            
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Notifications</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>                                                                           
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All Notifications</div>
                </li>
            </ul>
        </div>
        
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="search" tabindex="2" value="{{ request('search') }}" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>                
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Order #</th>
                                <th class="text-center">Items</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $note)
                            <tr>
                                <td class="text-center">{{ $note->created_at->format('M d, Y H:i') }}</td>
                                <td class="text-center">{{ $note->data['user_name'] ?? 'Customer' }}</td>
                                <td class="text-center">{{ $note->data['order_number'] ?? '' }}</td>
                                <td class="text-center">{{ $note->data['item_count'] ?? 0 }}</td>
                                <td class="text-center">${{ number_format($note->data['total'] ?? 0,2) }}</td>
                                <td class="text-center">
                                    @if(is_null($note->read_at))
                                        <span class="badge bg-warning">Unread</span>
                                    @else
                                        <span class="badge" style="background: #000; color: #fff; font-weight: bold;">Read</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.order.details', $note->data['order_id'] ?? 0) }}">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>                                        
                                        </div>
                                    </a>
                                    @if(is_null($note->read_at))
                                    <button class="btn btn-sm btn-outline-secondary" onclick="markRead('{{ $note->id }}')">Mark as read</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No notifications found</td>
                            </tr>
                            @endforelse                                  
                        </tbody>
                    </table>                
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">                
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markRead(id){
  fetch('{{ url('/admin/notifications') }}/'+id+'/read', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
   .then(()=> {
      // refresh both counts if present on page
      fetch('{{ route('admin.notifications.count') }}')
        .then(r=>r.json())
        .then(d=>{ 
          const notifEl = document.getElementById('notifCount'); 
          if(notifEl){ notifEl.innerText = d.count; } 
          
          const sidebarEl = document.getElementById('sidebarOrderCount');
          if(sidebarEl){ sidebarEl.innerText = d.order_count; }
        });
      location.reload();
   });
}
</script>
@endpush
@endsection


