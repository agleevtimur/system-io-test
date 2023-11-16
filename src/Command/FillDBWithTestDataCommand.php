<?php

namespace App\Command;

use App\Entity\Coupon;
use App\Entity\DBAL\CouponType;
use App\Entity\Product;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:fill-db-with-test-data', aliases: ['app:fill-db'])]
class FillDBWithTestDataCommand extends Command
{

    public function __construct(private EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
    }

    public function getDescription(): string
    {
        return "Чистит таблицы БД и заполняет тестовыми данными";
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager->beginTransaction();

        try {
            //чистим таблицы, чтобы не было дублирующих записей
            $qb1 = $this->entityManager->createQueryBuilder()
                ->delete(Coupon::class)
                ->getQuery()
                ->execute();

            $qb2 = $this->entityManager->createQueryBuilder()
                ->delete(Product::class)
                ->getQuery()
                ->execute();

            $sm = $this->entityManager->getConnection()->createSchemaManager();
            $sm->dropSequence('coupon_id_seq');
            $sm->createSequence(new Sequence('coupon_id_seq'));
            $sm->dropSequence('product_id_seq');
            $sm->createSequence(new Sequence('product_id_seq'));

            $testData = require 'test_data.php';

            foreach ($testData['product'] as $product) {
                $this->entityManager->persist((new Product())->setName($product['name'])->setPrice($product['price']));
            }

            foreach ($testData['coupon'] as $coupon) {
                $this->entityManager->persist((new Coupon())->setCode($coupon['code'])->setType($coupon['type'])->setDiscount($coupon['discount']));
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            $output->write($exception->getMessage());

            return Command::FAILURE;
        }
    }
}