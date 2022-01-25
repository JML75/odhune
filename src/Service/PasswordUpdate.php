<?php
namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{

// pattern ="/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$€@%_])([-+!*$€@%_\w]{8,15})$/",

/**
 * @Assert\NotBlank(message="Veuillez saisir votre ancien mot de passe")
 * @Assert\Regex(
     *  pattern ="//",
     *  message="8-15 caractères, au moins: 1 majuscule 1 minuscule  1 car. parmi - + ! * $ € @ % _"
     * )
 */

private $oldPassword;

/**
* @Assert\NotBlank(message="Veuillez saisir votre ancien mot de passe")
* @Assert\EqualTo(
* propertyPath="confirmPassword",
* message="Les mots de passe ne sont pas identiques"
* )
*/

private $newPassword;

/**
 * @Assert\NotBlank(message="Veuillez saisir votre nouveau mot de passe")
 */
private $confirmPassword;


public function getOldPassword () :?string
{
    return $this-> oldPassword;
}

public function setOldPassword (string $oldPassword) 
{
    $this-> oldPassword = $oldPassword;
    return $this;
}
public function getNewPassword () :?string
{
    return $this-> newPassword;
}

public function setNewPassword (string $newPassword) 
{
    $this-> newPassword = $newPassword;
    return $this;
}

public function getConfirmPassword () :?string
{
    return $this-> confirmPassword;
}

public function setConfirmPassword (string $confirmPassword) 
{
    $this-> confirmPassword = $confirmPassword;
    return $this;
}
   

} //fin de classe