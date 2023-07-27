<div class="col-md-12">
    <div class="alert alert-danger" id="alert-errors" style="display: none"></div>
    <div class="alert alert-success" id="alert-success" style="display: none"></div>
    @if(session()->has('message'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session()->get('message') }}
            {{ session()->forget('message') }}
            {{ session()->save() }}
        </div>
    @endif
    @if(session()->has('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session()->get('success') }}
            {{ session()->forget('success') }}
            {{ session()->save() }}
        </div>
    @endif
    @if (count($errors) > 0 or session()->has('error-title'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            @if(session()->has('error-title'))
                {{ session()->get('error-title') }}
                {{ session()->forget('error-title') }}
                {{ session()->save() }}
            @endif
            @if (count($errors) > 0)
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif
        </div>
    @endif
</div>
