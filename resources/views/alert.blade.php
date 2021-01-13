@if(count($errors)>0)
    <div class="" role="alert">
        <ul style="list-style: none; padding: 0; margin: 10px 0 0 0; color: #FF5238;">
            @foreach($errors->all() as $error)
                <li>
                    {{$error}}
                </li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-primary" role="alert">
        {{session('error')}}
    </div>
@endif
