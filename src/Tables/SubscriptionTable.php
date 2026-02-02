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
use QuangPhuc\WebsiteReseller\Models\Subscription;

class SubscriptionTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Subscription::class)
            ->addActions([
                EditAction::make()->route('website-reseller.subscriptions.edit'),
                DeleteAction::make()->route('website-reseller.subscriptions.destroy'),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('website-reseller.subscriptions.create'));
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'wr_subscriptions.id',
                'wr_subscriptions.name',
                'wr_subscriptions.package_id',
                'wr_subscriptions.package_price_id',
                'wr_subscriptions.subscription_period_id',
                'wr_subscriptions.commit_price',
                'wr_subscriptions.start_at',
                'wr_subscriptions.next_expires_at',
                'wr_subscriptions.created_at',
                'packages.name as package_name',
                'package_prices.name as package_price_name',
                'subscription_periods.name as subscription_period_name',
            ])
            ->leftJoin('wr_packages as packages', 'wr_subscriptions.package_id', '=', 'packages.id')
            ->leftJoin('wr_package_prices as package_prices', 'wr_subscriptions.package_price_id', '=', 'package_prices.id')
            ->leftJoin('wr_subscription_periods as subscription_periods', 'wr_subscriptions.subscription_period_id', '=', 'subscription_periods.id');

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('name')
                ->title(trans('core/base::tables.name'))
                ->alignStart(),
            Column::make('package_name')
                ->title('Package')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->package_name,
                    $column->getItem()->package_id,
                    'website-reseller.packages.edit'
                )),
            Column::make('package_price_name')
                ->title('Package Price')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->package_price_name,
                    $column->getItem()->package_price_id,
                    'website-reseller.package-prices.edit'
                )),
            Column::make('subscription_period_name')
                ->title('Period')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->subscription_period_name,
                    $column->getItem()->subscription_period_id,
                    'website-reseller.subscription-periods.edit'
                )),
            Column::make('commit_price')
                ->title('Price')
                ->alignStart(),
            Column::make('next_expires_at')
                ->title('Next Expiry')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $subscription = $column->getItem();
                    return $subscription->next_expires_at?->format('Y-m-d') ?? '—';
                }),
            CreatedAtColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('website-reseller.subscriptions.destroy'),
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
