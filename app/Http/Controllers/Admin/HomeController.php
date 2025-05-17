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

            $topItems = $this->getTopItems($timeType, $startDate, $endDate);
            $topCombos = $this->getTopCombos($timeType, $startDate, $endDate);
            $monthlyRevenue = $this->getMonthlyRevenue($year);
            $invoice = $this->getInvoices($timeType, $startDate, $endDate);
            $topCustomers = $this->getTopCustomers($timeType, $startDate, $endDate);

            return response()->json([
                'topItems' => $topItems,
                'topCombos' => $topCombos,
                'monthlyRevenue' => $monthlyRevenue,
                'invoice' => $invoice,
                'topCustomers' => $topCustomers,
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
            ->join('documents', 'transactions.document_id', '=', 'documents.id')
            ->select(
                'transactions.id as id',
                'users.avatar as avatar',
                'users.full_name as name',
                'users.phone as phone',
                'documents.title as document_title',
                'transactions.created_at as transaction_date',
                'transactions.amount as amount'
            )
            ->where('transactions.status', 1);

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
}
