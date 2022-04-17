<?php

namespace App\Form\Components\Field;

use App\Form\Components\Concerns;
use App\Form\Components\Contracts;
use App\Form\Components\Field;
use Closure;

class MarkdownEditor extends Field implements Contracts\HasFileAttachments
{
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasFileAttachments;
    use Concerns\HasPlaceholder;
    use Concerns\InteractsWithToolbarButtons;

    protected string $view = 'forms::components.markdown-editor';

    protected array | Closure $toolbarButtons = [
        'attachFiles',
        'bold',
        'bulletList',
        'codeBlock',
        'edit',
        'italic',
        'link',
        'orderedList',
        'preview',
        'strike',
    ];
}
