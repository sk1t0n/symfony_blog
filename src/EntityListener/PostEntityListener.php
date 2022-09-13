<?php

namespace App\EntityListener;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PostEntityListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $adminUsername
    ) {
    }

    /**
     * @throws \RuntimeException
     */
    public function prePersist(Post $post): void
    {
        $author = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $this->adminUsername]);
        if ($author) {
            $post->setAuthor($author);
        } else {
            throw new \RuntimeException('Incorrect admin username sent.');
        }
    }
}
