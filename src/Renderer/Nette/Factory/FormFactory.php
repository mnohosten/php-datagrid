<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\Renderer\Nette\Factory;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\Checkbox;

class FormFactory
{
    public function create(): Form
    {
        $form = new Form();
        $form->onRender[] = fn(Form $form) => $this->setRenderer($form);
        return $form;
    }

    private function setRenderer(Form $form): void
    {
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = null;
        $renderer->wrappers['pair']['container'] = 'div class="form-group row"';
        $renderer->wrappers['pair']['.error'] = 'has-danger';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 col-form-label"';
        $renderer->wrappers['control']['description'] = 'span class=form-text';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=form-control-feedback';
        $renderer->wrappers['control']['.error'] = 'is-invalid';


        foreach ($form->getControls() as $control) {
            $type = $control->getOption('type');
            if (in_array($type, ['button', 'submit'])) {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-success' : 'btn btn-secondary');
                $usedPrimary = true;
            } elseif (in_array($type, ['text', 'textarea', 'select'], true)) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif ($type === 'file') {
                $control->getControlPrototype()->addClass('form-control-file');
            } elseif (in_array($type, ['checkbox', 'radio'], true)) {
                if ($control instanceof Checkbox) {
                    $control->getLabelPrototype()->addClass('form-check-label');
                } else {
                    $control->getItemLabelPrototype()->addClass('form-check-label');
                }
                $control->getControlPrototype()->addClass('form-check-input');
                $control->getSeparatorPrototype()->setName('div')->addClass('form-check');
            }
        }
    }
}
