@if(Auth::check())
    <x-app-layout>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe("{{ $api_key }}");

            initialize();

            async function initialize() {
                const fetchClientSecret = async () => {
                    return "{{ $client_secret }}";
                };

                const checkout = await stripe.initEmbeddedCheckout({
                    fetchClientSecret,
                });

                checkout.mount('#checkout');
            }
        </script>
        <div id="checkout" class="rounded-xl overflow-hidden">
            <!-- Checkout will insert the payment form here -->
        </div>
    </x-app-layout>
@else
    <x-guest-layout>
        <br>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe("{{ $api_key }}");

            initialize();

            async function initialize() {
                const fetchClientSecret = async () => {
                    return "{{ $client_secret }}";
                };

                const checkout = await stripe.initEmbeddedCheckout({
                    fetchClientSecret,
                });

                checkout.mount('#checkout');
            }
        </script>
        <div id="checkout" class="rounded-xl overflow-hidden">
            <!-- Checkout will insert the payment form here -->
        </div>
    </x-guest-layout>
@endif
