<?php

namespace Drupal\voting\Helper;

trait VotesHelper 
{
  /**
   * Helper function to check if user has voted on a given node.
   */
  public function checkIfVoted($nid, $uid = NULL) 
  {
    $uid = empty($uid) ? \Drupal::currentUser()->id() : $uid;
    $rows = $this->select([
      ['v.uid', $uid, '='],
      ['v.nid', $nid, '=']
    ]);
    return !empty($rows);
  }
  /**
   * Get all votes for a given node.
   * Format for percentage and total count.
   */
  public function getTotalVotes($nid) 
  {
    $rows = $this->select([['v.nid', $nid, '=']]);
    $count = count($rows);
    $votes = [];
    array_walk($rows, function($row) use(&$votes) {
      $votes[$row->pid]['count']++; 
    });
    foreach($votes as &$vote) {
      $vote['percentage'] = round(($vote['count'] * 100) / $count);
    }
    return $votes;
  }
  /**
   * @TODO
   */
  public function getTotalCount($nid) 
  {
    $rows = $this->select([['v.nid', $nid, '=']]);
    return count($rows);
  }

}