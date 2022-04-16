<?php

namespace App\Forms\Concerns;

use App\Forms\Components\Contracts\HasFileAttachments;
use App\Forms\Components\Field;

trait SupportsComponentFileAttachments
{
    public function getComponentFileAttachmentUrl(string $statePath): ?string
    {
        foreach ($this->getComponents() as $component) {
            if ($component instanceof HasFileAttachments && $component instanceof Field && $component->getStatePath() === $statePath) {
                $attachment = $this->getLivewire()->getComponentFileAttachment($statePath);

                if (! $attachment) {
                    return null;
                }

                return $component->saveUploadedFileAttachment($attachment);
            }

            foreach ($component->getChildComponentContainers() as $container) {
                if ($container->isHidden()) {
                    continue;
                }

                if ($url = $container->getComponentFileAttachmentUrl($statePath)) {
                    return $url;
                }
            }
        }

        return null;
    }
}
