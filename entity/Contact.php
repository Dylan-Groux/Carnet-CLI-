<?php
class Contact {
    private int $id;
    private string $name;
    private string $email;
    private string $phone_number;

    public function __construct(int $id, string $name, string $email, string $phone_number) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone_number = $phone_number;
    }

    public function getId(): int {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getPhoneNumber(): string {
        return $this->phone_number;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }
    public function setEmail(?string $email): void {
        $this->email = $email;
    }
    public function setPhoneNumber(?string $phone_number): void {
        $this->phone_number = $phone_number;
    }

    public function __toString (): string {
        return "ID: " . $this->id . ", Name: " . $this->name . ", Email: " . $this->email . ", Phone Number: " . $this->phone_number;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}