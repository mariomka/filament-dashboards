<div
    x-data="{}"
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('advanced-dashboards-for-filament-styles', package: 'mariomka/advanced-dashboards-for-filament'))]"
>
    <x-filament-panels::page class="fi-dashboard-page">
        @if ($this->showFiltersForm())
            <form wire:submit="$refresh">
                {{ $this->filtersForm }}
            </form>
        @endif

        <x-advanced-dashboards-for-filament::questions-grid
            :columns="$this->getColumns()"
            :rowHeight="$this->getRowHeight()"
            :data="
                [
                    ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                    ...$this->getWidgetData(),
                ]
            "
            :questions="$this->getQuestions()"
        />
    </x-filament-panels::page>
</div>
