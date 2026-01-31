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
use QuangPhuc\WebsiteReseller\Models\Theme;

class ThemeTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Theme::class)
            ->addActions([
                EditAction::make()->route('website-reseller.themes.edit'),
                DeleteAction::make()->route('website-reseller.themes.destroy'),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('website-reseller.themes.create'));
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'preview_url',
                'source_code_id',
                'created_at',
            ])
            ->with(['packages:id,name', 'sourceCode:id,name']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('name')
                ->title(trans('core/base::tables.name'))
                ->alignStart(),
            Column::make('packages')
                ->title('Packages')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $theme = $column->getItem();
                    $packages = $theme->packages->pluck('name')->toArray();
                    return $packages ? implode(', ', $packages) : '—';
                }),
            Column::make('source_code_id')
                ->title('Source Code')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $theme = $column->getItem();
                    return $theme->sourceCode?->name ?? '—';
                }),
            Column::make('preview_url')
                ->title('Preview URL')
                ->alignStart()
                ->renderUsing(function (Column $column) {
                    $theme = $column->getItem();
                    if (!$theme->preview_url) {
                        return '—';
                    }
                    return '<a href="' . $theme->preview_url . '" target="_blank">' . $theme->preview_url . '</a>';
                }),
            CreatedAtColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('website-reseller.themes.destroy'),
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
