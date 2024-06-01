<?php

namespace Mariomka\AdvancedDashboardsForFilament\Dashboard;

use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Mariomka\AdvancedDashboardsForFilament\Questions\Question;
use Mariomka\AdvancedDashboardsForFilament\Questions\QuestionConfiguration;

abstract class AdvancedDashboard extends Page
{
    use HasFiltersForm;

    protected static string $view = 'advanced-dashboards-for-filament::dashboard.dashboard';

    public function getRowHeight(): string
    {
        return '120px';
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int|string|array
    {
        return 6;
    }

    public function getFiltersForm(): Form
    {
        if ((!$this->isCachingForms) && $this->hasCachedForm('filtersForm')) {
            return $this->getForm('filtersForm');
        }

        return $this->filtersForm($this->makeForm()
            ->columns([
                'md' => 2,
                'xl' => 3,
                '2xl' => 4,
            ])
            ->statePath('filters')
            ->debounce());
    }

    /**
     * @return array<class-string<Question> | QuestionConfiguration>
     */
    abstract public function getQuestions(): array;
}
