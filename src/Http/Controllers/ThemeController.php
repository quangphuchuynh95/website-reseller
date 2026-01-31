<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Illuminate\Support\Arr;
use QuangPhuc\WebsiteReseller\Forms\ThemeForm;
use QuangPhuc\WebsiteReseller\Http\Requests\ThemeRequest;
use QuangPhuc\WebsiteReseller\Models\Theme;
use QuangPhuc\WebsiteReseller\Tables\ThemeTable;

class ThemeController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Themes', route('website-reseller.themes.index'));
    }

    public function index(ThemeTable $dataTable)
    {
        $this->pageTitle('Themes');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Theme');

        return ThemeForm::create()->renderForm();
    }

    public function store(ThemeRequest $request)
    {
        $form = ThemeForm::create();
        $form->saving(function (ThemeForm $form) use ($request): void {
            $data = $request->validated();
            $packages = Arr::pull($data, "packages", default: []);
            $categories = Arr::pull($data, "categories", default: []);
            Arr::forget($data, 'database_file');

            $form->getModel()->fill($data)->save();
            $form->getModel()->packages()->sync($packages);
            $form->getModel()->categories()->sync($categories);
        });

        if ($databaseFile = $request->file('database_file')) {
            $filename = "website_{$form->getModel()->id}.sql";
            $path = $databaseFile->storeAs('themes/databases', $filename);
            $form->getModel()->update(['database_file' => $path]);
        }

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.themes.index')
            ->setNextRoute('website-reseller.themes.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Theme $theme)
    {
        $this->pageTitle('Edit Theme: ' . $theme->name);

        return ThemeForm::createFromModel($theme)->renderForm();
    }

    public function update(Theme $theme, ThemeRequest $request)
    {
        ThemeForm::createFromModel($theme)->saving(function (ThemeForm $form) use ($request): void {
            $data = $request->validated();
            $packages = Arr::pull($data, "packages", default: []);
            $categories = Arr::pull($data, "categories", default: []);
            Arr::forget($data, 'database_file');

            $form->getModel()->fill($data)->save();
            $form->getModel()->packages()->sync($packages);
            $form->getModel()->categories()->sync($categories);
        });

        if ($databaseFile = $request->file('database_file')) {
            $filename = "website_{$theme->id}.sql";
            $path = $databaseFile->storeAs('themes/databases', $filename);
            $theme->update(['database_file' => $path]);
        }

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.themes.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Theme $theme)
    {
        return DeleteResourceAction::make($theme);
    }
}
