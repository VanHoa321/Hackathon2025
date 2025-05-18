<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Document;
use App\Models\Publisher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $timeType = $request->get('timeType', 'all');
            $startDate = $request->get('startDate', null);
            $endDate = $request->get('endDate', null);
            $year = $request->get('year', Carbon::now()->year);

            $transaction = $this->getTransactions($timeType, $startDate, $endDate);
            $monthlyRevenue = $this->getMonthlyRevenue($year);
            $topCustomer = $this->getTopCustomers($timeType, $startDate, $endDate);

            return response()->json([
                'transaction' => $transaction,
                'monthlyRevenue' => $monthlyRevenue,
                'topCustomer' => $topCustomer,
            ]);
        }

        $customer_count = User::where("role_id", 2)->count();
        $document_count = Document::where("approve", 1)->count();
        $author_count = Author::count();
        $publisher_count = Publisher::count();
        return view("admin.home.index", compact("customer_count", "document_count", "author_count", "publisher_count"));
    }

    public function getTransactions($timeType, $startDate, $endDate)
    {
        $transactions = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->leftJoin('documents', 'transactions.document_id', '=', 'documents.id')
            ->select(
                'transactions.id as id',
                'users.name as name',
                'users.phone as phone',
                'documents.title as document_title',
                'transactions.created_at as transaction_date',
                'transactions.note as note',
                'transactions.type as type',
                'transactions.amount as amount'
            );

        // Lọc theo khoảng thời gian
        if ($startDate && $endDate) {
            $transactions->whereBetween('transactions.created_at', [$startDate, $endDate]);
        } elseif ($timeType !== 'all') {
            $now = Carbon::now();
            if ($timeType == 'filterDay') {
                $transactions->whereDate('transactions.created_at', $now->toDateString());
            } elseif ($timeType == 'filterWeek') {
                $transactions->whereBetween('transactions.created_at', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString(),
                ]);
            } elseif ($timeType == 'filterMonth') {
                $transactions->whereMonth('transactions.created_at', $now->month)
                            ->whereYear('transactions.created_at', $now->year);
            } elseif ($timeType == 'filterYear') {
                $transactions->whereYear('transactions.created_at', $now->year);
            }
        }

        return $transactions->orderByDesc('transactions.created_at')->get();
    }

    public function getMonthlyRevenue($year)
    {
        return DB::table('transactions')
        ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->whereYear('created_at', $year)
        ->where('type', 1)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month')
        ->get();
    }

    public function getTopCustomers($timeType, $startDate, $endDate)
    {
        $query = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'users.id as id',
                'users.name as name',
                'users.phone as phone',
                DB::raw('COUNT(transactions.id) as total_transactions'),
                DB::raw('SUM(transactions.amount) as total_amount')
            )
            ->where('transactions.type', 1);

        // Lọc theo thời gian
        if ($startDate && $endDate) {
            $query->whereBetween('transactions.created_at', [$startDate, $endDate]);
        } elseif ($timeType !== 'all') {
            $now = Carbon::now();
            if ($timeType == 'filterDay') {
                $query->whereDate('transactions.created_at', $now->toDateString());
            } elseif ($timeType == 'filterWeek') {
                $query->whereBetween('transactions.created_at', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString(),
                ]);
            } elseif ($timeType == 'filterMonth') {
                $query->whereMonth('transactions.created_at', $now->month)
                    ->whereYear('transactions.created_at', $now->year);
            } elseif ($timeType == 'filterYear') {
                $query->whereYear('transactions.created_at', $now->year);
            }
        }

        $topCustomers = $query->groupBy('users.id', 'users.name', 'users.phone')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return $topCustomers;
    }
}
