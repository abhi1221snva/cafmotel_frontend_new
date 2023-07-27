{{-- -------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
    <table class="messenger-list-item m-li-divider" data-contact="{{ Session::get('id') }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
            <div class="avatar av-m" style="background-color: #d9efff; text-align: center;">
                <span class="far fa-bookmark" style="font-size: 22px; color: #68a5ff; margin-top: calc(50% - 10px);"></span>
            </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Session::get('id') }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- All users/group list -------------------- --}}
@if($get == 'users')
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td style="position: relative">
            @if($user->active_status)
                <span class="activeStatus"></span>
            @endif
        <div class="avatar av-m"
        style="background-image: url('{{ '/asset/img/'.$user->avatar }}');">
        </div>
        </td>
        {{-- center side --}}
        <td>
        <p data-id="{{ $user->id }}" data-type="user">
            {{ strlen($user->first_name." ".$user->last_name) > 12 ? trim(substr($user->first_name." ".$user->last_name,0,12)).'..' : $user->first_name." ".$user->last_name }}
            @if($lastMessage) <span> {{ date('Y-m-d h:i', strtotime($lastMessage->created_at)) }}</span>  @endif
        </p>
            @if($lastMessage)
            <span>
                {{-- Last Message user indicator --}}
                {!!
                    $lastMessage->from_id == Session::get('id')
                    ? '<span class="lastMessageIndicator">You :</span>'
                    : ''
                !!}
                {{-- Last message body --}}
                @if($lastMessage->attachment == null)
                {{
                    strlen($lastMessage->body) > 30
                    ? trim(substr($lastMessage->body, 0, 30)).'..'
                    : $lastMessage->body
                }}
                @else
                <span class="fas fa-file"></span> Attachment
                @endif
            </span>
            @endif
        {{-- New messages counter --}}
            {!! $unseenCounter > 0 ? "<b>".$unseenCounter."</b>" : '' !!}
        </td>

    </tr>
</table>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td>
        <div class="avatar av-m"
            {{--      avatar modified to profile_pic       --}}
        style="background-image: url('{{ '/asset/img/'.$user->profile_pic }}');">
        </div>
        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ $user->id }}" data-type="user">
            {{ strlen($user->first_name." ".$user->last_name) > 12 ? trim(substr($user->first_name." ".$user->last_name,0,12)).'..' : $user->first_name." ".$user->last_name }}
        </td>
    </tr>
</table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif

