<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    public function __construct(
        ManagerRegistry $registry,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($registry, User::class);
    }

    /**
     * Save an entity.
     *
     * @param  \App\Entity\User  $entity
     * @param  bool  $flush
     *
     * @return void
     */
    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove an entity.
     *
     * @param  \App\Entity\User  $entity
     * @param  bool  $flush
     *
     * @return void
     */
    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Create a user entity by an array of attributes.
     *
     * @param  array  $attributes
     *
     * @return \App\Entity\User
     */
    public function create(array $attributes): User
    {
        $user = new User();
        $this->update($user, $attributes);

        if (!empty($attributes['roles'])) {
            $user->setRoles($attributes['roles']);
        }

        return $user;
    }

    /**
     * Updates user entity's attributes by an array.
     *
     * @param  \App\Entity\User  $user
     * @param  array  $attributes
     *
     * @return \App\Entity\User
     */
    public function update(User $user, array $attributes): User
    {
        if (!empty($attributes['email'])) {
            $user->setEmail($attributes['email']);
        }
        if (!empty($attributes['password'])) {
            $this->setUnhashedPassword($user, $attributes['password']);
        }
        if (!empty($attributes['firstName'])) {
            $user->setFirstName($attributes['firstName']);
        }
        if (!empty($attributes['lastName'])) {
            $user->setLastName($attributes['lastName']);
        }
        if (!empty($attributes['phone'])) {
            $user->setPhone($attributes['phone']);
        }

        return $user;
    }

    /**
     * Hashes and sets user's password.
     *
     * @param  \App\Entity\User  $user
     * @param  string  $unhashedPassword
     *
     * @return void
     */
    public function setUnhashedPassword(PasswordAuthenticatedUserInterface $user, string $unhashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $unhashedPassword);
        $user->setPassword($hashedPassword);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param  \App\Entity\User  $user
     * @param  string  $newHashedPassword
     *
     * @return void
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }
}
