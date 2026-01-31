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
                'id',
                'name',
                'package_id',
                'package_price_id',
                'commit_price',
                'payment_interval',
                'start_at',
                'next_expires_at',
                'created_at',
            ])
            ->with(['package:id,name', 'packagePrice:id,name']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('name')
                ->title(trans('core/base::tables.name'))
                ->alignStart(),
            Column::make('package_id')
                ->title('Package')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $subscription = $column->getItem();
                    return $subscription->package?->name ?? '—';
                }),
            Column::make('package_price_id')
                ->title('Package Price')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $subscription = $column->getItem();
                    return $subscription->packagePrice?->name ?? '—';
                }),
            Column::make('commit_price')
                ->title('Price')
                ->alignStart(),
            Column::make('payment_interval')
                ->title('Interval')
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
}
