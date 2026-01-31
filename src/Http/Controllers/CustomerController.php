<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\CustomerForm;
use QuangPhuc\WebsiteReseller\Http\Requests\CustomerRequest;
use QuangPhuc\WebsiteReseller\Models\Customer;
use QuangPhuc\WebsiteReseller\Tables\CustomerTable;

class CustomerController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Customers', route('website-reseller.customers.index'));
    }

    public function index(CustomerTable $dataTable)
    {
        $this->pageTitle('Customers');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Customer');

        return CustomerForm::create()->renderForm();
    }

    public function store(CustomerRequest $request)
    {
        $form = CustomerForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.customers.index')
            ->setNextRoute('website-reseller.customers.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Customer $customer)
    {
        $this->pageTitle('Edit Customer: ' . $customer->name);

        return CustomerForm::createFromModel($customer)->renderForm();
    }

    public function update(Customer $customer, CustomerRequest $request)
    {
        CustomerForm::createFromModel($customer)->saving(function (CustomerForm $form) use ($request): void {
            $data = $request->validated();

            // Only update password if provided
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $form->getModel()->fill($data)->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.customers.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Customer $customer)
    {
        return DeleteResourceAction::make($customer);
    }
}
