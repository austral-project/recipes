<?php
/*
 * This file is autogenerate and part of the Austral Log package.
 *
 * (c) Austral <support@austral.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Austral\LogBundle;

use Austral\LogBundle\Entity\ErrorLog as BaseErrorLog;

use Doctrine\ORM\Mapping as ORM;

/**
 * Austral Error Log Entity.
 *
 * @author Matthieu Beurel <matthieu@austral.dev>
 */
#[ORM\Table(name: "austral_log_error")]
#[ORM\Index(name: "austral_log_error_status", columns: ["status"])]
#[ORM\Index(name: "austral_log_error_last_seen", columns: ["last_seen"])]
#[ORM\Entity(repositoryClass: \Austral\LogBundle\Repository\ErrorLogRepository::class)]
class ErrorLog extends BaseErrorLog
{
}
