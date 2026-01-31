<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\WebsiteRequest;
use QuangPhuc\WebsiteReseller\Models\SourceCode;
use QuangPhuc\WebsiteReseller\Models\Subscription;
use QuangPhuc\WebsiteReseller\Models\Theme;
use QuangPhuc\WebsiteReseller\Models\Website;

class WebsiteForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Website::class)
            ->setValidatorClass(WebsiteRequest::class)
            ->add(
                'domain',
                TextField::class,
                TextFieldOption::make()
                    ->label('Domain')
                    ->placeholder('example.com')
                    ->required()
            )
            ->add(
                'subscription_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Subscription')
                    ->choices(function () {
                        return ['' => 'Select a subscription'] + Subscription::query()
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->allowClear()
            )
            ->add(
                'theme_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Theme')
                    ->choices(function () {
                        return ['' => 'Select a theme'] + Theme::query()
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->allowClear()
            )
            ->add(
                'source_code_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Source Code')
                    ->choices(function () {
                        return ['' => 'Select source code'] + SourceCode::query()
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->allowClear()
            )
            ->add('status', SelectField::class, StatusFieldOption::make());
    }
}
