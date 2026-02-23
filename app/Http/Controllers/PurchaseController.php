<?php
// app/Http/Controllers/PurchaseController.php
namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;

use App\Services\PurchaseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private PurchaseService $purchaseService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Purchase::class);

        $purchases = Purchase::with(['supplier', 'user'])
            ->orderByDesc('purchase_date')
            ->paginate(20);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $this->authorize('create', Purchase::class);

        $suppliers = Supplier::active()->orderBy('name')->get();

        // Incluir cost_price y stock en los productos
        $products = Product::active()
            ->orderBy('name')
            ->get(['id', 'name', 'cost_price', 'stock']);

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse | \Illuminate\Http\JsonResponse
    {
        // Validación manual para manejar tanto JSON como FormData
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'supplier_id.required' => 'Debe seleccionar un proveedor',
            'items.required' => 'Debe agregar al menos un producto',
            'items.array' => 'Los productos deben ser un array',
            'items.min' => 'Debe agregar al menos un producto',
        ]);

        try {
            $supplier = Supplier::findOrFail($validated['supplier_id']);

            $this->purchaseService->createPurchase(
                $request->user(),
                $supplier,
                $validated['items'],
                $validated['invoice_number'],
                isset($validated['purchase_date']) ? new \DateTime($validated['purchase_date']) : null,
                $validated['notes'] ?? null
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Compra registrada correctamente'
                ], 201);
            }

            return redirect()
                ->route('purchases.index')
                ->with('success', 'Compra registrada correctamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }


    public function show(Purchase $purchase)
    {
        $this->authorize('viewAny', Purchase::class);
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }
}
