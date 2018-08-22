<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 21.08.2018
 * Time: 17:13
 */

namespace SecretBundle\Factories;

use SecretBundle\Entity\UserInfo;
use SecretBundle\Entity\UserExperience;
use SecretBundle\Entity\UserPresence;

use SecretBundle\Interfaces\UsersInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class UsersFactory implements UsersInterface
{
    /**
     * @param string $userType
     * @return UserExperience|UserInfo|UserPresence
     */
    public static function create(string $userType)
    {
        switch($userType){
            case 'UserInfo':
                $instance = new UserInfo();
                break;
            case 'UserExperience':
                $instance = new UserExperience();
                break;
            case 'UserPresence':
                $instance = new UserPresence();
                break;
            default:
                throw new Exception('Undefined type of user.');
        }

        return $instance;
    }
}