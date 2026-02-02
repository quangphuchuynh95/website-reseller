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
                'wr_websites.id',
                'wr_websites.domain',
                'wr_websites.subscription_id',
                'wr_websites.theme_id',
                'wr_websites.source_code_id',
                'wr_websites.status',
                'wr_websites.created_at',
                'subscriptions.name as subscription_name',
                'themes.name as theme_name',
                'source_codes.name as source_code_name',
            ])
            ->leftJoin('wr_subscriptions as subscriptions', 'wr_websites.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('wr_themes as themes', 'wr_websites.theme_id', '=', 'themes.id')
            ->leftJoin('wr_source_codes as source_codes', 'wr_websites.source_code_id', '=', 'source_codes.id');

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('domain')
                ->title('Domain')
                ->alignStart(),
            Column::make('subscription_name')
                ->title('Subscription')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->subscription_name,
                    $column->getItem()->subscription_id,
                    'website-reseller.subscriptions.edit'
                )),
            Column::make('theme_name')
                ->title('Theme')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->theme_name,
                    $column->getItem()->theme_id,
                    'website-reseller.themes.edit'
                )),
            Column::make('source_code_name')
                ->title('Source Code')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->source_code_name,
                    $column->getItem()->source_code_id,
                    'website-reseller.source-codes.edit'
                )),
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

    protected function renderLink(?string $name, ?int $id, string $route): string
    {
        if (! $name || ! $id) {
            return '—';
        }

        $url = route($route, $id);

        return sprintf('<a href="%s">%s</a>', $url, e($name));
    }
}
