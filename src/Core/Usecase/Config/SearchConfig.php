<?php

/**
 * Ushahidi Platform Search Use Case
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Platform
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Usecase\Config;

use Ushahidi\Core\Data;
use Ushahidi\Core\Entity\ConfigRepository;
use Ushahidi\Core\Tool\AuthorizerTrait;
use Ushahidi\Core\Usecase;

class SearchConfig implements Usecase
{
	// Uses several traits to assign tools. Each of these traits provides a
	// setter method for the tool. For example, the AuthorizerTrait provides
	// a `setAuthorizer` method which only accepts `Authorizer` instances.
	use AuthorizerTrait;

	// Ushahidi\Usecase\SearchRepository
	protected $repo;

	public function __construct(Array $tools)
	{
		$this->setAuthorizer($tools['auth']);
		$this->setRepository($tools['repo']);
	}

	protected function setRepository(ConfigRepository $repo)
	{
		$this->repo = $repo;
	}

	public function interact(Data $search)
	{
		$results = $this->repo->all($search->groups);

		foreach ($results as $idx => $entity) {
			if (!$this->auth->isAllowed($entity, 'read')) {
				unset($results[$idx]);
			}
		}

		return $results;
	}
}
