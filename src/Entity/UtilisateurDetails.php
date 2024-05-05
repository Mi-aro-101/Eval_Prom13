<?php

namespace App\Entity;

use App\Repository\UtilisateurDetailsRepository;
use DateException;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;

#[ORM\Entity(repositoryClass: UtilisateurDetailsRepository::class)]
class UtilisateurDetails
{

    public function processDatas(Request $request, EntityManagerInterface $entityManager) : UtilisateurDetails
    {
        // Get id
        $id = $this->getIdFromDb($entityManager);
        $nom = $request->request->get('nom');
        $nom = trim($nom);
        $dateDeNaissance = $request->request->get('dateDeNaissance');
        $dateDeNaissance = DateTimeImmutable::createFromFormat('Y-m-d', $dateDeNaissance);
        $this->setId($id);
        $this->setNom($nom);
        $this->setDateDeNaissance($dateDeNaissance);
        $role = $request->attributes->get('role');
        $this->setRole($role);
        return $this;
    }

    public function getIdFromDb(EntityManagerInterface $entityManager)
    {
        $sql = "SELECT nextval('utilisateur_details_id_seq') as id";
        $id = $entityManager->getConnection()->fetchAssociative($sql);
        $id = $id['id'];
        return $id;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDeNaissance = null;

    #[ORM\Column]
    private ?int $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setId(int $id) : static
    {
        $this->id = $id;

        return $this;
    }

    public function setDateDeNaissance(\DateTimeInterface $dateDeNaissance): static
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): static
    {
        $this->role = $role;

        return $this;
    }
}
