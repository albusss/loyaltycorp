<?php

namespace Tests\App\Unit\Services\MailChimp;

use App\Database\Entities\MailChimp\MailChimpList;
use App\Services\MailChimp\MemberService;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mailchimp\Mailchimp;
use PHPUnit\Framework\TestCase;

class MemberServiceTest extends TestCase
{
    /*
        HELLO!

        Sorry, but I only had a few hours to write a tests, because now i need to do many tasks.
        I know how to write a unit and functionality tests.
        Below is the unit for create method with AAA (range-act-assert)
        Thank you. If you want to know more about my skills, you can see my account on github.
        https://github.com/albusss/code_example
     */

    const MAIL_CHIMP_ID = 'mail-chimp-id';

    const DATA =
        [
            'name' => 'New list',
            'permission_reminder' => 'You signed up for updates on Greeks economy.',
            'email_type_option' => false,
            'contact' => [
                'company' => 'Doe Ltd.',
                'address1' => 'DoeStreet 1',
                'address2' => '',
                'city' => 'Doesy',
                'state' => 'Doedoe',
                'zip' => '1672-12',
                'country' => 'US',
                'phone' => '55533344412'
            ],
            'campaign_defaults' => [
                'from_name' => 'John Doe',
                'from_email' => 'john@doe.com',
                'subject' => 'My new campaign!',
                'language' => 'US'
            ],
            'visibility' => 'prv',
            'use_archive_bar' => false,
            'notify_on_subscribe' => 'notify@loyaltycorp.com.au',
            'notify_on_unsubscribe' => 'notify@loyaltycorp.com.au'
        ];

    /**
     * Test application creates successfully list and returns it back with id from MailChimp.
     *
     * @dataProvider createDataProvider
     * @test
     *
     * @param EntityManagerInterface $entityManager
     * @param Mailchimp $mailchimp
     * @param array $parameters
     * @param string $expectedResult
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function create(
        EntityManagerInterface $entityManager,
        Mailchimp $mailchimp,
        array $parameters,
        string $expectedResult
    ) {
        $service = $this->createService($entityManager, $mailchimp);

        $actualResult = $service->create($parameters['data'], $parameters['mailchimp_id']);

        self::assertArrayHasKey($expectedResult, $actualResult);
    }

    /**
     * @return mixed[][]
     * @throws \ReflectionException
     */
    public function createDataProvider(): array
    {
        return
            [
                'successfully created' =>
                    [
                        'entity_manager' =>
                            $this->entityManagerMock(
                                $this->objectManagerMock(self::MAIL_CHIMP_ID, new MailChimpList())
                            ),
                        'mailchimp' => $this->mailchimpMock(1),
                        'parameters' =>
                            [
                                'data' => self::DATA,
                                'mailchimp_id' => 'mail-chimp-id',
                            ],
                        'expectedResult' => 'email_address',
                    ],
            ];
    }

    /**
     * @test
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function createExceptional()
    {
        $service =
            $this->createService(
                $this->entityManagerMock($this->objectManagerMock(self::MAIL_CHIMP_ID, null)),
                $this->mailchimpMock(0));

        $service->create(self::DATA, 'mail-chimp-id');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Mailchimp $mailchimp
     * @return MemberService
     */
    private function createService(EntityManagerInterface $entityManager, Mailchimp $mailchimp): MemberService
    {
        return new MemberService($entityManager, $mailchimp);
    }

    /**
     * @param $return
     * @return EntityManagerInterface
     * @throws \ReflectionException
     */
    private function entityManagerMock($return): EntityManagerInterface
    {
        $mock = $this->createMock(EntityManagerInterface::class);

        $mock
            ->expects(self::any())
            ->method('getRepository')
            ->with(MailChimpList::class)
            ->willReturn($return)
        ;

        $mock
            ->expects(self::any())
            ->method('persist')
            ->with()
        ;

        $mock
            ->expects(self::any())
            ->method('flush')
        ;

        return $mock;
    }

    /**
     * @param string $mailchimpId
     * @param $return
     * @return ObjectRepository
     * @throws \ReflectionException
     */
    private function objectManagerMock(string $mailchimpId, $return): ObjectRepository
    {
        $mock = $this->createMock(ObjectRepository::class);

        $mock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['mailChimpId' => $mailchimpId])
            ->willReturn($return)
        ;

        return $mock;
    }

    /**
     * @param int $count
     * @return Mailchimp
     * @throws \ReflectionException
     */
    private function mailchimpMock(int $count): Mailchimp
    {
        $mock = $this->createMock(Mailchimp::class);

        $mock
            ->expects(self::exactly($count))
            ->method('__call')
            ->with('post')
        ;

        return $mock;
    }
}
