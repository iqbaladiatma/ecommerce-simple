<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<button onclick="pay()">Bayar Sekarang</button>

<script>
    function pay() {
        fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: [
                    { product_id: 1, quantity: 2 },
                    { product_id: 2, quantity: 1 }
                ]
            })
        })
        .then(res => res.json())
        .then(res => {
            snap.pay(res.snap_token, {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran!");
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                }
            });
        });
    }
</script>
