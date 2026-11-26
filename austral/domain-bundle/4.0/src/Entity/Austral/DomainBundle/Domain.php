<?php
/*
 * This file is autogenerate and part of the Austral Domain Bundle package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Austral\DomainBundle;

use Austral\DomainBundle\Entity\Domain as BaseDomain;

use Doctrine\ORM\Mapping as ORM;

/**
 * Austral Domain Entity.
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
#[ORM\Table(name: "austral_http_domain")]
#[ORM\Entity(repositoryClass: \Austral\DomainBundle\Repository\DomainRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Domain extends BaseDomain
{
  /**
   * Domain constructor.
   * @throws \Exception
   */
  public function __construct()
  {
    parent::__construct();
  }
}
