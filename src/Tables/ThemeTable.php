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
                'wr_themes.id',
                'wr_themes.name',
                'wr_themes.source_code_id',
                'wr_themes.preview_url',
                'wr_themes.created_at',
                'source_codes.name as source_code_name',
            ])
            ->leftJoin('wr_source_codes as source_codes', 'wr_themes.source_code_id', '=', 'source_codes.id')
            ->with(['packages:id,name']);

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
                ->orderable(false)
                ->searchable(false)
                ->renderUsing(function (Column $column) {
                    $theme = $column->getItem();
                    $links = $theme->packages->map(fn ($package) => sprintf(
                        '<a href="%s">%s</a>',
                        route('website-reseller.packages.edit', $package->id),
                        e($package->name)
                    ))->toArray();

                    return $links ? implode(', ', $links) : '—';
                }),
            Column::make('source_code_name')
                ->title('Source Code')
                ->alignStart()
                ->renderUsing(fn (Column $column) => $this->renderLink(
                    $column->getItem()->source_code_name,
                    $column->getItem()->source_code_id,
                    'website-reseller.source-codes.edit'
                )),
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

    protected function renderLink(?string $name, ?int $id, string $route): string
    {
        if (! $name || ! $id) {
            return '—';
        }

        $url = route($route, $id);

        return sprintf('<a href="%s">%s</a>', $url, e($name));
    }
}
