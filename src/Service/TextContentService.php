<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TextContent;

class TextContentService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTextContent(string $entryPoint): ?string
    {
        $textContent = $this->entityManager->getRepository(TextContent::class)->findOneBy(['entryPoint' => $entryPoint]);

        return $textContent ? $textContent->getValue() : null;
    }
}
