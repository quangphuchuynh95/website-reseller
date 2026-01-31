<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\PackageRequest;
use QuangPhuc\WebsiteReseller\Models\Package;

class PackageForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Package::class)
            ->setValidatorClass(PackageRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add(
                'content',
                EditorField::class,
                EditorFieldOption::make()
                    ->label('Content')
            )
            ->add(
                'sequence',
                NumberField::class,
                NumberFieldOption::make()
                    ->label('Sequence')
                    ->defaultValue(0)
            )
            ->add(
                'features',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label('Features (JSON)')
                    ->rows(5)
                    ->helperText('Enter features as JSON array, e.g., ["Feature 1", "Feature 2"]')
            );
    }
}
