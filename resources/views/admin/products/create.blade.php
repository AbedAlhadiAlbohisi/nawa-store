@extends('layouts.admin')

@section('content')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Create Products</h3>
    </div>
</div>
      <form action="{{route('products.store',$product->id)}}"
        method="post" enctype="multipart/form-data">
         @csrf
                    @include('admin.products._form',[
                        'submit_label'=>'save'
                    ])
        </form>
@endsection
