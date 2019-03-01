<?php

namespace App\Services\MailChimp;

use App\Database\Entities\MailChimp\MailChimpMember;
use Doctrine\ORM\EntityManagerInterface;
use Mailchimp\Mailchimp;

class MemberService
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Mailchimp\Mailchimp
     */
    private $mailChimp;

    /**
     * ListsController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Mailchimp\Mailchimp $mailchimp
     */
    public function __construct(EntityManagerInterface $entityManager, Mailchimp $mailchimp)
    {
        $this->entityManager = $entityManager;
        $this->mailChimp = $mailchimp;
    }

    /**
     * Create MailChimp list.
     *
     * @param array $data
     * @param string $listId
     * @return array
     */
    public function create(array $data, string $listId): array
    {
        $member = new MailChimpMember($data);

        $this->entityManager->persist($member);
        $this->entityManager->flush();

        // Save list into MailChimp
        $this->mailChimp->post(sprintf('lists/%s/members', $listId), $member->toMailChimpArray());

        return $member->toArray();
    }
}
