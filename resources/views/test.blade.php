<!-- QR Code Section -->
<div class="barcode-section">
    @php
        // Use the static string directly
        $qrData = "TE7QBsrVWszNQ4fhToTHT626D2g8Ykfq5d";
        $qrSvg = \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($qrData, 'QRCODE', 2, 2);
    @endphp
    <div>
        {!! $qrSvg !!}
    </div>
</div>