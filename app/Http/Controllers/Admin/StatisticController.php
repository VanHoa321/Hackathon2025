<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentComment;
use App\Models\Favourite;
use App\Models\Rating;
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
}
