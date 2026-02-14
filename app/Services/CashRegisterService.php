<?php

// app/Services/CashRegisterService.php
namespace App\Services;

use App\Models\CashRegisterSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashRegisterService
{
    public function openSession(User $user, float $initialAmount): CashRegisterSession
    {
        // Verificar que no haya caja abierta
        if ($this->hasOpenSession()) {
            throw new \Exception('Ya existe una caja abierta');
        }

        DB::beginTransaction();
        try {
            $session = CashRegisterSession::create([
                'user_id' => $user->id,
                'initial_amount' => $initialAmount,
                'status' => 'open',
                'opened_at' => now(),
            ]);

            Log::info('Caja abierta', [
                'session_id' => $session->id,
                'user_id' => $user->id,
                'initial_amount' => $initialAmount,
            ]);

            DB::commit();
            return $session;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al abrir caja', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function closeSession(
        CashRegisterSession $session,
        float $finalAmount,
        ?string $notes = null
    ): CashRegisterSession {
        if ($session->isClosed()) {
            throw new \Exception('Esta caja ya está cerrada');
        }

        DB::beginTransaction();
        try {
            $expectedAmount = $this->calculateExpectedAmount($session);
            $difference = $finalAmount - $expectedAmount;

            $session->update([
                'final_amount' => $finalAmount,
                'expected_amount' => $expectedAmount,
                'difference' => $difference,
                'notes' => $notes,
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            Log::info('Caja cerrada', [
                'session_id' => $session->id,
                'expected' => $expectedAmount,
                'final' => $finalAmount,
                'difference' => $difference,
            ]);

            DB::commit();
            return $session->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cerrar caja', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function calculateExpectedAmount(CashRegisterSession $session): float
    {
        $cashSales = $session->sales()
            ->sum('cash_amount');

        return $session->initial_amount + $cashSales;
    }

    public function hasOpenSession(): bool
    {
        return CashRegisterSession::open()->exists();
    }

    public function getCurrentSession(): ?CashRegisterSession
    {
        return CashRegisterSession::open()->first();
    }

    public function getSessionSummary(CashRegisterSession $session): array
    {
        $sales = $session->sales;

        return [
            'total_sales' => $sales->count(),
            'total_amount' => $sales->sum('total_amount'),
            'cash_sales' => $sales->sum('cash_amount'),
            'card_sales' => $sales->sum('card_amount'),
            'transfer_sales' => $sales->sum('transfer_amount'),
            'initial_amount' => $session->initial_amount,
            'expected_cash' => $session->initial_amount + $sales->sum('cash_amount'),
        ];
    }
}