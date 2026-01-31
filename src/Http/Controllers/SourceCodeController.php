<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\SourceCodeForm;
use QuangPhuc\WebsiteReseller\Http\Requests\SourceCodeRequest;
use QuangPhuc\WebsiteReseller\Models\SourceCode;
use QuangPhuc\WebsiteReseller\Services\SourceCodeService;
use QuangPhuc\WebsiteReseller\Tables\SourceCodeTable;

class SourceCodeController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Source Codes', route('website-reseller.source-codes.index'));
    }

    public function index(SourceCodeTable $dataTable)
    {
        $this->pageTitle('Source Codes');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Source Code');

        return SourceCodeForm::create()->renderForm();
    }

    public function store(SourceCodeRequest $request)
    {
        $form = SourceCodeForm::create();

        $form->saving(function (SourceCodeForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        if ($zipFile = $request->file('files')) {
            app(SourceCodeService::class)->publishSourceCode($zipFile, $form->getModel());
        }

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.source-codes.index')
            ->setNextRoute('website-reseller.source-codes.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(SourceCode $sourceCode)
    {
        $this->pageTitle('Edit Source Code: ' . $sourceCode->name);

        return SourceCodeForm::createFromModel($sourceCode)->renderForm();
    }

    public function update(SourceCode $sourceCode, SourceCodeRequest $request)
    {
        SourceCodeForm::createFromModel($sourceCode)->saving(function (SourceCodeForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        if ($zipFile = $request->file('files')) {
            app(SourceCodeService::class)->publishSourceCode($zipFile, $sourceCode);
        }

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.source-codes.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(SourceCode $sourceCode)
    {
        $response = DeleteResourceAction::make($sourceCode);

        app(SourceCodeService::class)->deleteSourceCode($sourceCode);

        return $response;
    }
}
