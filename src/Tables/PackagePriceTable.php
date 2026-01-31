<?php

namespace QuangPhuc\WebsiteReseller\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use QuangPhuc\WebsiteReseller\Models\PackagePrice;

class PackagePriceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(PackagePrice::class)
            ->addActions([
                EditAction::make()->route('website-reseller.package-prices.edit'),
                DeleteAction::make()->route('website-reseller.package-prices.destroy'),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('website-reseller.package-prices.create'));
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'package_id',
                'name',
                'sequence',
                'payment_interval',
                'price',
                'created_at',
            ])
            ->with(['package:id,name']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('package_id')
                ->title('Package')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $price = $column->getItem();
                    return $price->package?->name ?? '—';
                }),
            Column::make('name')
                ->title(trans('core/base::tables.name'))
                ->alignStart(),
            Column::make('payment_interval')
                ->title('Payment Interval')
                ->alignStart(),
            Column::make('price')
                ->title('Price')
                ->alignStart(),
            Column::make('sequence')
                ->title('Sequence')
                ->alignStart(),
            CreatedAtColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('website-reseller.package-prices.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            CreatedAtBulkChange::make(),
        ];
    }
}
