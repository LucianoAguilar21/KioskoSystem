<?php
// app/Services/TicketService.php

namespace App\Services;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketService
{
    /**
     * Genera el PDF del ticket de venta
     */
    public function generateTicket(Sale $sale): \Barryvdh\DomPDF\PDF
    {
        $sale->load(['user', 'items.product', 'cashRegisterSession']);

        $data = [
            'sale' => $sale,
            'items' => $sale->items,
            'total' => $sale->total_amount,
            'business_name' => config('app.business_name', 'Mi Kiosko'),
            'business_address' => config('app.business_address', 'Dirección del Kiosko'),
            'business_phone' => config('app.business_phone', 'Teléfono'),
        ];

        return Pdf::loadView('tickets.sale', $data)
            ->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 80mm de ancho (ticket térmico)
    }

    /**
     * Genera el ticket y lo guarda en storage
     */
    public function generateAndSave(Sale $sale): string
    {
        $pdf = $this->generateTicket($sale);
        
        $filename = "ticket-{$sale->id}-" . now()->format('YmdHis') . ".pdf";
        $path = storage_path("app/public/tickets/{$filename}");
        
        // Crear directorio si no existe
        if (!file_exists(storage_path('app/public/tickets'))) {
            mkdir(storage_path('app/public/tickets'), 0755, true);
        }
        
        $pdf->save($path);
        
        return "tickets/{$filename}";
    }

    /**
     * Descarga directa del ticket
     */
    public function downloadTicket(Sale $sale): \Barryvdh\DomPDF\PDF
    {
        return $this->generateTicket($sale);
    }

    /**
     * Muestra el ticket en el navegador (para impresión automática)
     */
    public function streamTicket(Sale $sale): \Barryvdh\DomPDF\PDF
    {
        return $this->generateTicket($sale);
    }
}