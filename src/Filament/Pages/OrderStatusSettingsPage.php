<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TomatoPHP\FilamentTypes\Components\TypeColumn;
use TomatoPHP\FilamentTypes\Models\Type;

class OrderStatusSettingsPage extends Page implements HasTable
{
    use InteractsWithTable;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected ?string $status = null;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    protected string $view = 'filament-ecommerce::settings.status';

    public array $data = [];

    public function mount(): void
    {
        $types = [
            [
                'name' => [
                    'en' => 'Pending',
                    'ar' => 'قيد الانتظار',
                ],
                'key' => 'pending',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Prepared',
                    'ar' => 'تم التحضير',
                ],
                'key' => 'prepared',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Withdrew',
                    'ar' => 'تم السحب',
                ],
                'key' => 'withdrew',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Shipped',
                    'ar' => 'تم الشحن',
                ],
                'key' => 'shipped',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Delivered',
                    'ar' => 'تم التوصيل',
                ],
                'key' => 'delivered',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Part Delivered',
                    'ar' => 'تم التوصيل جزئياً',
                ],
                'key' => 'part-delivered',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Cancelled',
                    'ar' => 'تم الإلغاء',
                ],
                'key' => 'cancelled',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Refunded',
                    'ar' => 'تم الاسترجاع',
                ],
                'key' => 'refunded',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Done',
                    'ar' => 'تم الانتهاء',
                ],
                'key' => 'done',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            [
                'name' => [
                    'en' => 'Paid',
                    'ar' => 'تم الدفع',
                ],
                'key' => 'paid',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
        ];

        foreach ($types as $type) {
            $exists = Type::query()
                ->where('for', 'orders')
                ->where('type', 'status')
                ->where('key', $type['key'])
                ->first();
            if (! $exists) {
                $type['for'] = 'orders';
                $type['type'] = 'status';
                $exists = Type::create($type);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        $tenant = Filament::getTenant();
        if ($tenant) {
            return [
                Action::make('back')->action(fn () => redirect()->route('filament.' . filament()->getCurrentOrDefaultPanel()->getId() . '.pages.settings-hub', $tenant))->color('danger')->label(trans('filament-settings-hub::messages.back')),
            ];
        }

        return [
            Action::make('back')->action(fn () => redirect()->route('filament.' . filament()->getCurrentOrDefaultPanel()->getId() . '.pages.settings-hub'))->color('danger')->label(trans('filament-settings-hub::messages.back')),
        ];

    }

    public function getTitle(): string
    {
        return trans('filament-ecommerce::messages.settings.status.title');
    }

    public function table(Table $table): Table
    {
        $localsTitle = [];
        foreach (FilamentEcommercePlugin::$locals ?? ['en'] as $locale) {
            $localsTitle[] = TextInput::make('name.' . $locale)
                ->label(trans('filament-ecommerce::messages.settings.status.columns.value') . ' [' . $locale . ']')
                ->required();
        }

        return $table->query(Type::query()->where('for', 'orders')->where('type', 'status'))
            ->paginated(false)
            ->columns([
                TypeColumn::make('key')
                    ->label(trans('filament-ecommerce::messages.settings.status.columns.status')),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(trans('filament-ecommerce::messages.settings.status.action.edit'))
                    ->tooltip(trans('filament-ecommerce::messages.settings.status.action.edit'))
                    ->schema([
                        ...$localsTitle,
                        IconPicker::make('icon')->label(trans('filament-ecommerce::messages.settings.status.columns.icon')),
                        ColorPicker::make('color')->label(trans('filament-ecommerce::messages.settings.status.columns.color')),
                    ])
                    ->fillForm(fn (Type $record) => [
                        'name' => $record->getTranslations('name'),
                        'icon' => $record->icon,
                        'color' => $record->color,
                    ])
                    ->icon('heroicon-s-pencil-square')
                    ->iconButton()
                    ->action(function (array $data, Type $record) {
                        $record->update($data);
                        Notification::make()
                            ->title(trans('filament-ecommerce::messages.settings.status.action.notification'))
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
