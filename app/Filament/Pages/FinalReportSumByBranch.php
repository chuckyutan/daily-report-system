<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use Filament\Pages\Page;

class FinalReportSumByBranch extends Page
{
    protected static ?string $slug = 'final-report-sum-by-branch/{branch}';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.final-report-sum-by-branch';

    protected static ?string $navigationLabel = null;

    protected static bool $shouldRegisterNavigation = false;

    public Branch $branch;
}
