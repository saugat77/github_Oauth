@extends('layouts.main')
@section('content')
<form id="form" action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="d-flex">
        <div class="col-md-6 m-4 justify-content-center">
            <h2 class="block text-gray-700">Upload Json File</h2>
            @error('jsonFile')
                    <div class="text-danger">{{ $message }}</div>
            @enderror
            <input type="file" name="jsonFile" class=" form-control">
            <button class="m-2 btn btn-success rounded">
                Submit
            </button>
        </div>
    </div>

</form>
@endsection

