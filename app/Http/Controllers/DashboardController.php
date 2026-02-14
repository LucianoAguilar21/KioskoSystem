<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Services\CashRegisterService;
use App\Services\StockService;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private CashRegisterService $cashRegisterService,
        private StockService $stockService
    ) {}

    public function index()
    {
        $currentSession = $this->cashRegisterService->getCurrentSession();
        $todayStats = $this->getTodayStats();
        $topProducts = $this->getTopProducts();
        $stockAlerts = $this->stockService->getCriticalAlerts();

        return view('dashboard.index', compact(
            'currentSession',
            'todayStats',
            'topProducts',
            'stockAlerts'
        ));
    }

    private function getTodayStats(): array
    {
        $today = now()->startOfDay();
        $sales = Sale::where('created_at', '>=', $today)->get();
        
        return [
            'total_sales' => $sales->count(),
            'total_amount' => $sales->sum('total_amount'),
            'cash_amount' => $sales->sum('cash_amount'),
            'card_amount' => $sales->sum('card_amount'),
            'transfer_amount' => $sales->sum('transfer_amount'),
            'estimated_profit' => $sales->sum(fn($sale) => $sale->totalProfit()),
        ];
    }

    private function getTopProducts(int $limit = 5): array
    {
        return DB::table('sale_items')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.subtotal) as total_amount')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.created_at', '>=', now()->startOfDay())
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}