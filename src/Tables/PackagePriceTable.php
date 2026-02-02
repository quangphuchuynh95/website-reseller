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
                'wr_package_prices.id',
                'wr_package_prices.name',
                'wr_package_prices.package_id',
                'wr_package_prices.subscription_period_id',
                'wr_package_prices.sequence',
                'wr_package_prices.price',
                'wr_package_prices.created_at',
                'packages.name as package_name',
                'subscription_periods.name as subscription_period_name',
            ])
            ->leftJoin('wr_packages as packages', 'wr_package_prices.package_id', '=', 'packages.id')
            ->leftJoin('wr_subscription_periods as subscription_periods', 'wr_package_prices.subscription_period_id', '=', 'subscription_periods.id');

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('package_name')
                ->title('Package')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->package_name,
                    $column->getItem()->package_id,
                    'website-reseller.packages.edit'
                )),
            Column::make('name')
                ->title(trans('core/base::tables.name'))
                ->alignStart(),
            Column::make('subscription_period_name')
                ->title('Period')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->subscription_period_name,
                    $column->getItem()->subscription_period_id,
                    'website-reseller.subscription-periods.edit'
                )),
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

    protected function renderLink(?string $name, ?int $id, string $route): string
    {
        if (! $name || ! $id) {
            return '—';
        }

        $url = route($route, $id);

        return sprintf('<a href="%s">%s</a>', $url, e($name));
    }
}
