@extends('layouts.admin')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between border-bottom">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
        </li>
        <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">More</a>
        </li>
    </ul>
    <div>
        <div class="btn-wrapper">
            <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
            <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
        </div>
    </div>
</div>
<div class="tab-content tab-content-basic">
    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
        <div class="row">
            <div class="col-sm-12">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                    <div>
                        <p class="statistics-title">Bounce Rate</p>
                        <h3 class="rate-percentage">32.53%</h3>
                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                    </div>
                    <div>
                        <p class="statistics-title">Page Views</p>
                        <h3 class="rate-percentage">7,682</h3>
                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                    </div>
                    <div>
                        <p class="statistics-title">New Sessions</p>
                        <h3 class="rate-percentage">68.8</h3>
                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                    </div>
                    <div class="d-none d-md-block">
                        <p class="statistics-title">Avg. Time on Site</p>
                        <h3 class="rate-percentage">2m:35s</h3>
                        <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                    </div>
                    <div class="d-none d-md-block">
                        <p class="statistics-title">New Sessions</p>
                        <h3 class="rate-percentage">68.8</h3>
                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                    </div>
                    <div class="d-none d-md-block">
                        <p class="statistics-title">Avg. Time on Site</p>
                        <h3 class="rate-percentage">2m:35s</h3>
                        <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 d-flex flex-column">
                <div class="row flex-grow">
                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title card-title-dash">Statistik Cuan Space</h4>
                                        <h5 class="card-subtitle card-subtitle-dash">Lorem Ipsum is simply dummy text of the printing</h5>
                                    </div>
                                    <div id="performance-line-legend"></div>
                                </div>
                                <div class="chartjs-wrapper mt-5">
                                    <canvas id="performaneLine"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column">
                <div class="row flex-grow">
                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                        <div class="card bg-primary card-rounded">
                            <div class="card-body pb-0">
                                <h4 class="card-title card-title-dash text-white mb-4">Status Summary</h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="status-summary-ight-white mb-1">Closed Value</p>
                                        <h2 class="text-info">357</h2>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="status-summary-chart-wrapper pb-4">
                                            <canvas id="status-summary"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center mb-2 mb-sm-0">
                                            <div class="circle-progress-width">
                                                <div id="totalVisitors" class="progressbar-js-circle pr-2"></div>
                                            </div>
                                            <div>
                                                <p class="text-small mb-2">Total Visitors</p>
                                                <h4 class="mb-0 fw-bold">26.80%</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="circle-progress-width">
                                                <div id="visitperday" class="progressbar-js-circle pr-2"></div>
                                            </div>
                                            <div>
                                                <p class="text-small mb-2">Visits per day</p>
                                                <h4 class="mb-0 fw-bold">9065</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 d-flex flex-column">
                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title card-title-dash">Seller Management</h4>
                                        <p class="card-subtitle card-subtitle-dash">Manage all seller requests</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.sellers.index') }}" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button"><i class="mdi mdi-account-plus"></i>View All Sellers</a>
                                    </div>
                                </div>
                                <div class="table-responsive mt-1">
                                    <table class="table select-table">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Brand</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sellers as $seller)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex">
                                                            <img src="{{ Storage::url($seller->profile_image) }}" alt="profile" class="img-sm rounded-10">
                                                            <div>
                                                                <h6>{{ $seller->user->name }}</h6>
                                                                <p>{{ $seller->contact_email }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6>{{ $seller->brand_name }}</h6>
                                                    </td>
                                                    <td>
                                                        <div class="badge
                                                            {{ $seller->status == 'pending' ? 'badge-opacity-warning' :
                                                               ($seller->status == 'active' ? 'badge-opacity-success' : 'badge-opacity-danger') }}">
                                                            {{ ucfirst($seller->status) }}
                                                        </div>
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column">
                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h4 class="card-title card-title-dash">Top Seller</h4>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            @foreach ($topSellers as $topSeller)
                                                <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                    <div class="d-flex">
                                                        <img class="img-sm rounded-10" src="{{ Storage::url($topSeller->profile_image) }}" alt="profile">
                                                        <div class="wrapper ms-3">
                                                            <p class="ms-1 mb-1 fw-bold">{{ $topSeller->user->name }}</p>
                                                            <small class="text-muted mb-0">{{ $topSeller->brand_name }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="text-muted text-small">
                                                        {{ $topSeller->updated_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
