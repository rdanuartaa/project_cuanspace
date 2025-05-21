<!-- resources/views/admin/sellers/_table_body.blade.php -->

@foreach ($sellers as $seller)
    <tr>
        <td>{{ $seller->user->name }}</td>
        <td>{{ $seller->contact_email }}</td>
        <td>{{ $seller->brand_name }}</td>
        <td>
            <span class="badge
                {{ $seller->status == 'pending' ? 'badge-opacity-warning' :
                   ($seller->status == 'active' ? 'badge-opacity-success' : 'badge-opacity-danger') }}">
                {{ ucfirst($seller->status) }}
            </span>
        </td>
        <td>
            @if ($seller->status == 'pending')
                <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Verify</button>
                </form>
                <form action="{{ route('admin.sellers.deactivate', $seller->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                </form>
            @elseif ($seller->status == 'active')
                <form action="{{ route('admin.sellers.deactivate', $seller->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                </form>
                <form action="{{ route('admin.sellers.setPending', $seller->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Set Pending</button>
                </form>
            @elseif ($seller->status == 'inactive')
                <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Activate</button>
                </form>
                <form action="{{ route('admin.sellers.setPending', $seller->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Set Pending</button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
