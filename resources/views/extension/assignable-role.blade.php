<div class="form-group">
    <input type="hidden" name="_token" id="user-role-csrf" value="{{ csrf_token() }}" />
    <div class="form-group">
        <label>Select New Role: </label>
        <select name="role" class="custom-select" id="role-select">
            @foreach ($roles as $key => $role)
            <option data-id="{{$key}}" value={{$key}} @if ($role->assigned) selected @endif>{{$role->roleName}}</option>
            @endforeach
        </select>
    </div>
</div>
