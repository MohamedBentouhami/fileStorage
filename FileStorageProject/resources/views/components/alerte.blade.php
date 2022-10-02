<div>
    <!-- Success messages -->
    @if (session('success'))
    <div class="rounded-md p-5 bg-success bg-opacity-10 m-5 text-success alert">
        <div style="text-align: center; font-weight: bold;">
            {!! session('success') !!}
        </div>
    </div>
    @endif

    <!-- Warning messages -->
    @if (session('warning'))
    <div class="rounded-md p-5 bg-danger bg-opacity-10 m-5 text-dark alert">
        <div style="text-align: center; font-weight: bold;">
            {!! session('warning') !!}
        </div>
    </div>
    @endif

    <!-- Validation Status -->
    @if (session('status'))
    <div class="rounded-md p-5 bg-info bg-opacity-10 m-5 text-info alert">
        {{ Session::get('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>