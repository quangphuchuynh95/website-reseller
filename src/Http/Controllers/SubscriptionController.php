<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\SubscriptionForm;
use QuangPhuc\WebsiteReseller\Http\Requests\SubscriptionRequest;
use QuangPhuc\WebsiteReseller\Models\Subscription;
use QuangPhuc\WebsiteReseller\Tables\SubscriptionTable;

class SubscriptionController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Subscriptions', route('website-reseller.subscriptions.index'));
    }

    public function index(SubscriptionTable $dataTable)
    {
        $this->pageTitle('Subscriptions');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Subscription');

        return SubscriptionForm::create()->renderForm();
    }

    public function store(SubscriptionRequest $request)
    {
        $form = SubscriptionForm::create();
        $form->saving(function (SubscriptionForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.subscriptions.index')
            ->setNextRoute('website-reseller.subscriptions.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Subscription $subscription)
    {
        $this->pageTitle('Edit Subscription: ' . $subscription->name);

        return SubscriptionForm::createFromModel($subscription)->renderForm();
    }

    public function update(Subscription $subscription, SubscriptionRequest $request)
    {
        SubscriptionForm::createFromModel($subscription)->saving(function (SubscriptionForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.subscriptions.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Subscription $subscription)
    {
        return DeleteResourceAction::make($subscription);
    }
}
