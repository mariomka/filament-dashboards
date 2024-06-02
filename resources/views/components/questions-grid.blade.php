@props([
    'columns' => [
        'lg' => 6,
    ],
    'rowHeight' => '120px',
    'data' => [],
    'questions' => [],
])
@php
    use Mariomka\AdvancedDashboardsForFilament\Questions\QuestionConfiguration;
@endphp

<x-filament::grid
    :is-grid="true"
    :default="$columns['default'] ?? 6"
    :sm="$columns['sm'] ?? null"
    :md="$columns['md'] ?? null"
    :lg="$columns['lg'] ?? ($columns ? (is_array($columns) ? null : $columns) : 6)"
    :xl="$columns['xl'] ?? null"
    :two-xl="$columns['2xl'] ?? null"
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->class('gap-6')"
    :style="'grid-auto-rows: ' . $rowHeight"
>
    @php
        $normalizeQuestionConfiguration = function (string | QuestionConfiguration $question): QuestionConfiguration {
            if ($question instanceof QuestionConfiguration) {
                return $question;
            }

            return new QuestionConfiguration($question);
        };
    @endphp

    @foreach ($questions as $questionKey => $question)
        @php
            $question = $normalizeQuestionConfiguration($question);

            $cols = $question->getCols();
            $colsDefault = $cols['default'] ?? 3;
            $colsSm = $cols['sm'] ?? null;
            $colsMd = $cols['md'] ?? null;
            $colsLg = $cols['lg'] ?? ($cols ? (is_array($cols) ? null : $cols) : 3);
            $colsXl = $cols['xl'] ?? null;
            $cols2xl = $cols['2xl'] ?? null;

            $rows = $question->getRows();
            $rowsDefault = $rows['default'] ?? 3;
            $rowsSm = $rows['sm'] ?? null;
            $rowsMd = $rows['md'] ?? null;
            $rowsLg = $rows['lg'] ?? ($rows ? (is_array($rows) ? null : $rows) : 3);
            $rowsXl = $rows['xl'] ?? null;
            $rows2xl = $rows['2xl'] ?? null;
        @endphp

        <div
            {{
            $attributes
                ->class([
                    'col-[span_var(--col-span-default)_/span__var(--col-span-default)]' => $colsDefault,
                    'sm:col-[span_var(--col-span-sm)_/span__var(--col-span-sm)]' => $colsSm,
                    'md:col-[span_var(--col-span-md)_/span__var(--col-span-md)]' => $colsMd,
                    'lg:col-[span_var(--col-span-lg)_/span__var(--col-span-lg)]' => $colsLg,
                    'xl:col-[span_var(--col-span-xl)_/span__var(--col-span-xl)]' => $colsXl,
                    '2xl:col-[span_var(--col-span-2xl)_/span__var(--col-span-2xl)]' => $cols2xl,
                    'row-[span_var(--row-span-default)_/span__var(--row-span-default)]' => $rowsDefault,
                    'sm:row-[span_var(--row-span-sm)_/span__var(--row-span-sm)]' => $rowsSm,
                    'md:row-[span_var(--row-span-md)_/span__var(--row-span-md)]' => $rowsMd,
                    'lg:row-[span_var(--row-span-lg)_/span__var(--row-span-lg)]' => $rowsLg,
                    'xl:row-[span_var(--row-span-xl)_/span__var(--row-span-xl)]' => $rowsXl,
                    '2xl:row-[span_var(--row-span-2xl)_/span__var(--row-span-2xl)]' => $rows2xl,
                ])
                ->style([
                    "--col-span-default: {$colsDefault}" => $colsDefault,
                    "--col-span-sm: {$colsSm}" => $colsSm,
                    "--col-span-md: {$colsMd}" => $colsMd,
                    "--col-span-lg: {$colsLg}" => $colsLg,
                    "--col-span-xl: {$colsXl}" => $colsXl,
                    "--col-span-2xl: {$cols2xl}" => $cols2xl,
                    "--row-span-default: {$rowsDefault}" => $rowsDefault,
                    "--row-span-sm: {$rowsSm}" => $rowsSm,
                    "--row-span-md: {$rowsMd}" => $rowsMd,
                    "--row-span-lg: {$rowsLg}" => $rowsLg,
                    "--row-span-xl: {$rowsXl}" => $rowsXl,
                    "--row-span-2xl: {$rows2xl}" => $rows2xl,
                ])
        }}
        >
            @livewire(
                $question->question,
                [...$question->question::getDefaultProperties(), ...$question->getProperties(), ...$data],
                key("{$question->question}-{$questionKey}"),
            )
        </div>
    @endforeach
</x-filament::grid>