<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class UpdatePassword
{
    
   
    private $oldPassword;

    /**
     * @Assert\length(min=8, minMessage="votre mot de passe doit contenir au 8 caractères")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message=" les mots de passe ne correspondent pas")
     */
    private $confirmPassword;

   
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
