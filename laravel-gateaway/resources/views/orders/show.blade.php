@if(Auth::check())
    @include('orders.partials.show-staff')
@else
    @include('orders.partials.show-guest')
@endif