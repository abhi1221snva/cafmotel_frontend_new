{{--<script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>--}}
<script src="{{url('asset/js/pusher.min.js')}}"></script>
<script>
  // Enable pusher logging - don't include this in production
  Pusher.logToConsole = true;

  var pusher = new Pusher("{{ env("PUSHER_APP_KEY") }}", {
    encrypted: false,
    cluster: "{{ env("PUSHER_APP_CLUSTER") }}",
    authEndpoint: '{{url("chat/auth")}}',
    auth: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }
  });
</script>
<script src="{{ asset('asset/js/code.js') }}"></script>
<script src="{{ asset('asset/js/cf_custom.js') }}"></script>
