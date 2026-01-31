<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\PackageForm;
use QuangPhuc\WebsiteReseller\Http\Requests\PackageRequest;
use QuangPhuc\WebsiteReseller\Models\Package;
use QuangPhuc\WebsiteReseller\Tables\PackageTable;

class PackageController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Packages', route('website-reseller.packages.index'));
    }

    public function index(PackageTable $dataTable)
    {
        $this->pageTitle('Packages');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Package');

        return PackageForm::create()->renderForm();
    }

    public function store(PackageRequest $request)
    {
        $form = PackageForm::create();
        $form->saving(function (PackageForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.packages.index')
            ->setNextRoute('website-reseller.packages.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Package $package)
    {
        $this->pageTitle('Edit Package: ' . $package->name);

        return PackageForm::createFromModel($package)->renderForm();
    }

    public function update(Package $package, PackageRequest $request)
    {
        PackageForm::createFromModel($package)->saving(function (PackageForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.packages.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Package $package)
    {
        return DeleteResourceAction::make($package);
    }
}
