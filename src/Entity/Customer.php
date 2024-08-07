<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length:255)]
    private $fullname;

    #[ORM\Column(type: "string", length:255, unique: true)]     
    private $email;

    #[ORM\Column(type: "string", length:255)]     
    private $username;

    #[ORM\Column(type: "string", length:10)]     
    private $gender;

    #[ORM\Column(type: "string", length:255)]   
    private $country;

    #[ORM\Column(type: "string", length:255)]   
    private $city;

    #[ORM\Column(type: "string", length:20)]   
    private $phone;

    #[ORM\Column(type: "string", length:255)]   
    private $password;

    // #[ORM\Column(type: "string", length:255)]   
    // private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFullName(string $fullname): self
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullname;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }
    
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPassword(string $hashedpassword): self
    {
        $this->password = $hashedpassword;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
