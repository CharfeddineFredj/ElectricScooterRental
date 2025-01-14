<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/resrvation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reservation[model]' => 'Testing',
            'reservation[qte]' => 'Testing',
            'reservation[prix]' => 'Testing',
            'reservation[datersv]' => 'Testing',
            'reservation[heurersv]' => 'Testing',
            'reservation[periode]' => 'Testing',
            'reservation[impot]' => 'Testing',
            'reservation[total]' => 'Testing',
            'reservation[mtotal]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setModel('My Title');
        $fixture->setQte('My Title');
        $fixture->setPrix('My Title');
        $fixture->setDatersv('My Title');
        $fixture->setHeurersv('My Title');
        $fixture->setPeriode('My Title');
        $fixture->setImpot('My Title');
        $fixture->setTotal('My Title');
        $fixture->setMtotal('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setModel('Value');
        $fixture->setQte('Value');
        $fixture->setPrix('Value');
        $fixture->setDatersv('Value');
        $fixture->setHeurersv('Value');
        $fixture->setPeriode('Value');
        $fixture->setImpot('Value');
        $fixture->setTotal('Value');
        $fixture->setMtotal('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[model]' => 'Something New',
            'reservation[qte]' => 'Something New',
            'reservation[prix]' => 'Something New',
            'reservation[datersv]' => 'Something New',
            'reservation[heurersv]' => 'Something New',
            'reservation[periode]' => 'Something New',
            'reservation[impot]' => 'Something New',
            'reservation[total]' => 'Something New',
            'reservation[mtotal]' => 'Something New',
        ]);

        self::assertResponseRedirects('/resrvation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getModel());
        self::assertSame('Something New', $fixture[0]->getQte());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getDatersv());
        self::assertSame('Something New', $fixture[0]->getHeurersv());
        self::assertSame('Something New', $fixture[0]->getPeriode());
        self::assertSame('Something New', $fixture[0]->getImpot());
        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getMtotal());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setModel('Value');
        $fixture->setQte('Value');
        $fixture->setPrix('Value');
        $fixture->setDatersv('Value');
        $fixture->setHeurersv('Value');
        $fixture->setPeriode('Value');
        $fixture->setImpot('Value');
        $fixture->setTotal('Value');
        $fixture->setMtotal('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/resrvation/');
        self::assertSame(0, $this->repository->count([]));
    }
}
