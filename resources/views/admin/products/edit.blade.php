@extends('layouts.admin')
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Edit Product</h3>
      </div>
</div>
          <form action="{{route('products.update', $product->id)}}"
            method="post" enctype="multipart/form-data">
               @csrf
               @method('put')
               @include('admin.products._form',[
                'submit_label'=>'updated'
            ])
        </form>
@endsection
