<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Message\CommentMessage;
use App\Notification\CommentReviewNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;

class TinyChefController extends AbstractController
{
    /**
     * @Route("/", name="tiny_chef")
     */
    public function index(NotifierInterface $notifier)
    {
        $notifier->send(new Notification('Thank you for the feedback; your comment will be posted after moderation.', ['browser']));

        $comment = new Comment();
        $comment->setId(1);
        $comment->setAuthor('test');
        $comment->setEmail('test@test.com');
        $comment->setState('publish');

        $message = new CommentMessage(42, 'http://www.google.fr');

        $notification = new CommentReviewNotification($comment, $message->getReviewUrl());
        $notifier->send($notification, ...$notifier->getAdminRecipients());

        return $this->render('grumpy_chef/index.html.twig', [
            'controller_name' => 'GrumpyChefController',
        ]);
    }
}
