<?php

namespace App\Entity;

use App\Repository\AlumnosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlumnosRepository::class)]
class Alumnos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 100)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 50)]
    private ?string $correo_electronico = null;

    #[ORM\ManyToMany(targetEntity: Cursos::class, inversedBy: 'alumnos')]
    private Collection $cursos;

    public function __construct()
    {
        $this->cursos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCorreoElectronico(): ?string
    {
        return $this->correo_electronico;
    }

    public function setCorreoElectronico(string $correo_electronico): static
    {
        $this->correo_electronico = $correo_electronico;

        return $this;
    }

    /**
     * @return Collection<int, Cursos>
     */
    public function getCursos(): Collection
    {
        return $this->cursos;
    }

    public function addCurso(Cursos $curso): static
    {
        if (!$this->cursos->contains($curso)) {
            $this->cursos->add($curso);
        }

        return $this;
    }

    public function removeCurso(Cursos $curso): static
    {
        $this->cursos->removeElement($curso);

        return $this;
    }
}
