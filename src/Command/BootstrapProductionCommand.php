<?php

namespace App\Command;

use App\Entity\Organisation;
use App\Entity\User;
use App\Entity\UserOrganisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:bootstrap-production',
    description: 'Create the minimum production data needed to use the admin UI.',
)]
class BootstrapProductionCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $adminEmail = $this->getRequiredEnv('BOOTSTRAP_ADMIN_EMAIL');
        $adminPassword = $this->getRequiredEnv('BOOTSTRAP_ADMIN_PASSWORD');
        $organisationName = $this->getRequiredEnv('BOOTSTRAP_ORGANISATION_NAME');

        if ($adminEmail === null || $adminPassword === null || $organisationName === null) {
            $io->error('Missing bootstrap env vars. Required: BOOTSTRAP_ADMIN_EMAIL, BOOTSTRAP_ADMIN_PASSWORD, BOOTSTRAP_ORGANISATION_NAME');

            return Command::FAILURE;
        }

        $organisation = $this->em->getRepository(Organisation::class)->findOneBy(['name' => $organisationName]);
        if (!$organisation instanceof Organisation) {
            $organisation = (new Organisation())
                ->setName($organisationName)
                ->setPublic(false);
            $this->em->persist($organisation);
            $io->writeln(sprintf('Created organisation: %s', $organisationName));
        } else {
            $io->writeln(sprintf('Organisation already exists: %s', $organisationName));
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $adminEmail]);
        if (!$user instanceof User) {
            $user = (new User())
                ->setEmail($adminEmail)
                ->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $adminPassword));
            $this->em->persist($user);
            $io->writeln(sprintf('Created admin user: %s', $adminEmail));
        } else {
            $roles = $user->getRoles();
            if (!in_array('ROLE_ADMIN', $roles, true)) {
                $roles[] = 'ROLE_ADMIN';
                $user->setRoles($roles);
            }
            $io->writeln(sprintf('Admin user already exists: %s', $adminEmail));
        }

        $this->em->flush();

        $link = $this->em->getRepository(UserOrganisation::class)->findOneBy([
            'appuser' => $user,
            'organisation' => $organisation,
        ]);

        if (!$link instanceof UserOrganisation) {
            $link = (new UserOrganisation())
                ->setAppuser($user)
                ->setOrganisation($organisation);
            $this->em->persist($link);
            $io->writeln('Created user-organisation link.');
        }

        if ($user->getSelectedOrganisation()?->getId() !== $organisation->getId()) {
            $user->setSelectedOrganisation($organisation);
            $this->em->persist($user);
            $io->writeln('Selected organisation set for admin user.');
        }

        $this->em->flush();

        $io->success('Production bootstrap data is ready.');

        return Command::SUCCESS;
    }

    private function getRequiredEnv(string $name): ?string
    {
        $value = $_SERVER[$name] ?? $_ENV[$name] ?? getenv($name) ?: null;

        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        return trim($value);
    }
}
