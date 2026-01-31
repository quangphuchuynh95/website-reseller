<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\SubscriptionPeriodRequest;
use QuangPhuc\WebsiteReseller\Models\SubscriptionPeriod;

class SubscriptionPeriodForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(SubscriptionPeriod::class)
            ->setValidatorClass(SubscriptionPeriodRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'interval_value',
                TextField::class,
                TextFieldOption::make()
                    ->label('Interval Value (ISO 8601 Duration)')
                    ->placeholder('e.g., P1D, P1W, P1M, P3M, P1Y')
                    ->helperText('Use ISO 8601 duration format: P1D (1 day), P1W (1 week), P1M (1 month), P3M (3 months), P1Y (1 year)')
                    ->required()
            )
            ->add(
                'sequence',
                NumberField::class,
                NumberFieldOption::make()
                    ->label('Sequence')
                    ->defaultValue(0)
                    ->helperText('Order of appearance in listings (lower numbers appear first)')
            );
    }
}
