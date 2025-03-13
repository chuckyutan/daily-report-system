<?php

namespace App\Livewire;

use App\Models\FinalReport;
use Livewire\Component;

class FinalReportByBranch extends Component
{
    public $dateRange = null;
    public $userId = null;
    public $perPage = 10;

    protected $queryString = [
        'dateRange' => ['except' => ''],
        'userId' => ['except' => ''],
    ];

    public function render()
    {
        $query = FinalReport::query()->with('user');

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        if ($this->dateRange) {
            list($startDate, $endDate) = explode(' to ', $this->dateRange);
            $query->whereBetween('report_date', [$startDate, $endDate]);
        }

        $reports = $query->orderBy('report_date', 'desc')->get();

        return view('livewire.final-report-by-branch', [
            'reports' => $reports,
            'totalStats' => $this->calculateTotals($query),
        ]);
    }

    protected function calculateTotals($query)
    {
        $totals = (clone $query)->selectRaw('
            SUM(r_new_count) as total_r_new_count,
            SUM(r_new_volume) as total_r_new_volume,
            SUM(r_new_amount) as total_r_new_amount,
            SUM(r_continue_count) as total_r_continue_count,
            SUM(r_continue_volume) as total_r_continue_volume,
            SUM(r_continue_amount) as total_r_continue_amount,
            SUM(rgh_count) as total_rgh_count,
            SUM(rgh_amount) as total_rgh_amount,
            SUM(r_care_volume) as total_r_care_volume,
            SUM(filter_volume) as total_filter_volume,
            SUM(filter_amount) as total_filter_amount,
            SUM(house_goods_volume) as total_house_goods_volume,
            SUM(house_goods_amount) as total_house_goods_amount,
            SUM(care_count) as total_care_count,
            SUM(care_volume) as total_care_volume,
            SUM(care_amount) as total_care_amount
        ')->first();

        return $totals;
    }

    public function updatedDateRange()
    {
        $this->resetPage();
    }

    public function updatedUserId()
    {
        $this->resetPage();
    }
}