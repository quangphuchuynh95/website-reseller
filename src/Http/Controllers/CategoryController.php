<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\CategoryForm;
use QuangPhuc\WebsiteReseller\Http\Requests\CategoryRequest;
use QuangPhuc\WebsiteReseller\Models\Category;
use QuangPhuc\WebsiteReseller\Tables\CategoryTable;

class CategoryController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Categories', route('website-reseller.categories.index'));
    }

    public function index(CategoryTable $dataTable)
    {
        $this->pageTitle('Categories');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Category');

        return CategoryForm::create()->renderForm();
    }

    public function store(CategoryRequest $request)
    {
        $form = CategoryForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.categories.index')
            ->setNextRoute('website-reseller.categories.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Category $category)
    {
        $this->pageTitle('Edit Category: ' . $category->name);

        return CategoryForm::createFromModel($category)->renderForm();
    }

    public function update(Category $category, CategoryRequest $request)
    {
        CategoryForm::createFromModel($category)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.categories.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Category $category)
    {
        return DeleteResourceAction::make($category);
    }
}
