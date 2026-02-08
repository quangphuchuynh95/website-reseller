<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\FileFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\FileField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Illuminate\Support\Facades\File;
use QuangPhuc\WebsiteReseller\Http\Requests\SourceCodeRequest;
use QuangPhuc\WebsiteReseller\Models\SourceCode;

class SourceCodeForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->hasFiles()
            ->model(SourceCode::class)
            ->setValidatorClass(SourceCodeRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'slug',
                TextField::class,
                TextFieldOption::make()
                    ->label('Slug')
                    ->required()
            )
            ->add(
                'files',
                FileField::class,
                FileFieldOption::make()
                    ->label('Source Code Archive')
                    ->helperText($this->getFilesHelperText())
            )
            ->add(
                'caddy_template',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label('Caddy Template')
                    ->rows(10)
            )
            ->add(
                'setup_command',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label('Setup command (Bash)')
                    ->rows(10)
            );
    }

    protected function getFilesHelperText(): string
    {
        $model = $this->getModel();

        if ($model && $model->slug) {
            $sourcePath = config('plugins.website-reseller.source-code.base') . '/' . $model->slug;

            if (File::isDirectory($sourcePath)) {
                return sprintf(
                    'Current path: <code>%s</code><br>Leave empty if you don\'t want to change the files.',
                    $sourcePath
                );
            }
        }

        return 'Upload a zip file containing the source code (Max: 500MB)';
    }
}
