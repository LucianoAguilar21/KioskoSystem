{{-- resources/views/tickets/sale.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #{{ $sale->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.4;
            padding: 10px;
        }
        
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        
        .business-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .business-info {
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .ticket-info {
            margin-bottom: 15px;
            font-size: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .ticket-info div {
            margin-bottom: 3px;
        }
        
        .items {
            margin-bottom: 15px;
        }
        
        .item {
            margin-bottom: 8px;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        .totals {
            margin-bottom: 15px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .total-row.main {
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #000;
        }
        
        .payment-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .payment-method {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
        }
        
        .footer-message {
            margin-bottom: 3px;
        }
        
        .not-valid {
            font-weight: bold;
            margin-top: 8px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="header">
            <div class="business-name">{{ $business_name }}</div>
            <div class="business-info">{{ $business_address }}</div>
            <div class="business-info">Tel: {{ $business_phone }}</div>
        </div>

        <!-- Información del Ticket -->
        <div class="ticket-info">
            <div><strong>TICKET DE VENTA</strong></div>
            <div>N°: {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div>Fecha: {{ $sale->created_at->format('d/m/Y H:i:s') }}</div>
            <div>Cajero: {{ $sale->user->name }}</div>
            <div>Caja: #{{ $sale->cash_register_session_id }}</div>
        </div>

        <!-- Items -->
        <div class="items">
            @foreach($items as $item)
                <div class="item">
                    <div class="item-name">{{ $item->product->name }}</div>
                    <div class="item-details">
                        <span>{{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</span>
                        <span>${{ number_format($item->subtotal, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="separator"></div>

        <!-- Totales -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($sale->total_amount, 2) }}</span>
            </div>
            <div class="total-row main">
                <span>TOTAL:</span>
                <span>${{ number_format($sale->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Información de Pago -->
        <div class="payment-info">
            <div class="payment-method">
                MÉTODO DE PAGO:
                @if($sale->payment_method === 'cash')
                    EFECTIVO
                @elseif($sale->payment_method === 'card')
                    TARJETA
                @elseif($sale->payment_method === 'transfer')
                    TRANSFERENCIA
                @else
                    MIXTO
                @endif
            </div>
            
            @if($sale->payment_method === 'mixed')
                <div class="separator"></div>
                @if($sale->cash_amount > 0)
                    <div class="total-row">
                        <span>Efectivo:</span>
                        <span>${{ number_format($sale->cash_amount, 2) }}</span>
                    </div>
                @endif
                @if($sale->card_amount > 0)
                    <div class="total-row">
                        <span>Tarjeta:</span>
                        <span>${{ number_format($sale->card_amount, 2) }}</span>
                    </div>
                @endif
                @if($sale->transfer_amount > 0)
                    <div class="total-row">
                        <span>Transferencia:</span>
                        <span>${{ number_format($sale->transfer_amount, 2) }}</span>
                    </div>
                @endif
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">¡Gracias por su compra!</div>
            <div class="footer-message">Que tenga un excelente día</div>
            <div class="not-valid">*** DOCUMENTO NO VÁLIDO COMO FACTURA ***</div>
        </div>
    </div>
</body>
</html>