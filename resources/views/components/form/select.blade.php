@props([
'id' , 'name' , 'label' , 'value' => '' ,'options'
])

<div class="mb-3">
    <label for="{{ $name }}">{{ $label }}</label>
    <div>
        <select name="{{ $name }}" id="{{ $id }}" class="form-select @error($name) is-invalid @enderror">
            <option></option>
            @foreach($options as $key => $output)
            <option @selected($key == old($name,$value)) value="{{ $key }}">{{ $output }}</option>
            @endforeach
        </select>
        <x-form.error name="{{ $name }}" />
    </div>
</div>
