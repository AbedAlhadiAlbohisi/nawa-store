@extends('layouts.admin')
@section('content')
    <section class="content">
        <header class="mb-4 d-flex">
            <h2 class="mb-4 fs-3"> {{ $title }} </h2>
            <div class="ml-auto">
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">+ Create Product</a>
                <a href="{{ route('products.trashed') }}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i>
                    View Trashed</a>
            </div>
        </header>
        <div class="row">

            {{-- <a class="btn btn-primary m-5" href="{{route("products.create")}}" role="button">Create Proudct</a> --}}

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ URL::current() }}" method="get" class="form-inline">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control mb-2 mr-2"
                                placeholder="Search">
                            <select name="category_id" class="form-control mb-2 mr-2">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}" @selected(request('category_id')== $category->id)>{{$category->name}}</option>
                                @endforeach
                            </select>
                            <select name="status" class="form-control mb-2 mr-2">
                                <option value="">status</option>
                                <option value="active" @selected(request('status') == 'active')>active</option>
                                <option value="archived" @selected(request('status') == 'archived')>archived</option>
                                <option value="draft" @selected(request('status') == 'draft')>draft</option>
                            </select>

                            <input type="number" name="price_min" placeholder="min price"
                                class="form-control mb-2 mr-2" value="{{ request('search') }}">
                            <input type="number" name="price_max" placeholder="max price"
                                class="form-control mb-2 mr-2" value="{{ request('search') }}">
                            <button type="submit" aria-placeholder="Search" class="form-control mb-2 mr-2 btn btn-dark"
                                value="{{ request('search') }}">filtter</button>

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Category</th>
                                        {{-- <th>Description</th>
                                <th>Short Description</th> --}}
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Settings</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->index + 1 ?? '' }}</td>
                                            <td>
                                                <a href="{{ $product->image_url }}">
                                                    <img src="{{ $product->image_url }}" alt="" width="60">
                                                </a>
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->slug }}</td>
                                            <td> {{ $product->category_name }}</td>
                                            <td>{{ $product->price_formatted }}</td>
                                            <td> {{ $product->status }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('products.edit', $product->id) }}"
                                                        class="btn btn-warning mr-2">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('products.destroy', $product->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $products->links() }}
    </section>
@endsection
@section('script')
@endsection
