<?php

namespace QuangPhuc\WebsiteReseller\Forms;

use Botble\Base\Forms\FieldOptions\FileFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\FileField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use QuangPhuc\WebsiteReseller\Http\Requests\ThemeRequest;
use QuangPhuc\WebsiteReseller\Models\Category;
use QuangPhuc\WebsiteReseller\Models\Package;
use QuangPhuc\WebsiteReseller\Models\SourceCode;
use QuangPhuc\WebsiteReseller\Models\Theme;

class ThemeForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->hasFiles()
            ->model(Theme::class)
            ->setValidatorClass(ThemeRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'preview_url',
                TextField::class,
                TextFieldOption::make()
                    ->label('Preview URL')
                    ->placeholder('https://example.com/preview')
            )
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label('Image')
            )
            ->add(
                'packages',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Packages')
                    ->choices(Package::query()->pluck('name', 'id')->all())
                    ->selected($this->getModel() ? $this->getModel()->packages->pluck('id')->all() : [])
                    ->searchable()
                    ->multiple()
            )
            ->add(
                'categories',
                SelectField::class,
                SelectFieldOption::make()
                    ->label('Categories')
                    ->choices(Category::query()->pluck('name', 'id')->all())
                    ->selected($this->getModel() ? $this->getModel()->categories->pluck('id')->all() : [])
                    ->searchable()
                    ->multiple()
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
            ->add(
                'database_file',
                FileField::class,
                FileFieldOption::make()
                    ->label('Database File')
                    ->helperText($this->getDatabaseFileHelperText())
            );
    }

    protected function getDatabaseFileHelperText(): string
    {
        $model = $this->getModel();

        if ($model && $model->database_file) {
            $downloadUrl = route('website-reseller.themes.download-database', $model->id);
            $fileName = basename($model->database_file);

            return sprintf(
                'Current file: <a href="%s" target="_blank">%s</a><br>Leave empty if you don\'t want to change the file.',
                $downloadUrl,
                $fileName
            );
        }

        return 'Upload an SQL file for theme database (Max: 100MB)';
    }
}
