<?php
// app/Http/Controllers/CashRegisterController.php

namespace App\Http\Controllers;

use App\Http\Requests\OpenCashRegisterRequest;
use App\Http\Requests\CloseCashRegisterRequest;
use App\Models\CashRegisterSession;
use App\Services\CashRegisterService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class CashRegisterController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private CashRegisterService $cashRegisterService
    ) {}

    public function index()
    {
        $sessions = CashRegisterSession::with('user')
            ->orderByDesc('opened_at')
            ->paginate(20);

        $currentSession = $this->cashRegisterService->getCurrentSession();

        return view('cash-register.index', compact('sessions', 'currentSession'));
    }

    public function open(OpenCashRegisterRequest $request): RedirectResponse
    {
        try {
            $this->cashRegisterService->openSession(
                $request->user(),
                $request->validated('initial_amount')
            );

            return redirect()
                ->route('sales.index')
                ->with('success', 'Caja abierta correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showCloseForm(CashRegisterSession $session)
    {
        $this->authorize('close', $session);
        $summary = $this->cashRegisterService->getSessionSummary($session);

        return view('cash-register.close', compact('session', 'summary'));
    }

    public function close(
        CloseCashRegisterRequest $request,
        CashRegisterSession $session
    ): RedirectResponse {
        try {
            $this->cashRegisterService->closeSession(
                $session,
                $request->validated('final_amount'),
                $request->validated('notes')
            );

            return redirect()
                ->route('dashboard')
                ->with('success', 'Caja cerrada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(CashRegisterSession $session)
    {
        // Usar la policy 'view' en lugar de 'viewReports'
        $this->authorize('view', $session);
        
        $session->load(['user', 'sales.items.product']);
        $summary = $this->cashRegisterService->getSessionSummary($session);

        return view('cash-register.show', compact('session', 'summary'));
    }
}