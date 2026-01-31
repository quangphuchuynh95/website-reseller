<?php

namespace QuangPhuc\WebsiteReseller\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use QuangPhuc\WebsiteReseller\Models\Website;

class WebsiteTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Website::class)
            ->addActions([
                EditAction::make()->route('website-reseller.websites.edit'),
                DeleteAction::make()->route('website-reseller.websites.destroy'),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('website-reseller.websites.create'));
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'domain',
                'subscription_id',
                'theme_id',
                'source_code_id',
                'status',
                'created_at',
            ])
            ->with(['subscription:id,name', 'theme:id,name', 'sourceCode:id,name']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('domain')
                ->title('Domain')
                ->alignStart(),
            Column::make('subscription_id')
                ->title('Subscription')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $website = $column->getItem();
                    return $website->subscription?->name ?? '—';
                }),
            Column::make('theme_id')
                ->title('Theme')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $website = $column->getItem();
                    return $website->theme?->name ?? '—';
                }),
            Column::make('source_code_id')
                ->title('Source Code')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $website = $column->getItem();
                    return $website->sourceCode?->name ?? '—';
                }),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('website-reseller.websites.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            StatusBulkChange::make(),
            CreatedAtBulkChange::make(),
        ];
    }
}
