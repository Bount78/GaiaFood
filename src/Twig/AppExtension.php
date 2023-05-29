<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\TextContentService;

class AppExtension extends AbstractExtension
{
    private $textContentService;

    public function __construct(TextContentService $textContentService)
    {
        $this->textContentService = $textContentService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('text_content', [$this, 'getTextContent']),
        ];
    }

    public function getTextContent(string $entryPoint): ?string
    {
        return $this->textContentService->getTextContent($entryPoint);
    }
}
