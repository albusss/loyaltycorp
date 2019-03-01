<?php

namespace App\Services\MailChimp;

use App\Database\Entities\MailChimp\MailChimpList;
use App\Database\Entities\MailChimp\MailChimpMember;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
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
     * MemberService constructor.
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
     * Create MailChimp list member.
     * @param array $data
     * @param string $mailChimpId
     * @return array
     * @throws EntityNotFoundException
     */
    public function create(array $data, string $mailChimpId): array
    {
        $member = new MailChimpMember($data);

        /** @var \App\Database\Entities\MailChimp\MailChimpList|null $list */
        $list = $this->entityManager->getRepository(MailChimpList::class)->findOneBy(['mailChimpId' => $mailChimpId]);

        if ($list === null) {

            throw new EntityNotFoundException(sprintf('MailChimpList[%s] not found', $mailChimpId));
        }

        $list->addMember($member);

        $this->entityManager->persist($list);
        $this->entityManager->flush();

        // Save list into MailChimp
        $this->mailChimp->post(sprintf('lists/%s/members', $mailChimpId), $member->toMailChimpArray());

        return $member->toArray();
    }

    /**
     * Retrieve and return MailChimp list members.
     *
     * @param string $mailChimpId
     * @return array
     * @throws EntityNotFoundException
     */
    public function show(string $mailChimpId): array
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpList|null $list */
        $list = $this->entityManager->getRepository(MailChimpList::class)->findOneBy(['mailChimpId' => $mailChimpId]);

        if ($list === null) {

            throw new EntityNotFoundException(sprintf('MailChimpList[%s] not found', $mailChimpId));
        }

        $result = [];
        foreach ($list->getMembers() as $member) {
            $result[] = $member->toArray();
        }

        return $result;
    }
}
