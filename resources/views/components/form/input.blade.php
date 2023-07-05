@props([
    'id' , 'name' , 'label' , 'value' , 'type' => 'text',
])

<div class="mb-3">
    <label for="{{ $id }}">{{ $label }}</label>
    <div>
        <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" value="{{ old($name , $value) }}" placeholder="{{$label}}">

        <x-form.error name="{{ $name }}" />
    </div>

</div>
