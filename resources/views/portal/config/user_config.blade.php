var phpAuthUser = {!! json_encode($phpAuthUser) !!};
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{csrf_token()}}"
    }
});
