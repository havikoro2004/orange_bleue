<?php

namespace App\Entity;

use App\Repository\VillesFranceFreeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VillesFranceFreeRepository::class)]
class VillesFranceFree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ville_id = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $ville_departement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville_slug = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ville_nom = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ville_nom_simple = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ville_nom_reel = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $ville_nom_soundex = null;

    #[ORM\Column(length: 22, nullable: true)]
    private ?string $ville_nom_metaphone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville_code_postal = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $ville_commune = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville_code_commune = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_arrondissement = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $ville_canton = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_amdi = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_population_2010 = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_population_1999 = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_population_2012 = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_densite_2010 = null;

    #[ORM\Column(nullable: true)]
    private ?float $ville_surface = null;

    #[ORM\Column(nullable: true)]
    private ?float $ville_longitude_deg = null;

    #[ORM\Column(nullable: true)]
    private ?float $ville_latitude_deg = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $ville_longitude_grd = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $ville_latitude_grd = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $ville_longitude_dms = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $ville_latitude_dms = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_zmin = null;

    #[ORM\Column(nullable: true)]
    private ?int $ville_zmax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilleId(): ?int
    {
        return $this->ville_id;
    }

    public function setVilleId(int $ville_id): self
    {
        $this->ville_id = $ville_id;

        return $this;
    }

    public function getVilleDepartement(): ?string
    {
        return $this->ville_departement;
    }

    public function setVilleDepartement(?string $ville_departement): self
    {
        $this->ville_departement = $ville_departement;

        return $this;
    }

    public function getVilleSlug(): ?string
    {
        return $this->ville_slug;
    }

    public function setVilleSlug(?string $ville_slug): self
    {
        $this->ville_slug = $ville_slug;

        return $this;
    }

    public function getVilleNom(): ?string
    {
        return $this->ville_nom;
    }

    public function setVilleNom(?string $ville_nom): self
    {
        $this->ville_nom = $ville_nom;

        return $this;
    }

    public function getVilleNomSimple(): ?string
    {
        return $this->ville_nom_simple;
    }

    public function setVilleNomSimple(?string $ville_nom_simple): self
    {
        $this->ville_nom_simple = $ville_nom_simple;

        return $this;
    }

    public function getVilleNomReel(): ?string
    {
        return $this->ville_nom_reel;
    }

    public function setVilleNomReel(?string $ville_nom_reel): self
    {
        $this->ville_nom_reel = $ville_nom_reel;

        return $this;
    }

    public function getVilleNomSoundex(): ?string
    {
        return $this->ville_nom_soundex;
    }

    public function setVilleNomSoundex(?string $ville_nom_soundex): self
    {
        $this->ville_nom_soundex = $ville_nom_soundex;

        return $this;
    }

    public function getVilleNomMetaphone(): ?string
    {
        return $this->ville_nom_metaphone;
    }

    public function setVilleNomMetaphone(?string $ville_nom_metaphone): self
    {
        $this->ville_nom_metaphone = $ville_nom_metaphone;

        return $this;
    }

    public function getVilleCodePostal(): ?string
    {
        return $this->ville_code_postal;
    }

    public function setVilleCodePostal(?string $ville_code_postal): self
    {
        $this->ville_code_postal = $ville_code_postal;

        return $this;
    }

    public function getVilleCommune(): ?string
    {
        return $this->ville_commune;
    }

    public function setVilleCommune(?string $ville_commune): self
    {
        $this->ville_commune = $ville_commune;

        return $this;
    }

    public function getVilleCodeCommune(): ?string
    {
        return $this->ville_code_commune;
    }

    public function setVilleCodeCommune(?string $ville_code_commune): self
    {
        $this->ville_code_commune = $ville_code_commune;

        return $this;
    }

    public function getVilleArrondissement(): ?int
    {
        return $this->ville_arrondissement;
    }

    public function setVilleArrondissement(?int $ville_arrondissement): self
    {
        $this->ville_arrondissement = $ville_arrondissement;

        return $this;
    }

    public function getVilleCanton(): ?string
    {
        return $this->ville_canton;
    }

    public function setVilleCanton(?string $ville_canton): self
    {
        $this->ville_canton = $ville_canton;

        return $this;
    }

    public function getVilleAmdi(): ?int
    {
        return $this->ville_amdi;
    }

    public function setVilleAmdi(?int $ville_amdi): self
    {
        $this->ville_amdi = $ville_amdi;

        return $this;
    }

    public function getVillePopulation2010(): ?int
    {
        return $this->ville_population_2010;
    }

    public function setVillePopulation2010(?int $ville_population_2010): self
    {
        $this->ville_population_2010 = $ville_population_2010;

        return $this;
    }

    public function getVillePopulation1999(): ?int
    {
        return $this->ville_population_1999;
    }

    public function setVillePopulation1999(?int $ville_population_1999): self
    {
        $this->ville_population_1999 = $ville_population_1999;

        return $this;
    }

    public function getVillePopulation2012(): ?int
    {
        return $this->ville_population_2012;
    }

    public function setVillePopulation2012(?int $ville_population_2012): self
    {
        $this->ville_population_2012 = $ville_population_2012;

        return $this;
    }

    public function getVilleDensite2010(): ?int
    {
        return $this->ville_densite_2010;
    }

    public function setVilleDensite2010(?int $ville_densite_2010): self
    {
        $this->ville_densite_2010 = $ville_densite_2010;

        return $this;
    }

    public function getVilleSurface(): ?float
    {
        return $this->ville_surface;
    }

    public function setVilleSurface(?float $ville_surface): self
    {
        $this->ville_surface = $ville_surface;

        return $this;
    }

    public function getVilleLongitudeDeg(): ?float
    {
        return $this->ville_longitude_deg;
    }

    public function setVilleLongitudeDeg(?float $ville_longitude_deg): self
    {
        $this->ville_longitude_deg = $ville_longitude_deg;

        return $this;
    }

    public function getVilleLatitudeDeg(): ?float
    {
        return $this->ville_latitude_deg;
    }

    public function setVilleLatitudeDeg(?float $ville_latitude_deg): self
    {
        $this->ville_latitude_deg = $ville_latitude_deg;

        return $this;
    }

    public function getVilleLongitudeGrd(): ?string
    {
        return $this->ville_longitude_grd;
    }

    public function setVilleLongitudeGrd(?string $ville_longitude_grd): self
    {
        $this->ville_longitude_grd = $ville_longitude_grd;

        return $this;
    }

    public function getVilleLatitudeGrd(): ?string
    {
        return $this->ville_latitude_grd;
    }

    public function setVilleLatitudeGrd(?string $ville_latitude_grd): self
    {
        $this->ville_latitude_grd = $ville_latitude_grd;

        return $this;
    }

    public function getVilleLongitudeDms(): ?string
    {
        return $this->ville_longitude_dms;
    }

    public function setVilleLongitudeDms(?string $ville_longitude_dms): self
    {
        $this->ville_longitude_dms = $ville_longitude_dms;

        return $this;
    }

    public function getVilleLatitudeDms(): ?string
    {
        return $this->ville_latitude_dms;
    }

    public function setVilleLatitudeDms(string $ville_latitude_dms): self
    {
        $this->ville_latitude_dms = $ville_latitude_dms;

        return $this;
    }

    public function getVilleZmin(): ?int
    {
        return $this->ville_zmin;
    }

    public function setVilleZmin(?int $ville_zmin): self
    {
        $this->ville_zmin = $ville_zmin;

        return $this;
    }

    public function getVilleZmax(): ?int
    {
        return $this->ville_zmax;
    }

    public function setVilleZmax(?int $ville_zmax): self
    {
        $this->ville_zmax = $ville_zmax;

        return $this;
    }

    public function toArray(){
        return [
            'code'=>$this->getVilleCodePostal(),
            'ville'=>$this->getVilleNomReel()
        ];
    }
}
