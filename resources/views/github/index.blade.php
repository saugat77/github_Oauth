@extends('layouts.main')
@section('content')
    <form action="{{ route('github.cred') }}" method="POST">
        @csrf
        <div class="card" style=" width: 50rem;">
            @if ($errors->any())
                <div class="m-3 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <div class="form-row">
                    <div class="m-3 form-group col-md-6">
                        <label for="inputEmail4">Client ID</label>
                        <input type="text" name="client_id" class="form-control" placeholder="Client ID"
                            value="{{  old('client_id') }}">
                    </div>
                    <div class="m-3 form-group col-md-6">
                        <label for="inputPassword4">Client Secret</label>
                        <input type="text" name="client_secret" class="form-control" placeholder="Client Secret"
                            value=" {{ old('client_secret') }} ">
                    </div>
                </div>
                <button type="submit" class="m-3 btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
