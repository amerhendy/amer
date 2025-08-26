@extends('app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('auth.Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('employerlogin-post') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="nid" class="col-md-4 col-form-label text-md-end">{{ __('auth.nid') }}</label>
                            <div class="col-md-6">
                                <input id="nid" oninput="checknid()" type="number" class="form-control @error('nid') is-invalid @enderror" name="nid" value="{{ old('nid') }}" required autocomplete="nid" autofocus>

                                @error('nid')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="uid" class="col-md-4 col-form-label text-md-end">{{ __('auth.uid') }}</label>

                            <div class="col-md-6">
                                <input id="uid" type="text" oninput="checkuid()" class="form-control @error('uid') is-invalid @enderror" name="uid" required autocomplete="current-uid">
                                <script>
                                    function checkuid(){
                                        let uidtext=document.getElementById('uid').value;
                                        if(uidtext.length !== 5){
                                            $('#uid').addClass("is-invalid");
                                        }else{
                                            $('#uid').removeClass("is-invalid");
                                        }
                                    }
                                    function checknid(){
                                        let nidtext=document.getElementById('nid').value;
                                        if(nidtext.length < 14){
                                            $('#nid').addClass("is-invalid");
                                        }else if(nidtext.length > 14){
                                            $('#nid').addClass("is-invalid");
                                        }else{
                                            $('#nid').removeClass("is-invalid");
                                        }
                                    }
                                </script>
                                @error('uid')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('auth.login') }}
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
