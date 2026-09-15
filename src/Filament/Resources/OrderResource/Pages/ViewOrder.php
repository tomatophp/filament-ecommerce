<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->icon('heroicon-o-printer')
                ->label(trans('filament-ecommerce::messages.orders.actions.print'))
                ->openUrlInNewTab()
                ->url(route('order.print', $this->getRecord()->id)),
            DeleteAction::make()->icon('heroicon-o-trash'),
            EditAction::make()->icon('heroicon-o-pencil-square')->color('warning'),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('filament-ecommerce::orders.show')
                    ->viewData(['record' => $this->getRecord()]),
                $this->getRelationManagersContentComponent(),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['items'] = $this->getRecord()->ordersItems->toArray();

        return parent::mutateFormDataBeforeFill($data);
    }
}
