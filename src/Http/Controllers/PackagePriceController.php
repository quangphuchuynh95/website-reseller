<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\PackagePriceForm;
use QuangPhuc\WebsiteReseller\Http\Requests\PackagePriceRequest;
use QuangPhuc\WebsiteReseller\Models\PackagePrice;
use QuangPhuc\WebsiteReseller\Tables\PackagePriceTable;

class PackagePriceController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Package Prices', route('website-reseller.package-prices.index'));
    }

    public function index(PackagePriceTable $dataTable)
    {
        $this->pageTitle('Package Prices');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Package Price');

        return PackagePriceForm::create()->renderForm();
    }

    public function store(PackagePriceRequest $request)
    {
        $form = PackagePriceForm::create();
        $form->saving(function (PackagePriceForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.package-prices.index')
            ->setNextRoute('website-reseller.package-prices.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(PackagePrice $packagePrice)
    {
        $this->pageTitle('Edit Package Price');

        return PackagePriceForm::createFromModel($packagePrice)->renderForm();
    }

    public function update(PackagePrice $packagePrice, PackagePriceRequest $request)
    {
        PackagePriceForm::createFromModel($packagePrice)->saving(function (PackagePriceForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.package-prices.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(PackagePrice $packagePrice)
    {
        return DeleteResourceAction::make($packagePrice);
    }
}
