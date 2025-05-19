<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentComment;
use App\Models\Favourite;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatisticController extends Controller
{
    public function ratingStatistic()
    {
        // Get documents with average ratings and count, sorted by average rating
        $documents = Document::select('documents.*')
            ->leftJoin('ratings', 'documents.id', '=', 'ratings.document_id')
            ->groupBy('documents.id')
            ->with(['category'])
            ->orderByRaw('AVG(ratings.rating) DESC')
            ->get()
            ->map(function ($document) {
                $document->average_rating = Rating::where('document_id', $document->id)->avg('rating') ?? 0;
                $document->rating_count = Rating::where('document_id', $document->id)->count();
                return $document;
            });

        $monthlyStats = [];
        $monthlyRatingCountStats = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < 6; $i++) {
            $month = $currentDate->copy()->subMonths($i);
            $monthName = 'Tháng ' . $month->format('n') . '-' . $month->format('Y');
            $monthStart = $month->copy()->startOfMonth()->setTime(0, 0, 0);
            $monthEnd = $month->copy()->endOfMonth()->setTime(23, 59, 59);


            // Average rating stats (top 10 documents by average rating up to month end)
            $avgStats = Rating::select('documents.id', 'documents.title')
                ->selectRaw('AVG(ratings.rating) as average_rating')
                ->join('documents', 'ratings.document_id', '=', 'documents.id')
                ->whereBetween('ratings.created_at', [$monthStart, $monthEnd])
                ->groupBy('documents.id', 'documents.title')
                ->orderByRaw('AVG(ratings.rating) DESC')
                ->limit(10)
                ->get();

            $monthlyStats[$monthName] = [
                'labels' => $avgStats->pluck('title')->toArray(),
                'data' => $avgStats->pluck('average_rating')->map(fn($rating) => round($rating, 2))->toArray()
            ];

            // Favourite stats (top 10 documents by number of favourites in the month)
            $countStats = Rating::select('documents.id', 'documents.title')
                ->selectRaw('COUNT(ratings.id) as rating_count')
                ->join('documents', 'ratings.document_id', '=', 'documents.id')
                ->whereBetween('ratings.created_at', [$monthStart, $monthEnd])
                ->where("approve", 1)
                ->groupBy('documents.id', 'documents.title')
                ->orderByRaw('COUNT(ratings.id) DESC')
                ->limit(10)
                ->get();

            $monthlyRatingCountStats[$monthName] = [
                'labels' => $countStats->pluck('title')->toArray(),
                'data' => $countStats->pluck('rating_count')->toArray()
            ];
        }
        return view('admin.statistic.rating-statistic', compact('documents', 'monthlyStats', 'monthlyRatingCountStats'));
    }

    public function ratingList($documentId)
    {
        try {
            $document = Document::findOrFail($documentId);
            $ratings = Rating::where('document_id', $documentId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.statistic.rating-list', compact('document', 'ratings'));
        } catch (\Exception $e) {
            Log::error('Error in ratingList', ['error' => $e->getMessage(), 'document_id' => $documentId]);
            return redirect()->route('statistic.rating')
                ->with('error', 'Không thể tải danh sách người đánh giá. Vui lòng thử lại.');
        }
    }

    public function favouriteStatistic()
    {
        // Get documents with favourite counts, sorted by favourite count
        $documents = Document::select('documents.*')
            ->leftJoin('favourites', 'documents.id', '=', 'favourites.document_id')
            ->groupBy('documents.id')
            ->with(['category'])
            ->orderByRaw('COUNT(favourites.id) DESC')
            ->get()
            ->map(function ($document) {
                $document->favourite_count = Favourite::where('document_id', $document->id)->count();
                return $document;
            });

        // Prepare data for overall favourites chart (top 10 documents by total favourites)
        $overallfavouriteStats = Favourite::select('documents.id', 'documents.title')
            ->join('documents', 'favourites.document_id', '=', 'documents.id')
            ->groupBy('documents.id', 'documents.title')
            ->orderByRaw('COUNT(favourites.id) DESC')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->favourite_count = Favourite::where('document_id', $item->id)->count();
                return $item;
            });

        $overallfavouriteData = [
            'labels' => $overallfavouriteStats->pluck('title')->toArray(),
            'data' => $overallfavouriteStats->pluck('favourite_count')->toArray()
        ];

        // Prepare data for monthly favourites charts (last 6 months)
        $monthlyfavouriteStats = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < 6; $i++) {
            $month = $currentDate->copy()->subMonths($i);
            $monthName = 'Tháng ' . $month->format('n') . '-' . $month->format('Y'); // e.g., "5/2025"
            $monthStart = $month->copy()->startOfMonth()->setTime(0, 0, 0); // Explicitly set to start of day
            $monthEnd = $month->copy()->endOfMonth()->setTime(23, 59, 59);   // Explicitly set to end of day

            // Favourite stats (top 10 documents by number of favourites in the month)
            $favStats = Favourite::select('documents.id', 'documents.title')
                ->selectRaw('COUNT(favourites.id) as favorite_count')
                ->join('documents', 'favourites.document_id', '=', 'documents.id')
                ->whereBetween('favourites.created_at', [$monthStart, $monthEnd])
                ->where("approve", 1)
                ->groupBy('documents.id', 'documents.title')
                ->orderByRaw('COUNT(favourites.id) DESC')
                ->limit(10)
                ->get();

            $monthlyFavouriteStats[$monthName] = [
                'labels' => $favStats->pluck('title')->toArray(),
                'data' => $favStats->pluck('favorite_count')->toArray()
            ];
        }

        return view('admin.statistic.favourite-statistic', compact('documents', 'overallfavouriteData', 'monthlyFavouriteStats'));
    }

    public function downloadStatistic()
    {
        // Get documents sorted by download count for the table
        $documents = Document::select('documents.*')
            ->with(['category'])
            ->orderBy('download_count', 'DESC')
            ->get();

        // Prepare data for top 10 documents by download count chart
        $topDownloadDocuments = Document::select('documents.id', 'documents.title', 'documents.download_count')
            ->orderBy('download_count', 'DESC')
            ->limit(10)
            ->get();

        $topDownloadDocumentsData = [
            'labels' => $topDownloadDocuments->pluck('title')->toArray(),
            'data' => $topDownloadDocuments->pluck('download_count')->toArray()
        ];

        // Prepare data for top 10 categories by total download count
        $topDownloadCategories = DocumentCategory::select('document_categories.id', 'document_categories.name')
            ->selectRaw('SUM(documents.download_count) as total_download_count')
            ->join('documents', 'document_categories.id', '=', 'documents.category_id')
            ->groupBy('document_categories.id', 'document_categories.name')
            ->orderByRaw('SUM(documents.download_count) DESC')
            ->limit(10)
            ->get();

        $topDownloadCategoriesData = [
            'labels' => $topDownloadCategories->pluck('name')->toArray(),
            'data' => $topDownloadCategories->pluck('total_download_count')->toArray()
        ];

        return view('admin.statistic.download-statistic', compact('documents', 'topDownloadDocumentsData', 'topDownloadCategoriesData'));
    }

    public function commentStatistic()
    {
        // Get documents sorted by comment count for the table
        $documents = Document::select('documents.*')
            ->leftJoin('document_comments', 'documents.id', '=', 'document_comments.document_id')
            ->groupBy('documents.id')
            ->with(['category'])
            ->orderByRaw('COUNT(document_comments.id) DESC')
            ->get()
            ->map(function ($document) {
                $document->comment_count = DocumentComment::where('document_id', $document->id)->count();
                return $document;
            });

        // Prepare data for top 10 documents by comment count chart
        $topCommentDocuments = Document::select('documents.id', 'documents.title')
            ->leftJoin('document_comments', 'documents.id', '=', 'document_comments.document_id')
            ->groupBy('documents.id', 'documents.title')
            ->orderByRaw('COUNT(document_comments.id) DESC')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->comment_count = DocumentComment::where('document_id', $item->id)->count();
                return $item;
            });

        $topCommentDocumentsData = [
            'labels' => $topCommentDocuments->pluck('title')->toArray(),
            'data' => $topCommentDocuments->pluck('comment_count')->toArray()
        ];

        // Prepare data for weekly comment charts (last 7 weeks)
        $weeklyCommentStats = [];
        $currentDate = Carbon::now();
        $weeksAgo = 6; // 7 weeks including the current week

        // Mapping English day abbreviations to Vietnamese
        $dayMapping = [
            'Mon' => 'T2',
            'Tue' => 'T3',
            'Wed' => 'T4',
            'Thu' => 'T5',
            'Fri' => 'T6',
            'Sat' => 'T7',
            'Sun' => 'CN'
        ];

        for ($i = 0; $i <= $weeksAgo; $i++) {
            $weekStart = $currentDate->copy()->subWeeks($i)->startOfWeek(Carbon::MONDAY)->setTime(0, 0, 0);
            $weekEnd = $currentDate->copy()->subWeeks($i)->endOfWeek(Carbon::SUNDAY)->setTime(23, 59, 59);
            $weekLabel = "Tuần từ " . $weekStart->format('d/m/Y'); // e.g., "Tuần từ 12/05/2025"

            $dailyComments = [];
            for ($day = 0; $day < 7; $day++) {
                $dayStart = $weekStart->copy()->addDays($day)->setTime(0, 0, 0);
                $dayEnd = $weekStart->copy()->addDays($day)->setTime(23, 59, 59);
                $dayName = $dayStart->format('D'); // e.g., "Mon"
                $vietnameseDay = $dayMapping[$dayName]; // e.g., "T2"
                $dayLabel = $vietnameseDay . ' (' . $dayStart->format('d/m') . ')'; // e.g., "T2 (05/12)"
                $dailyComments[$dayLabel] = DocumentComment::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            }

            $weeklyCommentStats[$weekLabel] = [
                'labels' => array_keys($dailyComments),
                'data' => array_values($dailyComments)
            ];
        }

        return view('admin.statistic.comment-statistic', compact('documents', 'topCommentDocumentsData', 'weeklyCommentStats'));
    }

    public function commentList($documentId)
    {
        try {
            $document = Document::findOrFail($documentId);
            $comments = DocumentComment::where('document_id', $documentId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.statistic.comment-list', compact('document', 'comments'));
        } catch (\Exception $e) {
            Log::error('Error in commentList', ['error' => $e->getMessage(), 'document_id' => $documentId]);
            return redirect()->route('statistic.comment')
                ->with('error', 'Không thể tải danh sách bình luận. Vui lòng thử lại.');
        }
    }

    public function favouriteList($documentId)
    {
        try {
            $document = Document::findOrFail($documentId);
            $favourites = Favourite::where('document_id', $documentId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.statistic.favourite-list', compact('document', 'favourites'));
        } catch (\Exception $e) {
            Log::error('Error in favouriteList', ['error' => $e->getMessage(), 'document_id' => $documentId]);
            return redirect()->route('statistic.favourite')
                ->with('error', 'Không thể tải danh sách người yêu thích. Vui lòng thử lại.');
        }
    }

    public function userStatistic()
    {
        // Prepare data for registration charts (last 6 months)
        $registrationStats = [];
        $transactionStats = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < 6; $i++) {
            $month = $currentDate->copy()->subMonths($i);
            $monthName = 'Tháng ' . $month->format('n') . '-' . $month->format('Y');
            $monthStart = $month->copy()->startOfMonth()->setTime(0, 0, 0);
            $monthEnd = $month->copy()->endOfMonth()->setTime(23, 59, 59);

            // For the current month, limit to today (May 17, 2025, 10:06 PM +07)
            if ($i === 0) {
                $monthEnd = Carbon::today()->setTime(23, 59, 59); // Today at 23:59:59
            }

            // Calculate days in the month for the chart
            $daysInMonth = $monthStart->diffInDays($monthEnd) + 1;
            $dailyRegistrations = [];
            $dailyTransactions = [
                1 => [], // Type 1: Nạp tiền (Deposit)
                2 => [], // Type 2: Mua tài liệu (Buy Document)
                3 => [], // Type 3: Tải lên tài liệu (Upload Document)
                4 => [], // Type 4: Tải xuống tài liệu (Download Document)
                5 => [], // Type 5: Tài liệu tải lên được duyệt (Approved Upload)
                6 => [], // Type 6: Tài liệu tải lên được mua (Purchased Upload)
            ];

            // Initialize arrays for each day
            for ($day = 0; $day < $daysInMonth; $day++) {
                $dayDate = $monthStart->copy()->addDays($day);
                $dayLabel = $dayDate->format('d/m'); // e.g., "01/05"
                $dailyRegistrations[$dayLabel] = 0; // Initialize registration count
                foreach ($dailyTransactions as $type => $data) {
                    $dailyTransactions[$type][$dayLabel] = 0; // Initialize transaction counts
                }
            }

            // Fetch registration data from users table
            $registrations = User::selectRaw('DATE(created_at) as registration_date, COUNT(*) as registration_count')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->groupBy('registration_date')
                ->get();

            foreach ($registrations as $registration) {
                $dayLabel = Carbon::parse($registration->registration_date)->format('d/m');
                $dailyRegistrations[$dayLabel] = $registration->registration_count;
            }

            $registrationStats[$monthName] = [
                'labels' => array_keys($dailyRegistrations),
                'data' => array_values($dailyRegistrations),
            ];

            // Fetch transaction data for each type (unchanged)
            for ($type = 1; $type <= 6; $type++) {
                $transactions = Transaction::selectRaw('DATE(created_at) as transaction_date, COUNT(*) as transaction_count')
                    ->where('type', $type)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->groupBy('transaction_date')
                    ->get();

                foreach ($transactions as $transaction) {
                    $dayLabel = Carbon::parse($transaction->transaction_date)->format('d/m');
                    $dailyTransactions[$type][$dayLabel] = $transaction->transaction_count;
                }
            }

            $transactionStats[$monthName] = [
                'labels' => array_keys($dailyRegistrations), // Same labels as registrations (days of the month)
                'data' => [
                    array_values($dailyTransactions[1]), // Type 1
                    array_values($dailyTransactions[2]), // Type 2
                    array_values($dailyTransactions[3]), // Type 3
                    array_values($dailyTransactions[4]), // Type 4
                    array_values($dailyTransactions[5]), // Type 5
                    array_values($dailyTransactions[6]), // Type 6
                ],
            ];
        }

        return view('admin.statistic.user-statistic', compact('registrationStats', 'transactionStats'));
    }

    public function userTransactionOverview()
    {
        $users = User::orderBy('point', 'desc')->get();
        return view('admin.statistic.user-transaction-overview', compact('users'));
    }

        public function userTransactionDetail($userId)
    {
        $user = User::findOrFail($userId);
        $transactionStats = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < 6; $i++) {
            $month = $currentDate->copy()->subMonths($i);
            $monthName = 'Tháng ' . $month->format('n') . '-' . $month->format('Y');
            $monthStart = $month->copy()->startOfMonth()->setTime(0, 0, 0);
            $monthEnd = $month->copy()->endOfMonth()->setTime(23, 59, 59);

            // For the current month, limit to today (May 18, 2025)
            if ($i === 0) {
                $monthEnd = Carbon::today()->setTime(23, 59, 59); // Today at 23:59:59
            }

            // Calculate days in the month for the chart
            $daysInMonth = $monthStart->diffInDays($monthEnd) + 1;
            $dailyDeposits = [
                1 => [], // Type 1: Nạp tiền
                5 => [], // Type 5: Tài liệu tải lên được duyệt
                6 => [], // Type 6: Tài liệu tải lên được mua
                'total' => [], // Total deposits
            ];
            $dailySpends = [
                2 => [], // Type 2: Mua tài liệu
                'total' => [], // Total spends
            ];

            // Initialize arrays for each day
            for ($day = 0; $day < $daysInMonth; $day++) {
                $dayDate = $monthStart->copy()->addDays($day);
                $dayLabel = $dayDate->format('d/m'); // e.g., "01/05"
                $dailyDeposits[1][$dayLabel] = 0; // Initialize Type 1
                $dailyDeposits[5][$dayLabel] = 0; // Initialize Type 5
                $dailyDeposits[6][$dayLabel] = 0; // Initialize Type 6
                $dailyDeposits['total'][$dayLabel] = 0; // Initialize total deposits
                $dailySpends[2][$dayLabel] = 0; // Initialize Type 2
                $dailySpends['total'][$dayLabel] = 0; // Initialize total spends
            }

            // Fetch deposit transactions (type 1 and 5)
            $deposits = Transaction::selectRaw('DATE(created_at) as transaction_date, type, SUM(amount) as total_amount')
                ->where('user_id', $userId)
                ->whereIn('type', [1, 5, 6])
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->groupBy('transaction_date', 'type')
                ->get();

            foreach ($deposits as $deposit) {
                $dayLabel = Carbon::parse($deposit->transaction_date)->format('d/m');
                $dailyDeposits[$deposit->type][$dayLabel] = $deposit->total_amount ?? 0;
                $dailyDeposits['total'][$dayLabel] += $deposit->total_amount ?? 0;
            }

            // Fetch spend transactions (type 2)
            $spends = Transaction::selectRaw('DATE(created_at) as transaction_date, type, SUM(amount) as total_amount')
                ->where('user_id', $userId)
                ->where('type', 2)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->groupBy('transaction_date', 'type')
                ->get();

            foreach ($spends as $spend) {
                $dayLabel = Carbon::parse($spend->transaction_date)->format('d/m');
                $dailySpends[$spend->type][$dayLabel] = $spend->total_amount ?? 0;
                $dailySpends['total'][$dayLabel] = $spend->total_amount ?? 0; // Since only Type 2, total = Type 2
            }

            $transactionStats[$monthName] = [
                'labels' => array_keys($dailyDeposits[1]), // Same labels for all (days of the month)
                'deposit_data' => [
                    array_values($dailyDeposits[1]), // Type 1
                    array_values($dailyDeposits[5]), // Type 5
                    array_values($dailyDeposits[6]), // Type 6
                    array_values($dailyDeposits['total']), // Total deposits
                ],
                'spend_data' => [
                    array_values($dailySpends[2]), // Type 2
                    array_values($dailySpends['total']), // Total spends
                ],
            ];
        }

        return view('admin.statistic.user-transaction-detail', compact('user', 'transactionStats'));
    }

    public function documentTransactionOverview()
    {
        // Get documents sorted by the number of transactions
        $documents = Document::select('documents.*')
            ->leftJoin('transactions', 'documents.id', '=', 'transactions.document_id')
            ->groupBy('documents.id')
            ->orderByRaw('COUNT(transactions.id) DESC')
            ->get();

        return view('admin.statistic.document-transaction-overview', compact('documents'));
    }

    public function documentTransactionDetail($documentId)
    {
        $document = Document::findOrFail($documentId);

        // Fetch transactions related to this document with user details
        $transactions = Transaction::where('document_id', $documentId)
            ->with('user') // Eager load user relationship
            ->orderBy('created_at', 'desc')
            ->get();

        // Prepare data for charts (last 6 months)
        $transactionStats = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < 6; $i++) {
            $month = $currentDate->copy()->subMonths($i);
            $monthName = 'Tháng ' . $month->format('n') . '-' . $month->format('Y');
            $monthStart = $month->copy()->startOfMonth()->setTime(0, 0, 0);
            $monthEnd = $month->copy()->endOfMonth()->setTime(23, 59, 59);

            // For the current month, limit to today (May 18, 2025, 08:30 AM +07)
            if ($i === 0) {
                $monthEnd = Carbon::today()->setTime(23, 59, 59); // Today at 23:59:59
            }

            // Calculate days in the month for the chart
            $daysInMonth = $monthStart->diffInDays($monthEnd) + 1;
            $dailyPurchases = []; // Points from purchases (type 2)
            $dailyDownloads = []; // Number of downloads (type 4)

            // Initialize arrays for each day
            for ($day = 0; $day < $daysInMonth; $day++) {
                $dayDate = $monthStart->copy()->addDays($day);
                $dayLabel = $dayDate->format('d/m'); // e.g., "01/05"
                $dailyPurchases[$dayLabel] = 0; // Initialize purchase points
                $dailyDownloads[$dayLabel] = 0; // Initialize download count
            }

            // Fetch purchase transactions (type 2) - Sum of points
            $purchases = Transaction::selectRaw('DATE(created_at) as transaction_date, SUM(amount) as total_amount')
                ->where('document_id', $documentId)
                ->where('type', 2) // Type 2: Mua tài liệu
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->groupBy('transaction_date')
                ->get();

            foreach ($purchases as $purchase) {
                $dayLabel = Carbon::parse($purchase->transaction_date)->format('d/m');
                $dailyPurchases[$dayLabel] = $purchase->total_amount ?? 0;
            }

            // Fetch download transactions (type 4) - Count of downloads
            $downloads = Transaction::selectRaw('DATE(created_at) as transaction_date, COUNT(*) as download_count')
                ->where('document_id', $documentId)
                ->where('type', 4) // Type 4: Tải xuống tài liệu
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->groupBy('transaction_date')
                ->get();

            foreach ($downloads as $download) {
                $dayLabel = Carbon::parse($download->transaction_date)->format('d/m');
                $dailyDownloads[$dayLabel] = $download->download_count ?? 0;
            }

            $transactionStats[$monthName] = [
                'labels' => array_keys($dailyPurchases), // Same labels for both (days of the month)
                'purchase_data' => array_values($dailyPurchases), // Points from purchases
                'download_data' => array_values($dailyDownloads), // Number of downloads
            ];
        }

        return view('admin.statistic.document-transaction-detail', compact('document', 'transactions', 'transactionStats'));
    }

}
