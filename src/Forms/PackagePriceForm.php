<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\PackagePriceRequest;
use QuangPhuc\WebsiteReseller\Models\Package;
use QuangPhuc\WebsiteReseller\Models\PackagePrice;

class PackagePriceForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(PackagePrice::class)
            ->setValidatorClass(PackagePriceRequest::class)
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
                    ->required()
            )
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'sequence',
                NumberField::class,
                NumberFieldOption::make()
                    ->label('Sequence')
                    ->defaultValue(0)
            )
            ->add(
                'payment_interval',
                TextField::class,
                TextFieldOption::make()
                    ->label('Payment Interval')
                    ->placeholder('e.g., monthly, yearly')
            )
            ->add(
                'price',
                NumberField::class,
                NumberFieldOption::make()
                    ->label('Price')
                    ->defaultValue(0)
            );
    }
}
