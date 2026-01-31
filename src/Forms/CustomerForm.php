<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\CustomerRequest;
use QuangPhuc\WebsiteReseller\Models\Customer;

class CustomerForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->setValidatorClass(CustomerRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->required()
                    ->maxLength(255)
            )
            ->add(
                'password',
                'password',
                TextFieldOption::make()
                    ->label('Password')
                    ->required($this->getModel() ? false : true)
                    ->helperText($this->getModel() ? 'Leave blank to keep current password' : null)
            );
    }
}
