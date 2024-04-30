<?php

namespace Drupal\voting\Controller;

use Drupal\voting\Helper\VotesHelper;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Voting main controller.
 */
class VotesController extends ControllerBase 
{
  use VotesHelper;
  /** Votes table constant */ 
  const TABLE = 'votes';
  /** @var Connection $db */
  protected $db;
  /** @var Messenger $message */
  protected $message;


  /**
   * VotesController constructor.
   *
   * @param Connection $db
   * @param Messenger $message 
   */
  public function __construct(Connection $db, Messenger $message)
  {
    $this->db = $db;
    $this->message = $message;
  }

  /**
   * {@inheritdoc}
   *
   * @param ContainerInterface $container
   *   The Drupal service container.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('messenger')
    );
  }

  /**
   * Callback for votes control.
   * @param $node Node which the vote was submitted.
   */
  public function vote(NodeInterface $node)
  {
    $nid = $node->id();
    $uid = \Drupal::currentUser()->id();
    $vote = \Drupal::request()->request->get('vote');
    if (empty($uid) || empty($vote) || $this->checkIfVoted($nid, $uid)) {
      return $this->forbidden();
    }
    try { 
      $this->insert([
        'nid' => $nid,
        'uid' => $uid,
        'pid' => $vote
      ]);
    } catch (\Throwable $th) { throw $th; }
    // Finally
    $this->message->addMessage(t('Your vote was received !'));
    $url = Url::fromRoute('entity.node.canonical', array('node' => $nid));
    return new RedirectResponse($url->toString());
  }


  

  /**
   * Helper function to query votes table
   * Since we are not using entities, for a simple solution, we'll do here.
   * The correct would be an entity manager.
   */
  private function select($conditions = []) 
  {
    $results = [];
    $query = $this->db->select(self::TABLE, 'v')->fields('v'); 
    if (!empty($conditions)) {
      foreach($conditions as $condition) {
        $query->condition(...$condition);
      }
    }
    // Execute and return as array
    foreach ($query->execute() as $row) {
      $results[] = $row;
    }
    return $results;
  }

  /**
   * Insert new row of votes.
   */
  private function insert($fields) 
  {
    return $this->db->insert(self::TABLE)
      ->fields($fields)
      ->execute();
  }
  
  /**
   * Helper function for a 403 HTTP response.
   */
  private function forbidden()
  {
    throw new AccessDeniedHttpException();
  }

}