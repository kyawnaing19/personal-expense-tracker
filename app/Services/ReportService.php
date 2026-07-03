<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Carbon\Carbon;

class ReportService
{
    public function __construct(
        private ReportRepository $reportRepository

    ) {}

    public function getDataRange(array $filters = []): array
    {
        $filter = $filters['filter'] ?? 'this_month';

        return match ($filter) {
            'today' => [
                'from' => Carbon::today()->toDateString(),
                'to' => Carbon::today()->toDateString()],
            'yesterday' => [
                'from' => Carbon::yesterday()->toDateString(),
                'to' => Carbon::yesterday()->toDateString()],
            'this_month' => [
                'from' => Carbon::now()->startOfMonth()->toDateString(),
                'to' => Carbon::now()->endOfMonth()->toDateString()],
            'last_month' => [
                'from' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                'to' => Carbon::now()->subMonth()->endOfMonth()->toDateString()],
            'custom' => [
                'from' => $filters['from'],
                'to' => $filters['to']],
            default => [
                'from' => Carbon::now()->startOfMonth()->toDateString(),
                'to' => Carbon::now()->endOfMonth()->toDateString(),
            ]
        };
    }

    public function getSummary(string $userId, array $filter = [])
    {
        $dataRange = $this->getDataRange($filter);

        return $this->reportRepository->getSummary($userId, $dataRange);
    }

    public function getCategoryBreakdown(string $userId, array $filter = [])
    {
        $dataRange = $this->getDataRange($filter);

        return $this->reportRepository->getCategoryBreakdown($userId, $dataRange);
    }

    public function getBudgetOverview(string $userId, array $filter = [])
    {
        $month = $filter['month'];
        $year = $filter['year'];

        return $this->reportRepository->getBudgetOverview($userId, $month, $year);
    }
}
