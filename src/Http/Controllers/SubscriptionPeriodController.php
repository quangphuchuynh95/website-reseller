<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use QuangPhuc\WebsiteReseller\Forms\SubscriptionPeriodForm;
use QuangPhuc\WebsiteReseller\Http\Requests\SubscriptionPeriodRequest;
use QuangPhuc\WebsiteReseller\Models\SubscriptionPeriod;
use QuangPhuc\WebsiteReseller\Tables\SubscriptionPeriodTable;

class SubscriptionPeriodController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add('Website Reseller')
            ->add('Subscription Periods', route('website-reseller.subscription-periods.index'));
    }

    public function index(SubscriptionPeriodTable $dataTable)
    {
        $this->pageTitle('Subscription Periods');

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle('Create Subscription Period');

        return SubscriptionPeriodForm::create()->renderForm();
    }

    public function store(SubscriptionPeriodRequest $request)
    {
        $form = SubscriptionPeriodForm::create();
        $form->saving(function (SubscriptionPeriodForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.subscription-periods.index')
            ->setNextRoute('website-reseller.subscription-periods.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(SubscriptionPeriod $subscriptionPeriod)
    {
        $this->pageTitle('Edit Subscription Period: ' . $subscriptionPeriod->name);

        return SubscriptionPeriodForm::createFromModel($subscriptionPeriod)->renderForm();
    }

    public function update(SubscriptionPeriod $subscriptionPeriod, SubscriptionPeriodRequest $request)
    {
        SubscriptionPeriodForm::createFromModel($subscriptionPeriod)->saving(function (SubscriptionPeriodForm $form) use ($request): void {
            $form->getModel()->fill($request->validated())->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('website-reseller.subscription-periods.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(SubscriptionPeriod $subscriptionPeriod)
    {
        return DeleteResourceAction::make($subscriptionPeriod);
    }
}
