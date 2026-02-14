<?php
// app/Http/Controllers/SaleController.php
namespace App\Http\Controllers;

use App\Http\Requests\CreateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use App\Services\CashRegisterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private CashRegisterService $cashRegisterService
    ) {}

    public function index()
    {
        $currentSession = $this->cashRegisterService->getCurrentSession();

        if (!$currentSession) {
            return view('cash-register.open-required');
        }

        $products = Product::active()
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'sale_price', 'stock']);

        return view('sales.index', compact('products', 'currentSession'));
    }

    // public function store(CreateSaleRequest $request): RedirectResponse
    // {
    //     try {
    //         $currentSession = $this->cashRegisterService->getCurrentSession();

    //         if (!$currentSession) {
    //             return back()->with('error', 'No hay caja abierta');
    //         }

    //         $sale = $this->saleService->createSale(
    //             $request->user(),
    //             $currentSession,
    //             $request->validated('items'),
    //             $request->validated('payment_method'),
    //             $request->validated('payment_amounts')
    //         );

    //         return redirect()
    //             ->route('sales.index')
    //             ->with('success', "Venta #{$sale->id} registrada correctamente");
    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }
    // app/Http/Controllers/SaleController.php - Método store actualizado
    public function store(Request $request): RedirectResponse | \Illuminate\Http\JsonResponse
    {
        // Validación manual para manejar tanto JSON como FormData
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,card,transfer,mixed'],
            'payment_amounts' => ['required', 'array'],
            'payment_amounts.cash' => ['nullable', 'numeric', 'min:0'],
            'payment_amounts.card' => ['nullable', 'numeric', 'min:0'],
            'payment_amounts.transfer' => ['nullable', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Debe agregar al menos un producto',
            'items.array' => 'Los productos deben ser un array',
            'items.min' => 'Debe agregar al menos un producto',
        ]);

        try {
            $currentSession = $this->cashRegisterService->getCurrentSession();

            if (!$currentSession) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'No hay caja abierta'], 422);
                }
                return back()->with('error', 'No hay caja abierta');
            }

            $sale = $this->saleService->createSale(
                $request->user(),
                $currentSession,
                $validated['items'],
                $validated['payment_method'],
                $validated['payment_amounts']
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Venta #{$sale->id} registrada correctamente",
                    'sale' => $sale
                ], 201);
            }

            return redirect()
                ->route('sales.index')
                ->with('success', "Venta #{$sale->id} registrada correctamente");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $sales = Sale::with(['user', 'items.product'])
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('sales.history', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product', 'cashRegisterSession']);
        return view('sales.show', compact('sale'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'code', 'sale_price', 'stock']);

        return response()->json($products);
    }
}
