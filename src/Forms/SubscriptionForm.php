<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\SubscriptionRequest;
use QuangPhuc\WebsiteReseller\Models\Package;
use QuangPhuc\WebsiteReseller\Models\PackagePrice;
use QuangPhuc\WebsiteReseller\Models\Subscription;
use QuangPhuc\WebsiteReseller\Models\SubscriptionPeriod;

class SubscriptionForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Subscription::class)
            ->setValidatorClass(SubscriptionRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'domain',
                TextField::class,
                TextFieldOption::make()
                    ->label('Domain')
                    ->placeholder('example.com')
            )
            ->add(
                'package_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Package')
                    ->choices(function () {
                        return ['' => 'Select a package'] + Package::query()
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->allowClear()
            )
            ->add(
                'package_price_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Package Price')
                    ->choices(function () {
                        return ['' => 'Select a package price'] + PackagePrice::query()
                            ->with('package')
                            ->get()
                            ->mapWithKeys(function ($price) {
                                return [$price->id => ($price->package->name ?? '') . ' - ' . $price->name];
                            })
                            ->all();
                    })
                    ->searchable()
                    ->allowClear()
            )
            ->add(
                'subscription_period_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Subscription Period')
                    ->choices(function () {
                        return ['' => 'Select a period'] + SubscriptionPeriod::query()
                            ->orderBy('sequence')
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->allowClear()
            )
            ->add(
                'commit_price',
                NumberField::class,
                NumberFieldOption::make()
                    ->label('Commit Price')
                    ->defaultValue(0)
            )
            ->add(
                'start_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label('Start Date')
            )
            ->add(
                'next_expires_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label('Next Expiry Date')
            );
    }
}
