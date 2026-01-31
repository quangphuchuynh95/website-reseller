<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\WebsiteForm;
use QuangPhuc\WebsiteReseller\Http\Requests\WebsiteRequest;
use QuangPhuc\WebsiteReseller\Models\Website;
use QuangPhuc\WebsiteReseller\Tables\WebsiteTable;

class WebsiteController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Websites', route('website-reseller.websites.index'));
    }

    public function index(WebsiteTable $dataTable)
    {
        $this->pageTitle('Websites');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Website');

        return WebsiteForm::create()->renderForm();
    }

    public function store(WebsiteRequest $request)
    {
        $form = WebsiteForm::create();
        $form->saving(function (WebsiteForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.websites.index')
            ->setNextRoute('website-reseller.websites.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Website $website)
    {
        $this->pageTitle('Edit Website: ' . $website->domain);

        return WebsiteForm::createFromModel($website)->renderForm();
    }

    public function update(Website $website, WebsiteRequest $request)
    {
        WebsiteForm::createFromModel($website)->saving(function (WebsiteForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.websites.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Website $website)
    {
        return DeleteResourceAction::make($website);
    }
}
