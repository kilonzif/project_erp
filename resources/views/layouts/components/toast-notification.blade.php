<script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        @foreach(session()->pull('notifications') as $notification)
                @php /**@var  \App\Classes\ToastNotification $notification **/ @endphp

            toastr['{{ $notification->type }}']('{{ $notification->message }}', '{{ $notification->title }}', {positionClass: 'toast-top-{{ $notification->position }}', "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 15000});
        @endforeach
    });
    @php flushNotifications() @endphp
</script>