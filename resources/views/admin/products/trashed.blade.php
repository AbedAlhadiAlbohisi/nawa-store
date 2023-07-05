@extends('layouts.admin')
@section('content')
<section class="content">
    <header class="mb-4 d-flex">
        <h2 class="mb-4 fs-3"> {{ $title }} </h2>
        <div class="ml-auto">
            <a href="{{route("products.index")}}" class="btn btn-sm btn-primary">Products List</a>
        </div>
    </header>
    <div class="row">

        {{-- <a class="btn btn-primary m-5" href="{{route("products.create")}}" role="button">Create Proudct</a> --}}

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                </div>
                   <!-- /.card-header -->
                   <div class="card-body">
                    @if (session()->has('success'))
                     <div class="alert alert-success">
                        {{session('success')}}
                     </div>
                    @endif
                     <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Deleted_at</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $products as $product )
                             <tr>
                                 <td>{{$loop->index + 1 ?? ''}}</td>
                                 <td>
                                     <a href="{{ $product->image_url }}">
                                        <img src="{{ $product->image_url }}" alt="" width="60">
                                     </a>
                                 </td>
                                 <td>{{$product->name}}</td>

                                 <td>{{ $product->deleted_at }}</td>


                                 <td>
                                        <div class="btn-group">
                                            <form method="POST" action="{{route('products.restore',$product->id)}}">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-trash-restore"></i>Restore</button>
                                            </form>
                                            <form method="POST" action="{{route('products.force-delete',$product->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>Force Delete</button>
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
