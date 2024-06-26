<?php

namespace Mariomka\AdvancedDashboardsForFilament;

use Filament\Panel;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\View;
use Livewire\Features\SupportTesting\Testable;
use Mariomka\AdvancedDashboardsForFilament\Commands\MakeAdvancedDashboardCommand;
use Mariomka\AdvancedDashboardsForFilament\Commands\MakeQuestionCommand;
use Mariomka\AdvancedDashboardsForFilament\Dashboard\AdvancedDashboard;
use Mariomka\AdvancedDashboardsForFilament\Questions\Question;
use Mariomka\AdvancedDashboardsForFilament\Testing\TestsAdvancedDashboardsForFilament;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AdvancedDashboardsForFilamentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'advanced-dashboards-for-filament';

    public static string $viewNamespace = 'advanced-dashboards-for-filament';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands());

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        Panel::macro('discoverQuestions', function (string $in, string $for): Panel {
            /** @var Panel $this */
            if ($this->hasCachedComponents()) {
                return $this;
            }

            $this->questionsDirectories[] = $in;
            $this->questionsNamespaces[] = $for;

            $questions = [];

            $this->discoverComponents(
                Question::class,
                $questions,
                directory: $in,
                namespace: $for,
            );

            return $this;
        });

        Panel::macro('getQuestionDirectories', function (): array {
            return $this->questionDirectories ?? [];
        });

        Panel::macro('getQuestionNamespaces', function (): array {
            return $this->questionNamespaces ?? [];
        });
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        FilamentIcon::register($this->getIcons());

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_ACTIONS_BEFORE,
            fn (): View => view('advanced-dashboards-for-filament::dashboard.header-loading-indicator-start'),
            scopes: AdvancedDashboard::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_ACTIONS_AFTER,
            fn (): View => view('advanced-dashboards-for-filament::dashboard.header-loading-indicator-end'),
            scopes: AdvancedDashboard::class,
        );

        if (app()->runningInConsole() && file_exists(__DIR__ . '/../stubs/')) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/advanced-dashboards-for-filament/{$file->getFilename()}"),
                ], 'advanced-dashboards-for-filament-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsAdvancedDashboardsForFilament());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'mariomka/advanced-dashboards-for-filament';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('advanced-dashboards-for-filament', __DIR__ . '/../resources/dist/components/advanced-dashboards-for-filament.js'),
            Css::make('advanced-dashboards-for-filament-styles', __DIR__ . '/../resources/dist/advanced-dashboards-for-filament.css')
                ->loadedOnRequest(),
            // Js::make('advanced-dashboards-for-filament-scripts', __DIR__ . '/../resources/dist/advanced-dashboards-for-filament.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MakeAdvancedDashboardCommand::class,
            MakeQuestionCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [];
    }
}
