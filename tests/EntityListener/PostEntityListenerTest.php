<?php

namespace App\Tests\EntityListener;

use App\Entity\Post;
use App\EntityListener\PostEntityListener;
use App\Factory\PostFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class PostEntityListenerTest extends KernelTestCase
{
    use Factories;

    private PostEntityListener $postEntityListener;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postEntityListener = self::getContainer()->get(PostEntityListener::class);
    }

    public function testPrePersistWithCorrectUsername(): void
    {
        $post = PostFactory::createOne([
            'author' => UserFactory::createOne([
                'username' => 'admin_test'
            ])
        ]);
        $this->assertInstanceOf(Post::class, $post->object());

        $this->postEntityListener->prePersist($post->object());
    }

    public function testPrePersistWithIncorrectUsername(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Incorrect admin username sent.');

        $post = PostFactory::createOne([
            'author' => UserFactory::createOne([
                'username' => 'incorrect_username'
            ])
        ]);
        $this->assertInstanceOf(Post::class, $post->object());

        $this->postEntityListener->prePersist($post->object());
    }
}
